<?php

namespace App\Models;

use App\Core\Model;

class Client extends Model
{
    protected string $table = 'clients';

    public function allClients(): array
    {
        return $this->db
            ->query("SELECT * FROM clients ORDER BY company_name")
            ->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM clients
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO clients
            (
                company_name,
                contact_person,
                email,
                phone,
                status
            )
            VALUES
            (?, ?, ?, ?, 1)
        ");

        return $stmt->execute([
            trim($data['company_name']),
            trim($data['contact_person']),
            trim($data['email']),
            trim($data['phone'])
        ]);
    }

    public function update(array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE clients
            SET
                company_name=?,
                contact_person=?,
                email=?,
                phone=?,
                status=?
            WHERE id=?
        ");

        return $stmt->execute([
            $data['company_name'],
            $data['contact_person'],
            $data['email'],
            $data['phone'],
            $data['status'],
            $data['id']
        ]);
    }

    public function deleteClient(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM clients
            WHERE id=?
        ");

        return $stmt->execute([$id]);
    }
}