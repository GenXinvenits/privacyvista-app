<?php

namespace App\Models;

use App\Core\Model;

class ProcessingActivity extends Model
{
    public function forClient(int $clientId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM processing_activities WHERE client_id = ? ORDER BY updated_at DESC, id DESC');
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM processing_activities WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO processing_activities (client_id, department_id, name, description, purpose, legal_basis, data_subjects, personal_data_categories, special_category_data, recipients, international_transfer, transfer_details, retention_period, security_measures, risk_level, status, owner_user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $data['client_id'], $data['department_id'] ?: null, trim($data['name']), trim($data['description'] ?? ''),
            trim($data['purpose'] ?? ''), trim($data['legal_basis'] ?? ''), trim($data['data_subjects'] ?? ''),
            trim($data['personal_data_categories'] ?? ''), !empty($data['special_category_data']) ? 1 : 0,
            trim($data['recipients'] ?? ''), !empty($data['international_transfer']) ? 1 : 0,
            trim($data['transfer_details'] ?? ''), trim($data['retention_period'] ?? ''), trim($data['security_measures'] ?? ''),
            in_array($data['risk_level'] ?? '', ['low','medium','high','critical'], true) ? $data['risk_level'] : 'medium',
            in_array($data['status'] ?? '', ['draft','active','under_review','archived'], true) ? $data['status'] : 'draft',
            $data['owner_user_id'] ?: null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE processing_activities SET department_id=?, name=?, description=?, purpose=?, legal_basis=?, data_subjects=?, personal_data_categories=?, special_category_data=?, recipients=?, international_transfer=?, transfer_details=?, retention_period=?, security_measures=?, risk_level=?, status=?, owner_user_id=? WHERE id=? AND client_id=?');
        return $stmt->execute([
            $data['department_id'] ?: null, trim($data['name']), trim($data['description'] ?? ''), trim($data['purpose'] ?? ''),
            trim($data['legal_basis'] ?? ''), trim($data['data_subjects'] ?? ''), trim($data['personal_data_categories'] ?? ''),
            !empty($data['special_category_data']) ? 1 : 0, trim($data['recipients'] ?? ''), !empty($data['international_transfer']) ? 1 : 0,
            trim($data['transfer_details'] ?? ''), trim($data['retention_period'] ?? ''), trim($data['security_measures'] ?? ''),
            in_array($data['risk_level'] ?? '', ['low','medium','high','critical'], true) ? $data['risk_level'] : 'medium',
            in_array($data['status'] ?? '', ['draft','active','under_review','archived'], true) ? $data['status'] : 'draft',
            $data['owner_user_id'] ?: null, $data['id'], $data['client_id']
        ]);
    }

    public function delete(int $id, int $clientId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM processing_activities WHERE id=? AND client_id=?');
        return $stmt->execute([$id, $clientId]);
    }
}
