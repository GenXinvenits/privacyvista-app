<?php
namespace App\Controllers;
use App\Core\BaseController; use App\Core\Security; use App\Models\Assessment; use App\Models\AssessmentFinding; use App\Models\Client;
class FindingsController extends BaseController
{
 private function assessment():?array{$id=(int)($_GET['assessment_id']??$_POST['assessment_id']??0);return$id?(new Assessment())->find($id):null;}
 private function client(int $id):?array{return(new Client())->find($id);}
 public function index():void{$this->requireLogin();$a=$this->assessment();if(!$a){http_response_code(404);exit('Assessment not found');}$client=$this->client((int)$a['client_id']);$findings=(new AssessmentFinding())->forAssessment((int)$a['id']);$this->view('findings/index',compact('assessment','client','findings'));}
 public function create():void{$this->requireLogin();$a=$this->assessment();if(!$a){http_response_code(404);exit('Assessment not found');}$client=$this->client((int)$a['client_id']);$this->view('findings/form',['assessment'=>$a,'client'=>$client,'finding'=>null]);}
 public function store():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$a=$this->assessment();if(!$a||trim($_POST['title']??'')===''){http_response_code(422);exit('Finding title is required');}$_POST['assessment_id']=$a['id'];(new AssessmentFinding())->create($_POST);redirect('findings',['assessment_id'=>(int)$a['id']]);}
 public function status():void{$this->requireLogin();if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');}$a=$this->assessment();if($a){$status=$_POST['status']??'open';if(in_array($status,['open','accepted','mitigated','closed'],true))(new AssessmentFinding())->updateStatus((int)$_POST['id'],$status);}redirect('findings',['assessment_id'=>(int)($a['id']??0)]);}
}
