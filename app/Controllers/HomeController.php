<?php
namespace App\Controllers;
use App\Core\BaseController;

class HomeController extends BaseController {
 public function index(){if($this->isLoggedIn())redirect('dashboard');redirect('login');}
 public function dashboard(){
  $this->requireLogin();
  $db=\App\Core\Database::connect();
  $clientId=$this->clientId();

  if($this->isSuperuser()){
   $stats=[
    'users'=>(int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'clients'=>(int)$db->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
    'activities'=>(int)$db->query('SELECT COUNT(*) FROM processing_activities')->fetchColumn(),
    'assessments'=>(int)$db->query('SELECT COUNT(*) FROM privacy_assessments')->fetchColumn(),
    'open_findings'=>(int)$db->query("SELECT COUNT(*) FROM assessment_findings WHERE status IN ('open','accepted')")->fetchColumn(),
    'open_tasks'=>(int)$db->query("SELECT COUNT(*) FROM privacy_tasks WHERE status NOT IN ('completed','cancelled')")->fetchColumn()
   ];
   $recentClients=$db->query('SELECT * FROM clients ORDER BY id DESC LIMIT 5')->fetchAll();
  } else {
   $stats=[
    'users'=>(int)$this->count($db,'SELECT COUNT(*) FROM users WHERE client_id=?',[$clientId]),
    'clients'=>$clientId ? 1 : 0,
    'activities'=>(int)$this->count($db,'SELECT COUNT(*) FROM processing_activities WHERE client_id=?',[$clientId]),
    'assessments'=>(int)$this->count($db,'SELECT COUNT(*) FROM privacy_assessments WHERE client_id=?',[$clientId]),
    'open_findings'=>(int)$this->count($db,"SELECT COUNT(*) FROM assessment_findings f INNER JOIN privacy_assessments a ON a.id=f.assessment_id WHERE a.client_id=? AND f.status IN ('open','accepted')",[$clientId]),
    'open_tasks'=>(int)$this->count($db,"SELECT COUNT(*) FROM privacy_tasks WHERE client_id=? AND status NOT IN ('completed','cancelled')",[$clientId])
   ];
   $recentClients=[];
   if($clientId){
    $s=$db->prepare('SELECT * FROM clients WHERE id=?');$s->execute([$clientId]);$recentClients=$s->fetchAll();
   }
  }

  $defaultClientId=!empty($recentClients)?(int)$recentClients[0]['id']:($clientId ?? 0);
  $this->view('dashboard/index',['title'=>'Dashboard','user'=>$_SESSION['user'],'stats'=>$stats,'recentClients'=>$recentClients,'defaultClientId'=>$defaultClientId]);
 }
 private function count($db,string $sql,array $params):int{$s=$db->prepare($sql);$s->execute($params);return(int)$s->fetchColumn();}
}
