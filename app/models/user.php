<?php

namespace model;

class User{
	private $intId;
	private $strUsername;
	private $strPassword;
	private $strToken;
	private $strIdentifier;

	public function __construct($arrUser){
		$this->intId = $arrUser['id'];
		$this->strUsername = $arrUser['username'];
		$this->strPassword = $arrUser['password'];
		$this->strToken = $arrUser['token'];
		$this->strIdentifier = $arrUser['identifier'];
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

	public function GetIdentifier(){
		return $this->strIdentifier;
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

	public function SetIdentifier($s){
		$this->strIdentifier = '' . $s;
	}
}
?>