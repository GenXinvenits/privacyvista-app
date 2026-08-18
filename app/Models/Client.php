<?php

namespace App\Models;

use App\Core\Model;

class Client extends Model
{
    protected string $table = 'clients';

    public function allClients(string $search = ''): array
    {
        if ($search === '') return $this->db->query('SELECT * FROM clients ORDER BY company_name')->fetchAll();
        $stmt = $this->db->prepare('SELECT * FROM clients WHERE company_name LIKE ? OR contact_person LIKE ? OR email LIKE ? ORDER BY company_name');
        $like = '%'.$search.'%';
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM clients WHERE id=? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO clients (company_name,contact_person,email,phone,status) VALUES (?,?,?,?,1)');
        return $stmt->execute([trim($data['company_name']),trim($data['contact_person'] ?? ''),trim($data['email'] ?? ''),trim($data['phone'] ?? '')]);
    }

    public function update(array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE clients SET company_name=?,contact_person=?,email=?,phone=?,status=? WHERE id=?');
        return $stmt->execute([trim($data['company_name']),trim($data['contact_person'] ?? ''),trim($data['email'] ?? ''),trim($data['phone'] ?? ''),(int)($data['status'] ?? 1),(int)$data['id']]);
    }

    public function deleteClient(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM clients WHERE id=?');
        return $stmt->execute([$id]);
    }
}
