<?php

namespace model;

class UserModel extends \Model{
	
	private $userDAL;
	
	public function __construct(){
		$this->userDAL = new \model\dal\UserDAL();
	}
	
	public function getUserByToken($strToken){
		return $this->userDAL->GetUserByToken($strToken);
	}

	public function getUserById($intId){
		return $this->userDAL->GetUserById($intId);
	}

	public function getUserByUserName($strUserName){
		return $this->userDAL->GetUserByUserName($strUserName);
	}
	
	public function saveUser($user){
		return ($this->userDAL->SaveUser($user)) ? true : false;
	}
	
	public function checkAgent($user){
		return ($user->getAgent() === $_SERVER['HTTP_USER_AGENT']) ? true : false;
	}
	
	public function checkIp($user){
		return ($user->getIp() === $_SERVER['REMOTE_ADDR']) ? true : false;
	}
	
	public function generateToken(){
		return sha1(uniqid(rand(), true));
	}
	
	public function generateIdentifier(){
		return sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
	}

	public function generateCookieContent($user){
		$strIdentifier = $this->generateIdentifier();
		$strCookieValue = $user->getToken() . ':' . $strIdentifier;
		return $strCookieValue;
	}
}
?>