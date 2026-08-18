<?php
namespace App\Models;
use App\Core\Model;
class User extends Model
{
 protected string $table='users';
 public function getAllUsers(?int $clientId=null):array{$sql='SELECT u.id,u.client_id,u.fullname,u.email,r.name AS role,u.status FROM users u JOIN roles r ON r.id=u.role_id';if($clientId!==null){$s=$this->db->prepare($sql.' WHERE u.client_id=? OR u.role_id=1 ORDER BY u.id DESC');$s->execute([$clientId]);return$s->fetchAll();}return$this->db->query($sql.' ORDER BY u.id DESC')->fetchAll();}
 public function create(array $data):bool{$s=$this->db->prepare('INSERT INTO users (client_id,role_id,fullname,email,password,status) VALUES (?,?,?,?,?,1)');return$s->execute([$data['client_id']??null,$data['role_id'],$data['fullname'],$data['email'],$data['password']]);}
 public function update(array $data):bool{$s=$this->db->prepare('UPDATE users SET client_id=?,fullname=?,email=?,role_id=?,status=? WHERE id=?');return$s->execute([$data['client_id']??null,$data['fullname'],$data['email'],$data['role_id'],$data['status'],$data['id']]);}
 public function countSuperusers():int{return(int)$this->db->query('SELECT COUNT(*) FROM users WHERE role_id=1')->fetchColumn();}
 public function deleteUser(int $id):bool{$s=$this->db->prepare('DELETE FROM users WHERE id=?');return$s->execute([$id]);}
}
