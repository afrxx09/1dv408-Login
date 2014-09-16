<?php

namespace model;

class UserModel extends \Model{
	
	private $userDAL;
	
	public function __construct(){
		$this->userDAL = new \model\UserDAL();
	}
	
	public function GetUserByToken($strToken){
		return $this->userDAL->GetUserByToken($strToken);
		//return ($arrUser === false) ? null : new \model\user($arrUser);
	}

	public function GetUserById($intId){
		return $this->userDAL->GetUserById($intId);
		//return ($arrUser === false) ? null : new \model\user($arrUser);
	}

	public function GetUserByUserName($strUserName){
		return $this->userDAL->GetUserByUserName($strUserName);
		//return ($arrUser === false) ? null : new \model\User($arrUser);
	}
	
	public function SaveUser($arrUser){
		return ($this->userDAL->SaveUser($arrUser)) ? $arrUser : null;
	}
	
	public function Auth($arrUser, $strPassword){
		$p1 = $this->ScramblePassword($strPassword);
		$p2 = $arrUser['password']->GetPassword();
		return ($p1 === $p2) ? true : false;
	}
	
	public function prepareUserDataForCookie($arrUser){
		$arrUser['token'] = $this->GenerateToken();
		$arrUser['ip'] = $_SERVER['REMOTE_ADDR'];
		$arrUser['agent'] = $_SERVER['HTTP_USER_AGENT'];
		$arrUser['logintime'] = time();
		return $this->SaveUser($arrUser);
	}
	
	public function GenerateToken(){
		return sha1(uniqid(rand(), true));
	}
	
	public function generateCookieContent($arrUser){
		$strIdentifier = sha1($arrUser['agent'] . $arrUser['ip']);
		$strCookieValue = $arrUser['token'] . ':' . $strIdentifier . ':' . $arrUser['logintime'];
	}
	
	public function ScramblePassword($strPassword){
		//Will make more complex if there is time.
		$salt = 'asd123';
		return sha1($salt . $strPassword);
	}
	
	public function GetLoginTime($arrUser){
		return intval($arrUser['logintime']);
	}
	/*
	private $intId;
	private $strUsername;
	private $strPassword;
	private $strToken;
	private $strIp;
	private $strAgent;
	private $strLoginTime;
	
	public function __construct($arrUser){
		$this->intId = $arrUser['id'];
		$this->strUsername = $arrUser['username'];
		$this->strPassword = $arrUser['password'];
		$this->strToken = $arrUser['token'];
		$this->strIp = $arrUser['ip'];
		$this->strAgent = $arrUser['agent'];
		$this->strLoginTime = $arrUser['logintime'];
	}
	
	//Getters
	public function GetId(){
		return $this->intId;
	}

	public function GetUsername(){
		return $this->strUsername;
	}

	public function GetPassword(){
		return $this->strPassword;
	}

	public function GetToken(){
		return $this->strToken;
	}

	public function GetIp(){
		return $this->strIp;
	}
	
	public function GetAgent(){
		return $this->strAgent;
	}
	
	public function GetLoginTime(){
		return $this->strLoginTime;
	}

	//Setters
	public function SetUsername($s){
		$this->strUsername = '' . $s;
	}

	public function SetPassword($s){
		$this->strPassword = '' . $s;
	}

	public function SetToken($s){
		$this->strToken = '' . $s;
	}

	public function SetIp($s){
		$this->strIp = '' . $s;
	}
	
	public function SetAgent($s){
		$this->strAgent = '' . $s;
	}
	
	public function SetLoginTime($s){
		$this->strLoginTime = '' . $s;
	}
	*/
}
?>