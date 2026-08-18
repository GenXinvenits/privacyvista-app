<?php
namespace App\Controllers;
use App\Core\BaseController;
use App\Core\Database;
use App\Models\Client;
class ReportsController extends BaseController
{
 private function client():?array{$id=(int)($_GET['client_id']??0);if($id<=0&&$this->clientId())$id=$this->clientId();if($id<=0)return null;$client=(new Client())->find($id);if($client)$this->requireClientAccess($id);return$client;}
 public function index():void{
  $this->requireLogin();$db=Database::connect();$client=$this->client();$clients=$this->isSuperuser()?(new Client())->allClients():($client?[$client]:[]);
  $summary=['activities'=>0,'active_activities'=>0,'assessments'=>0,'completed_assessments'=>0,'findings'=>0,'open_findings'=>0,'tasks'=>0,'open_tasks'=>0,'overdue_tasks'=>0];$recentAssessments=[];$recentFindings=[];$tasks=[];
  if($client){$id=(int)$client['id'];$summary['activities']=$this->count($db,'SELECT COUNT(*) FROM processing_activities WHERE client_id=?',[$id]);$summary['active_activities']=$this->count($db,"SELECT COUNT(*) FROM processing_activities WHERE client_id=? AND status='active'",[$id]);$summary['assessments']=$this->count($db,'SELECT COUNT(*) FROM privacy_assessments WHERE client_id=?',[$id]);$summary['completed_assessments']=$this->count($db,"SELECT COUNT(*) FROM privacy_assessments WHERE client_id=? AND status IN ('completed','approved')",[$id]);$summary['findings']=$this->count($db,'SELECT COUNT(*) FROM assessment_findings f INNER JOIN privacy_assessments a ON a.id=f.assessment_id WHERE a.client_id=?',[$id]);$summary['open_findings']=$this->count($db,"SELECT COUNT(*) FROM assessment_findings f INNER JOIN privacy_assessments a ON a.id=f.assessment_id WHERE a.client_id=? AND f.status IN ('open','accepted')",[$id]);$summary['tasks']=$this->count($db,'SELECT COUNT(*) FROM privacy_tasks WHERE client_id=?',[$id]);$summary['open_tasks']=$this->count($db,"SELECT COUNT(*) FROM privacy_tasks WHERE client_id=? AND status IN ('open','in_progress','blocked')",[$id]);$summary['overdue_tasks']=$this->count($db,"SELECT COUNT(*) FROM privacy_tasks WHERE client_id=? AND due_date IS NOT NULL AND due_date < CURDATE() AND status NOT IN ('completed','cancelled')",[$id]);$s=$db->prepare('SELECT title,assessment_type,status,risk_score,due_date,created_at FROM privacy_assessments WHERE client_id=? ORDER BY created_at DESC LIMIT 8');$s->execute([$id]);$recentAssessments=$s->fetchAll();$s=$db->prepare("SELECT f.title,f.severity,f.status,a.title AS assessment_title FROM assessment_findings f INNER JOIN privacy_assessments a ON a.id=f.assessment_id WHERE a.client_id=? ORDER BY FIELD(f.severity,'critical','high','medium','low'),f.id DESC LIMIT 8");$s->execute([$id]);$recentFindings=$s->fetchAll();$s=$db->prepare('SELECT title,priority,status,due_date,assigned_to FROM privacy_tasks WHERE client_id=? ORDER BY due_date IS NULL,due_date LIMIT 8');$s->execute([$id]);$tasks=$s->fetchAll();}
  $this->view('reports/index',compact('client','clients','summary','recentAssessments','recentFindings','tasks'));
 }
 private function count($db,string $sql,array $params):int{$s=$db->prepare($sql);$s->execute($params);return(int)$s->fetchColumn();}
}
