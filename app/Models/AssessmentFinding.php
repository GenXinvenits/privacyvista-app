<?php
namespace App\Models;
use App\Core\Model;
class AssessmentFinding extends Model
{
 public function forAssessment(int $id):array{$s=$this->db->prepare('SELECT * FROM assessment_findings WHERE assessment_id=? ORDER BY FIELD(severity,"critical","high","medium","low"),id DESC');$s->execute([$id]);return $s->fetchAll();}
 public function create(array $d):int{$s=$this->db->prepare('INSERT INTO assessment_findings(assessment_id,title,description,severity,likelihood,impact,status,recommendation) VALUES(?,?,?,?,?,?,?,?)');$s->execute([(int)$d['assessment_id'],trim($d['title']),trim($d['description']??''),$d['severity'],max(1,min(5,(int)$d['likelihood'])),max(1,min(5,(int)$d['impact'])),$d['status']??'open',trim($d['recommendation']??'')]);return(int)$this->db->lastInsertId();}
 public function updateStatus(int $id,string $status):bool{$s=$this->db->prepare('UPDATE assessment_findings SET status=? WHERE id=?');return$s->execute([$status,$id]);}
}
