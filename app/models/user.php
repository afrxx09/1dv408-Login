<?php

namespace model;

class User{
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
	
	/*Getters*/
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

	/*Setters*/
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
}
?>