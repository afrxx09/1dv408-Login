<?php

namespace model;

class UserModel extends \Model{
	
	private $userDAL;
	
	public function __construct(){
		$this->userDAL = new \model\UserDAL();
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
	
	public function saveUser($arrUser){
		return ($this->userDAL->SaveUser($arrUser)) ? $arrUser : null;
	}
	
	public function auth($arrUser, $strPassword){
		return ($arrUser['password'] === $this->ScramblePassword($strPassword)) ? true : false;
	}
	
	public function checkAgent($arrUser){
		return ($arrUser['agent'] === $_SERVER['HTTP_USER_AGENT']) ? true : false;
	}
	
	public function checkIp($arrUser){
		return ($arrUser['ip'] === $_SERVER['REMOTE_ADDR']) ? true : false;
	}
	
	public function updateSignInData($arrUser, $boolAddCookieTimeStamp){
		$arrUser['token'] = $this->GenerateToken();
		$arrUser['ip'] = $_SERVER['REMOTE_ADDR'];
		$arrUser['agent'] = $_SERVER['HTTP_USER_AGENT'];
		if($boolAddCookieTimeStamp){
			$arrUser['cookietime'] = time();
		}
		if($this->SaveUser($arrUser)){
			return $arrUser;
		}
		return false;
	}
	
	public function generateToken(){
		return sha1(uniqid(rand(), true));
	}
	
	public function generateIdentifier(){
		return sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
	}

	public function generateCookieContent($arrUser){
		$strIdentifier = $this->generateIdentifier();
		$strCookieValue = $arrUser['token'] . ':' . $strIdentifier;
		return $strCookieValue;
	}
	
	public function scramblePassword($strPassword){
		//Will make more complex if there is time.
		$salt = 'asd123';
		return sha1($salt . $strPassword);
	}
	
	public function getLoginTime($arrUser){
		return intval($arrUser['logintime']);
	}
	
	public function getToken($arrUser){
		return $arrUser['token'];
	}

	public function getCookieTime($arrUser){
		return intval($arrUser['cookietime']);
	}
}
?>