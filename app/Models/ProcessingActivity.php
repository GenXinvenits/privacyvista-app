<?php
namespace App\Models;
use App\Core\Model;

class ProcessingActivity extends Model
{
    public function forClient(int $clientId): array
    {
        $s = $this->db->prepare('SELECT * FROM processing_activities WHERE client_id=? ORDER BY updated_at DESC,id DESC');
        $s->execute([$clientId]);
        return $s->fetchAll();
    }

    public function find(int $id): ?array
    {
        $s = $this->db->prepare('SELECT * FROM processing_activities WHERE id=? LIMIT 1');
        $s->execute([$id]);
        return $s->fetch() ?: null;
    }

    private function riskLevel(string $risk): string
    {
        $risk = ucfirst(strtolower(trim($risk)));
        return in_array($risk, ['Low', 'Medium', 'High'], true) ? $risk : 'Low';
    }

    private function status(array $d): int
    {
        if (array_key_exists('status', $d)) {
            $status = strtolower(trim((string)$d['status']));
            return in_array($status, ['active', 'under_review'], true) ? 1 : 0;
        }
        return !empty($d['status']) ? 1 : 0;
    }

    private function personalData(array $d): string
    {
        $value = trim((string)($d['personal_data'] ?? $d['personal_data_categories'] ?? ''));
        if (!empty($d['special_category_data'])) {
            $value = trim($value . ($value !== '' ? "\n" : '') . 'Special-category data involved');
        }
        return $value;
    }

    private function securityMeasures(array $d): string
    {
        $value = trim((string)($d['security_measures'] ?? ''));
        $transfer = trim((string)($d['transfer_details'] ?? ''));
        if (!empty($d['international_transfer']) && $transfer !== '') {
            $value = trim($value . ($value !== '' ? "\n\n" : '') . 'International transfer details: ' . $transfer);
        }
        return $value;
    }

    public function create(array $d): int
    {
        $s = $this->db->prepare(
            'INSERT INTO processing_activities
            (client_id,department,name,purpose,legal_basis,data_subjects,personal_data,recipients,retention_period,security_measures,international_transfer,risk_level,status)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );

        $s->execute([
            (int)$d['client_id'],
            trim((string)($d['department'] ?? '')),
            trim((string)($d['name'] ?? '')),
            trim((string)($d['purpose'] ?? '')),
            trim((string)($d['legal_basis'] ?? '')),
            trim((string)($d['data_subjects'] ?? '')),
            $this->personalData($d),
            trim((string)($d['recipients'] ?? '')),
            trim((string)($d['retention_period'] ?? '')),
            $this->securityMeasures($d),
            !empty($d['international_transfer']) ? 1 : 0,
            $this->riskLevel((string)($d['risk_level'] ?? 'Low')),
            $this->status($d),
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(array $d): bool
    {
        $s = $this->db->prepare(
            'UPDATE processing_activities SET
                department=?,name=?,purpose=?,legal_basis=?,data_subjects=?,personal_data=?,recipients=?,retention_period=?,security_measures=?,international_transfer=?,risk_level=?,status=?
             WHERE id=? AND client_id=?'
        );

        return $s->execute([
            trim((string)($d['department'] ?? '')),
            trim((string)($d['name'] ?? '')),
            trim((string)($d['purpose'] ?? '')),
            trim((string)($d['legal_basis'] ?? '')),
            trim((string)($d['data_subjects'] ?? '')),
            $this->personalData($d),
            trim((string)($d['recipients'] ?? '')),
            trim((string)($d['retention_period'] ?? '')),
            $this->securityMeasures($d),
            !empty($d['international_transfer']) ? 1 : 0,
            $this->riskLevel((string)($d['risk_level'] ?? 'Low')),
            $this->status($d),
            (int)$d['id'],
            (int)$d['client_id'],
        ]);
    }

    public function deleteForClient(int $id, int $clientId): bool
    {
        $s = $this->db->prepare('DELETE FROM processing_activities WHERE id=? AND client_id=?');
        return $s->execute([$id, $clientId]);
    }
}
