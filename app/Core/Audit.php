<?php
namespace App\Core;
class Audit{
 public static function log(string $action,?string $entityType=null,?int $entityId=null,?int $clientId=null,array $details=[]):void{
  try{$db=Database::connect();$s=$db->prepare('INSERT INTO privacy_audit_log(user_id,client_id,action,entity_type,entity_id,details,ip_address) VALUES(?,?,?,?,?,?,?)');$s->execute([$_SESSION['user']['id']??null,$clientId,$action,$entityType,$entityId,$details?json_encode($details,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES):null,$_SERVER['REMOTE_ADDR']??null]);}catch(\Throwable $e){}
 }
}
