<?php
namespace App\Models;
use App\Core\Model;
class Assessment extends Model
{
    public function forClient(int $clientId): array { $s=$this->db->prepare('SELECT a.*,u.name owner_name FROM assessments a LEFT JOIN users u ON u.id=a.owner_user_id WHERE a.client_id=? ORDER BY a.created_at DESC'); $s->execute([$clientId]); return $s->fetchAll(); }
    public function find(int $id): ?array { $s=$this->db->prepare('SELECT * FROM assessments WHERE id=? LIMIT 1'); $s->execute([$id]); return $s->fetch() ?: null; }
    public function create(array $d): int { $s=$this->db->prepare('INSERT INTO assessments (client_id,name,scope,methodology,status,risk_score,owner_user_id) VALUES (?,?,?,?,?,?,?)'); $s->execute([(int)$d['client_id'],trim($d['name']),trim($d['scope']??''),trim($d['methodology']??''),in_array($d['status']??'', ['draft','in_progress','completed','approved'],true)?$d['status']:'draft',(int)($d['risk_score']??0),(int)($d['owner_user_id']??0)]); return (int)$this->db->lastInsertId(); }
    public function update(array $d): bool { $s=$this->db->prepare('UPDATE assessments SET name=?,scope=?,methodology=?,status=?,risk_score=?,owner_user_id=? WHERE id=? AND client_id=?'); return $s->execute([trim($d['name']),trim($d['scope']??''),trim($d['methodology']??''),$d['status'],(int)($d['risk_score']??0),(int)($d['owner_user_id']??0),(int)$d['id'],(int)$d['client_id']]); }
}
