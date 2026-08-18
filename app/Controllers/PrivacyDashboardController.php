<?php
namespace App\Controllers;
use App\Core\BaseController; use App\Models\Client; use App\Models\PrivacyDashboard;
class PrivacyDashboardController extends BaseController
{
 public function show():void{$this->requireLogin();$id=(int)($_GET['client_id']??0);$client=(new Client())->find($id);if(!$client){http_response_code(404);exit('Client not found');}$metrics=(new PrivacyDashboard())->forClient($id);$this->view('privacy/dashboard',compact('client','metrics'));}
}
