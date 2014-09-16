<?php

namespace model;

class SessionModel extends \Model{
	
	private $strSessionKey = 'login';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function CreateLoginSession(){
		$_SESSION[$this->strSessionKey] = true;
	}
	
	public function LoginSessionExists(){
		return isset($_SESSION[$this->strSessionKey] && $_SESSION[$this->strSessionKey] === true) ? true : false;
	}
	/*
	private $userDAL;
	public function __construct(){
		$this->userDAL = new \model\UserDAL();
		parent::__construct();
	}

	public function GetUserByToken($strToken){
		$arrUser = $this->userDAL->GetUserByToken($strToken);
		return ($arrUser === false) ? null : new \model\user($arrUser);
	}

	public function GetUserById($intId){
		$arrUser = $this->userDAL->GetUserById($intId);
		return ($arrUser === false) ? null : new \model\user($arrUser);
	}

	public function GetUserByUserName($strUserName){
		$arrUser = $this->userDAL->GetUserByUserName($strUserName);
		return ($arrUser === false) ? null : new \model\User($arrUser);
	}

	public function SaveUser($user){
		return ($this->userDAL->SaveUser($user)) ? $user : null;
	}
	
	public function ScramblePassword($strPassword){
		//Will make more complex if there is time.
		$salt = 'asd123';
		return sha1($salt . $strPassword);
	}
	
	public function Auth($user, $strPassword){
		$p1 = $this->ScramblePassword($strPassword);
		$p2 = $user->GetPassword();
		return ($p1 === $p2) ? true : false;
	}

	public function GenerateToken(){
		return sha1(uniqid(rand(), true));
	}

	public function GenerateIdintifier($strAgent, $strIp){
		return sha1($strAgent . $strIp);
	}
	*/
}
?>