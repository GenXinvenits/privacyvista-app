<?php
namespace App\Models;
use App\Core\Model;
class PrivacyDashboard extends Model
{
 public function forClient(int $clientId):array
 {
  $q=fn(string $sql)=>$this->db->prepare($sql);
  $s=$q('SELECT COUNT(*) FROM processing_activities WHERE client_id=?');$s->execute([$clientId]);$pa=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM processing_activities WHERE client_id=? AND status="active"');$s->execute([$clientId]);$activePa=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM privacy_assessments WHERE client_id=?');$s->execute([$clientId]);$assessments=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM privacy_assessments WHERE client_id=? AND status IN ("draft","in_progress")');$s->execute([$clientId]);$pendingAssessments=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM privacy_tasks WHERE client_id=? AND status NOT IN ("completed","cancelled")');$s->execute([$clientId]);$openTasks=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM privacy_tasks WHERE client_id=? AND status NOT IN ("completed","cancelled") AND due_date IS NOT NULL AND due_date<CURDATE()');$s->execute([$clientId]);$overdueTasks=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM assessment_findings f JOIN privacy_assessments a ON a.id=f.assessment_id WHERE a.client_id=? AND f.status IN ("open","accepted")');$s->execute([$clientId]);$openFindings=(int)$s->fetchColumn();
  $s=$q('SELECT COUNT(*) FROM assessment_findings f JOIN privacy_assessments a ON a.id=f.assessment_id WHERE a.client_id=? AND f.status IN ("open","accepted") AND f.severity IN ("high","critical")');$s->execute([$clientId]);$highFindings=(int)$s->fetchColumn();
  $s=$q('SELECT COALESCE(AVG(risk_score),0) FROM privacy_assessments WHERE client_id=? AND risk_score IS NOT NULL');$s->execute([$clientId]);$risk=(float)$s->fetchColumn();
  return compact('pa','activePa','assessments','pendingAssessments','openTasks','overdueTasks','openFindings','highFindings','risk');
 }
}
