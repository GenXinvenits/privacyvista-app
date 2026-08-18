<?php
namespace App\Models;
use App\Core\Model;
class Assessment extends Model
{
 public function forClient(int $clientId):array{$s=$this->db->prepare('SELECT a.*,u.name owner_name FROM privacy_assessments a LEFT JOIN users u ON u.id=a.created_by WHERE a.client_id=? ORDER BY a.created_at DESC');$s->execute([$clientId]);return$s->fetchAll();}
 public function find(int $id):?array{$s=$this->db->prepare('SELECT * FROM privacy_assessments WHERE id=? LIMIT 1');$s->execute([$id]);return$s->fetch()?:null;}
 public function create(array $d):int{$s=$this->db->prepare('INSERT INTO privacy_assessments(client_id,title,assessment_type,status,risk_score,due_date,created_by) VALUES(?,?,?,?,?,?,?)');$s->execute([(int)$d['client_id'],trim($d['name']),$d['assessment_type']??'privacy_review',in_array($d['status']??'', ['draft','in_progress','completed','approved'],true)?$d['status']:'draft',(int)($d['risk_score']??0),$d['due_date']??null,(int)($d['owner_user_id']??0)]);return(int)$this->db->lastInsertId();}
 public function update(array $d):bool{$s=$this->db->prepare('UPDATE privacy_assessments SET title=?,assessment_type=?,status=?,risk_score=?,due_date=? WHERE id=? AND client_id=?');return$s->execute([trim($d['name']),$d['assessment_type']??'privacy_review',$d['status'],(int)($d['risk_score']??0),$d['due_date']??null,(int)$d['id'],(int)$d['client_id']]);}
}
