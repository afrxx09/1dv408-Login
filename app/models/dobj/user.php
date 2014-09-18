<?php

namespace model\dobj;

class User{
	
	private $intId;
	private $strUsername;
	private $strPassword;
	private $strToken;
	private $strIp;
	private $strAgent;
	private $intCookieTime;
	
	public function __construct($arrUser){
		$this->intId = $arrUser['id'];
		
		$this->setUsername($arrUser['username']);
		$this->setPassword($arrUser['password']);
		$this->setToken($arrUser['token']);
		$this->setIp($arrUser['ip']);
		$this->setAgent($arrUser['agent']);
		$this->setCookieTime($arrUser['cookietime']);
	}
	
	//Getters
	public function getId(){
		return $this->intId;
	}
	public function getUsername(){
		return $this->strUsername;
	}
	public function getPassword(){
		return $this->strPassword;
	}
	public function getToken(){
		return $this->strToken;
	}
	public function getIp(){
		return $this->strIp;
	}
	public function getAgent(){
		return $this->strAgent;
	}
	public function getCookieTime(){
		return $this->intCookieTime;
	}
	//Setters
	public function setUsername($s){
		$this->strUsername = '' . $s;
	}
	public function setPassword($s){
		$this->strPassword = '' . $s;	
	}
	public function setToken($s){
		$this->strToken = '' . $s;
	}
	public function setIp($s){
		$this->strIp = '' . $s;
	}
	public function setAgent($s){
		$this->strAgent = '' . $s;
	}
	public function setCookieTime($i){
		$this->intCookieTime = intval($i);
	}
	
	//Methods
	public function auth($strPassword){
		return ($this->getPassword() === $this->ScramblePassword($strPassword)) ? true : false;
	}
	
	//Will make more complex if there is time.
	public function scramblePassword($strPassword){
		$salt = 'asd123';
		return sha1($salt . $strPassword);
	}
	
	public function updateSignInData($strToken, $boolAddCookieTimeStamp){
		$this->setToken($strToken);
		$this->setIp($_SERVER['REMOTE_ADDR']);
		$this->setAgent($_SERVER['HTTP_USER_AGENT']);
		if($boolAddCookieTimeStamp){
			$this->setCookieTime(time());
		}
	}
	
}
?>