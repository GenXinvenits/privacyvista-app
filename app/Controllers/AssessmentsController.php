<?php
namespace App\Controllers;
use App\Core\BaseController; use App\Core\Database; use App\Core\Security; use App\Models\Assessment; use App\Models\Client;
class AssessmentsController extends BaseController
{
 private function client():?array{$id=(int)($_GET['client_id']??$_POST['client_id']??0);return$id?(new Client())->find($id):null;}
 private function resolveClient():?array{$c=$this->client();if($c)return$c;$db=Database::connect();$s=$db->query('SELECT * FROM clients ORDER BY company_name LIMIT 2');$clients=$s->fetchAll();return count($clients)===1?$clients[0]:null;}
 private function formData(int $clientId):array{$db=Database::connect();$s=$db->prepare('SELECT id,name FROM processing_activities WHERE client_id=? ORDER BY name');$s->execute([$clientId]);return$s->fetchAll();}
 public function index():void{$this->requireLogin();$c=$this->resolveClient();if(!$c){http_response_code(400);exit('Please select a client to view assessments');}$assessments=(new Assessment())->forClient((int)$c['id']);$this->view('assessments/index',['client'=>$c,'assessments'=>$assessments]);}
 public function create():void{$this->requireLogin();$c=$this->resolveClient();if(!$c){http_response_code(400);exit('Please select a client to create an assessment');}$this->view('assessments/form',['client'=>$c,'assessment'=>null,'activities'=>$this->formData((int)$c['id']),'title'=>'New privacy assessment']);}
 public function store():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$c=$this->client();if(!$c||trim($_POST['title']??'')===''){http_response_code(422);exit('Assessment title is required');}$_POST['client_id']=$c['id'];$_POST['created_by']=$_SESSION['user']['id']??0;(new Assessment())->create($_POST);redirect('assessments?client_id='.$c['id']);}
 public function edit():void{$this->requireLogin();$c=$this->client();$a=(new Assessment())->find((int)($_GET['id']??0));if(!$c||!$a||(int)$a['client_id']!==(int)$c['id']){http_response_code(404);exit('Assessment not found');}$this->view('assessments/form',['client'=>$c,'assessment'=>$a,'activities'=>$this->formData((int)$c['id']),'title'=>'Edit privacy assessment']);}
 public function update():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$c=$this->client();if(!$c){http_response_code(404);exit('Client not found');}$_POST['client_id']=$c['id'];(new Assessment())->update($_POST);redirect('assessments?client_id='.$c['id']);}
}
