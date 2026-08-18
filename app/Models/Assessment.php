<?php
namespace App\Models;
use App\Core\Model;
class Assessment extends Model
{
 protected string $table='privacy_assessments';
 public function forClient(int $clientId):array{$s=$this->db->prepare('SELECT a.* FROM privacy_assessments a WHERE a.client_id=? ORDER BY a.created_at DESC');$s->execute([$clientId]);return$s->fetchAll();}
 public function find(int $id):?array{$s=$this->db->prepare('SELECT * FROM privacy_assessments WHERE id=? LIMIT 1');$s->execute([$id]);return$s->fetch()?:null;}
 public function create(array $d):int{$s=$this->db->prepare('INSERT INTO privacy_assessments(client_id,processing_activity_id,title,assessment_type,status,risk_score,findings,recommendations,due_date,created_by) VALUES(?,?,?,?,?,?,?,?,?,?)');$s->execute([(int)$d['client_id'],!empty($d['processing_activity_id'])?(int)$d['processing_activity_id']:null,trim($d['title']),in_array($d['assessment_type']??'', ['privacy_review','dpia','vendor_review','transfer_review','other'],true)?$d['assessment_type']:'privacy_review',in_array($d['status']??'', ['draft','in_progress','completed','approved','archived'],true)?$d['status']:'draft',($d['risk_score']??'')!==''?(float)$d['risk_score']:null,trim($d['findings']??''),trim($d['recommendations']??''),$d['due_date']??null,(int)($d['created_by']??0)]);return(int)$this->db->lastInsertId();}
 public function update(array $d):bool{$s=$this->db->prepare('UPDATE privacy_assessments SET processing_activity_id=?,title=?,assessment_type=?,status=?,risk_score=?,findings=?,recommendations=?,due_date=?,completed_at=CASE WHEN ?="completed" AND completed_at IS NULL THEN NOW() WHEN ?<>"completed" THEN NULL ELSE completed_at END WHERE id=? AND client_id=?');return$s->execute([!empty($d['processing_activity_id'])?(int)$d['processing_activity_id']:null,trim($d['title']),$d['assessment_type'],$d['status'],($d['risk_score']??'')!==''?(float)$d['risk_score']:null,trim($d['findings']??''),trim($d['recommendations']??''),$d['due_date']??null,$d['status'],$d['status'],(int)$d['id'],(int)$d['client_id']]);}
}
