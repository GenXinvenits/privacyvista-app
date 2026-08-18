<?php
namespace App\Controllers;
use App\Core\BaseController;
use App\Core\Security;
use App\Models\Assessment;
use App\Models\Client;
class AssessmentsController extends BaseController
{
    private function client(): ?array { $id=(int)($_GET['client_id']??$_POST['client_id']??0); return $id?(new Client())->find($id):null; }
    public function index(): void { $this->requireLogin(); $c=$this->client(); if(!$c){http_response_code(404);exit('Client not found');} $assessments=(new Assessment())->forClient((int)$c['id']); $this->view('assessments/index',compact('client','assessments')); }
    public function create(): void { $this->requireLogin(); $c=$this->client(); if(!$c){http_response_code(404);exit('Client not found');} $this->view('assessments/form',['client'=>$c,'assessment'=>null,'title'=>'New privacy assessment']); }
    public function store(): void { $this->requireLogin(); if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');} $c=$this->client(); if(!$c||trim($_POST['name']??'')===''){http_response_code(422);exit('Assessment name is required');} $_POST['client_id']=$c['id']; $_POST['owner_user_id']=$_SESSION['user']['id']??0; (new Assessment())->create($_POST); redirect('assessments?client_id='.$c['id']); }
    public function edit(): void { $this->requireLogin(); $c=$this->client(); $a=(new Assessment())->find((int)($_GET['id']??0)); if(!$c||!$a||(int)$a['client_id']!==(int)$c['id']){http_response_code(404);exit('Assessment not found');} $this->view('assessments/form',['client'=>$c,'assessment'=>$a,'title'=>'Edit privacy assessment']); }
    public function update(): void { $this->requireLogin(); if(!Security::verifyCsrf($_POST['_csrf']??null)){http_response_code(419);exit('Invalid security token');} $c=$this->client(); if(!$c){http_response_code(404);exit('Client not found');} $_POST['client_id']=$c['id']; $_POST['owner_user_id']=$_SESSION['user']['id']??0; (new Assessment())->update($_POST); redirect('assessments?client_id='.$c['id']); }
}
