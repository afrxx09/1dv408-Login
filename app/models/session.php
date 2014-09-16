<?php

namespace model;

class SessionModel extends \Model{
	
	private $strSessionKey = 'login';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function createLoginSession($strToken){
		$_SESSION[$this->strSessionKey] = $strToken;
	}
	
	public function loginSessionExists(){
		return (isset($_SESSION[$this->strSessionKey])) ? true : false;
	}
	
	public function getSessionToken(){
		return $_SESSION[$this->strSessionKey];
	}
	
	public function destroyLoginSession(){
		unset($_SESSION[$this->strSessionKey]);
	}

}
?>