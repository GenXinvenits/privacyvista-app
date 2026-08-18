<?php
namespace App\Controllers;
use App\Core\BaseController; use App\Core\Database; use App\Core\Security; use App\Models\Client; use App\Models\PrivacyTask;
class TasksController extends BaseController{
 private function client():?array{$id=(int)($_GET['client_id']??$_POST['client_id']??0);return$id?(new Client())->find($id):null;}
 public function index():void{$this->requireLogin();$client=$this->client();if(!$client){http_response_code(404);exit('Client not found');}$tasks=(new PrivacyTask())->forClient((int)$client['id']);$this->view('tasks/index',compact('client','tasks'));}
 public function create():void{$this->requireLogin();$client=$this->client();if(!$client){http_response_code(404);exit('Client not found');}$db=Database::connect();$ass=$db->prepare('SELECT id,title FROM privacy_assessments WHERE client_id=? ORDER BY created_at DESC');$ass->execute([$client['id']]);$users=$db->query('SELECT id,fullname FROM users ORDER BY fullname')->fetchAll();$this->view('tasks/form',['client'=>$client,'assessments'=>$ass->fetchAll(),'users'=>$users]);}
 public function store():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$client=$this->client();if(!$client||trim($_POST['title']??'')===''){http_response_code(422);exit('Task title is required');}$_POST['client_id']=$client['id'];(new PrivacyTask())->create($_POST);redirect('tasks?client_id='.$client['id']);}
 public function status():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$client=$this->client();if($client){$status=$_POST['status']??'open';if(!in_array($status,['open','in_progress','blocked','completed','cancelled'],true))$status='open';(new PrivacyTask())->updateStatus((int)$_POST['id'],(int)$client['id'],$status);}redirect('tasks?client_id='.(int)($client['id']??0));}
}
