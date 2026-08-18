<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function getAllUsers(): array
    {
        $sql = "
            SELECT
                u.id,
                u.fullname,
                u.email,
                r.name AS role,
                u.status
            FROM users u
            JOIN roles r ON r.id = u.role_id
            ORDER BY u.id DESC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users
            (
                client_id,
                role_id,
                fullname,
                email,
                password,
                status
            )
            VALUES
            (
                NULL,
                ?,
                ?,
                ?,
                ?,
                1
            )
        ");

        return $stmt->execute([
            $data['role_id'],
            $data['fullname'],
            $data['email'],
            $data['password']
        ]);
    }
    
    public function update(array $data): bool
{
    $stmt = $this->db->prepare("
        UPDATE users
        SET
            fullname = ?,
            email = ?,
            role_id = ?,
            status = ?
        WHERE id = ?
    ");

    return $stmt->execute([
        $data['fullname'],
        $data['email'],
        $data['role_id'],
        $data['status'],
        $data['id']
    ]);
}
    
public function countSuperusers(): int
{
    $stmt = $this->db->query("
        SELECT COUNT(*)
        FROM users
        WHERE role_id = 1
    ");

    return (int)$stmt->fetchColumn();
}
 
public function deleteUser(int $id): bool
{
    $stmt = $this->db->prepare("
        DELETE FROM users
        WHERE id = ?
    ");

    return $stmt->execute([$id]);
}  
    
}