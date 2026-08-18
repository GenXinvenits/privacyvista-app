<?php
namespace App\Controllers;
use App\Core\BaseController;
use App\Models\Client;
class FormsController extends BaseController
{
 private function forms():array{return[['name'=>'Data Subject Access Request','description'=>'Capture and manage requests from data subjects to access their personal data.','category'=>'Data Subject Rights','status'=>'Available'],['name'=>'Consent Record','description'=>'Record consent collection, purpose, source and withdrawal information.','category'=>'Consent Management','status'=>'Available'],['name'=>'Privacy Incident Report','description'=>'Capture the initial details of a suspected privacy or personal-data incident.','category'=>'Incident Management','status'=>'Available'],['name'=>'Data Processing Review','description'=>'Structured review form for documenting a processing activity and its privacy controls.','category'=>'Processing Activities','status'=>'Available'],['name'=>'Privacy Risk Assessment','description'=>'Collect information required to assess privacy risks associated with a project or process.','category'=>'Assessments','status'=>'Available'],['name'=>'Vendor Privacy Questionnaire','description'=>'Gather privacy and data-protection information from third-party vendors.','category'=>'Third Parties','status'=>'Available']];}
 public function index():void{$this->requireLogin();$this->view('forms/index',['title'=>'Forms','forms'=>$this->forms()]);}
 public function riskAssessment():void{$this->requireLogin();$model=new Client();if($this->clientId()){$client=$model->find($this->clientId());$clients=$client?[$client]:[];}else{$clients=$model->allClients();}$this->view('forms/privacy-risk-assessment',['title'=>'Privacy Risk Assessment','clients'=>$clients]);}
}
