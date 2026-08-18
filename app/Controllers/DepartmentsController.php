<?php

namespace App\Controllers;
use App\Core\BaseController;
use App\Core\Flash;
use App\Models\Client;
use App\Models\Department;

class DepartmentsController extends BaseController
{
    private function clientIdFromRequest(): int
    {
        $id=(int)($_GET['client_id']??$_POST['client_id']??0);
        if($id<=0&&$this->clientId())$id=$this->clientId();
        $this->requireClientAccess($id);
        return$id;
    }

    public function index(){
        $this->requireLogin(); $clientId=$this->clientIdFromRequest(); $client=(new Client())->find($clientId);
        if(!$client){Flash::error('Client not found.');redirect('clients');}
        $departments=(new Department())->allByClient($clientId);
        $this->view('departments/index',['title'=>'Departments','client'=>$client,'departments'=>$departments]);
    }

    public function create(){
        $this->requireLogin();$clientId=$this->clientIdFromRequest();$client=(new Client())->find($clientId);
        if(!$client){Flash::error('Client not found.');redirect('clients');}
        $this->view('departments/create',['title'=>'Add Department','client'=>$client]);
    }

    public function store(){
        $this->requireLogin();$clientId=$this->clientIdFromRequest();$model=new Department();
        $model->create(['client_id'=>$clientId,'name'=>trim($_POST['name']??''),'description'=>trim($_POST['description']??'')]);
        Flash::success('Department created successfully.');redirect('departments?client_id='.$clientId);
    }
}
