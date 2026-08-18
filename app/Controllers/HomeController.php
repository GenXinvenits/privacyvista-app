<?php
namespace App\Controllers;
use App\Core\BaseController;
class HomeController extends BaseController {
 public function index(){if($this->isLoggedIn())redirect('dashboard');redirect('login');}
 public function dashboard(){
  $this->requireLogin(); $db=\App\Core\Database::connect();
  $stats=['users'=>(int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn(),'clients'=>(int)$db->query('SELECT COUNT(*) FROM clients')->fetchColumn(),'activities'=>(int)$db->query('SELECT COUNT(*) FROM processing_activities')->fetchColumn(),'assessments'=>(int)$db->query('SELECT COUNT(*) FROM assessments')->fetchColumn(),'open_findings'=>(int)$db->query("SELECT COUNT(*) FROM assessment_findings WHERE status IN ('open','accepted')")->fetchColumn(),'open_tasks'=>(int)$db->query("SELECT COUNT(*) FROM privacy_tasks WHERE status NOT IN ('completed','cancelled')")->fetchColumn()];
  $recentClients=$db->query('SELECT * FROM clients ORDER BY id DESC LIMIT 5')->fetchAll();
  $this->view('dashboard/index',['title'=>'Dashboard','user'=>$_SESSION['user'],'stats'=>$stats,'recentClients'=>$recentClients]);
 }
}
