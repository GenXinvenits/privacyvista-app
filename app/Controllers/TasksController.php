<?php
namespace App\Controllers;
use App\Core\BaseController; use App\Core\Database; use App\Core\Security; use App\Models\Client; use App\Models\PrivacyTask;
class TasksController extends BaseController
{
 private function client():?array{$id=(int)($_GET['client_id']??$_POST['client_id']??0);return$id?(new Client())->find($id):null;}
 public function index():void{$this->requireLogin();$c=$this->client();if(!$c){http_response_code(404);exit('Client not found');}$tasks=(new PrivacyTask())->forClient((int)$c['id']);$this->view('tasks/index',compact('client','tasks'));}
 public function create():void{$this->requireLogin();$c=$this->client();if(!$c){http_response_code(404);exit('Client not found');}$db=Database::connect();$ass=$db->prepare('SELECT id,name FROM assessments WHERE client_id=? ORDER BY created_at DESC');$ass->execute([$c['id']]);$users=$db->query('SELECT id,name FROM users ORDER BY name')->fetchAll();$this->view('tasks/form',['client'=>$c,'assessments'=>$ass->fetchAll(),'users'=>$users]);}
 public function store():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$c=$this->client();if(!$c||trim($_POST['title']??'')===''){http_response_code(422);exit('Task title is required');}$_POST['client_id']=$c['id'];(new PrivacyTask())->create($_POST);redirect('tasks?client_id='.$c['id']);}
 public function status():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$c=$this->client();if($c)(new PrivacyTask())->updateStatus((int)$_POST['id'],(int)$c['id'],$_POST['status']??'open');redirect('tasks?client_id='.(int)($c['id']??0));}
}
