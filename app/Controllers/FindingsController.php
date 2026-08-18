<?php
namespace App\Controllers;
use App\Core\BaseController;
use App\Core\Database;
use App\Core\Security;
use App\Models\Assessment;
use App\Models\AssessmentFinding;
use App\Models\Client;
class FindingsController extends BaseController
{
 private function selectedClient():?array{
  $id=(int)($_GET['client_id']??$_POST['client_id']??0);
  if($id<=0&&$this->clientId())$id=$this->clientId();
  if($id<=0)return null;
  $client=(new Client())->find($id);
  if($client)$this->requireClientAccess((int)$client['id']);
  return$client;
 }
 private function selectableClients():array{
  $db=Database::connect();
  if($this->clientId()){
   $s=$db->prepare('SELECT * FROM clients WHERE id=?');$s->execute([$this->clientId()]);return$s->fetchAll()?:[];
  }
  return$db->query('SELECT * FROM clients ORDER BY company_name')->fetchAll();
 }
 private function selectedAssessment(?array $client):?array{
  if(!$client)return null;
  $id=(int)($_GET['assessment_id']??$_POST['assessment_id']??0);
  if($id<=0)return null;
  $assessment=(new Assessment())->find($id);
  if(!$assessment||(int)$assessment['client_id']!==(int)$client['id'])return null;
  $this->requireClientAccess((int)$client['id']);
  return$assessment;
 }
 public function index():void{
  $this->requireLogin();
  $client=$this->selectedClient();
  $clients=$this->selectableClients();
  $assessments=$client?(new Assessment())->forClient((int)$client['id']):[];
  $assessment=$this->selectedAssessment($client);
  $findings=$assessment?(new AssessmentFinding())->forAssessment((int)$assessment['id']):[];
  $this->view('findings/index',compact('client','clients','assessments','assessment','findings'));
 }
 public function create():void{$this->requireLogin();$client=$this->selectedClient();$assessment=$this->selectedAssessment($client);if(!$assessment){redirect('findings',['client_id'=>(int)($client['id']??0)]);return;}$this->view('findings/form',['assessment'=>$assessment,'client'=>$client,'finding'=>null]);}
 public function store():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$client=$this->selectedClient();$assessment=$this->selectedAssessment($client);if(!$assessment||trim($_POST['title']??'')===''){http_response_code(422);exit('Finding title is required');}$_POST['assessment_id']=$assessment['id'];(new AssessmentFinding())->create($_POST);redirect('findings',['client_id'=>(int)$client['id'],'assessment_id'=>(int)$assessment['id']]);}
 public function status():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$client=$this->selectedClient();$assessment=$this->selectedAssessment($client);if($assessment){$status=$_POST['status']??'open';if(in_array($status,['open','accepted','mitigated','closed'],true))(new AssessmentFinding())->updateStatus((int)$_POST['id'],$status);}redirect('findings',['client_id'=>(int)($client['id']??0),'assessment_id'=>(int)($assessment['id']??0)]);}
}
