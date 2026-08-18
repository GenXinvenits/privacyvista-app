<?php
namespace App\Models;
use App\Core\Model;
class PrivacyTask extends Model
{
 public function forClient(int $id):array{$s=$this->db->prepare('SELECT t.*,u.name assignee_name FROM privacy_tasks t LEFT JOIN users u ON u.id=t.assigned_to WHERE t.client_id=? ORDER BY t.due_date IS NULL,t.due_date,t.id DESC');$s->execute([$id]);return$s->fetchAll();}
 public function create(array $d):int{$s=$this->db->prepare('INSERT INTO privacy_tasks(client_id,assessment_id,finding_id,title,description,priority,status,assigned_to,due_date) VALUES(?,?,?,?,?,?,?,?,?)');$s->execute([(int)$d['client_id'],($d['assessment_id']??null)?(int)$d['assessment_id']:null,($d['finding_id']??null)?(int)$d['finding_id']:null,trim($d['title']),trim($d['description']??''),$d['priority']??'medium',$d['status']??'open',($d['assigned_to']??null)?(int)$d['assigned_to']:null,$d['due_date']??null]);return(int)$this->db->lastInsertId();}
 public function updateStatus(int $id,int $clientId,string $status):bool{$completed=$status==='completed'?', completed_at=NOW()':', completed_at=NULL';$s=$this->db->prepare("UPDATE privacy_tasks SET status=? $completed WHERE id=? AND client_id=?");return$s->execute([$status,$id,$clientId]);}
}
