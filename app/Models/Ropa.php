<?php

namespace App\Models;

use App\Core\Model;

class Ropa extends Model
{
    public function forClient(int $clientId): array
    {
        $s = $this->db->prepare('SELECT * FROM ropa_records WHERE client_id=? ORDER BY updated_at DESC,id DESC');
        $s->execute([$clientId]);
        $rows = $s->fetchAll();
        foreach ($rows as &$row) {
            $row['data'] = $this->decode($row['data'] ?? null);
        }
        return $rows;
    }

    public function find(int $id): ?array
    {
        $s = $this->db->prepare('SELECT * FROM ropa_records WHERE id=? LIMIT 1');
        $s->execute([$id]);
        $row = $s->fetch();
        if (!$row) return null;
        $row['data'] = $this->decode($row['data'] ?? null);
        return $row;
    }

    public function create(array $data): int
    {
        $s = $this->db->prepare(
            'INSERT INTO ropa_records (client_id,process_name,status,data,created_by) VALUES (?,?,?,?,?)'
        );
        $s->execute([
            (int)$data['client_id'],
            trim((string)$data['process_name']),
            trim((string)($data['status'] ?? 'planned')),
            json_encode($data['payload'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            !empty($data['created_by']) ? (int)$data['created_by'] : null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(array $data): bool
    {
        $s = $this->db->prepare(
            'UPDATE ropa_records SET process_name=?,status=?,data=? WHERE id=? AND client_id=?'
        );
        return $s->execute([
            trim((string)$data['process_name']),
            trim((string)($data['status'] ?? 'planned')),
            json_encode($data['payload'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            (int)$data['id'],
            (int)$data['client_id'],
        ]);
    }

    private function decode($json): array
    {
        if (is_array($json)) return $json;
        $decoded = json_decode((string)$json, true);
        return is_array($decoded) ? $decoded : [];
    }
}
