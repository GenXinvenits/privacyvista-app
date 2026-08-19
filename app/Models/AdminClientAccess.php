<?php
namespace App\Models;
use App\Core\Model;

class AdminClientAccess extends Model
{
    public function clientsForAdmin(int $adminId): array
    {
        $s=$this->db->prepare('SELECT c.* FROM clients c JOIN admin_client_access a ON a.client_id=c.id WHERE a.admin_user_id=? AND c.status=1 ORDER BY c.company_name');
        $s->execute([$adminId]);
        return $s->fetchAll();
    }

    public function canAccess(int $adminId,int $clientId): bool
    {
        $s=$this->db->prepare('SELECT 1 FROM admin_client_access WHERE admin_user_id=? AND client_id=? LIMIT 1');
        $s->execute([$adminId,$clientId]);
        return (bool)$s->fetchColumn();
    }
}
