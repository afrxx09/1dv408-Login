<?php

namespace model;

class SessionModel extends \Model{
	
	private $strSessionKey = 'login';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function createLoginSession(){
		$_SESSION[$this->strSessionKey] = true;
	}
	
	public function loginSessionExists(){
		return (isset($_SESSION[$this->strSessionKey]) && $_SESSION[$this->strSessionKey] === true) ? true : false;
	}

	public function destroyLoginSession(){
		unset($_SESSION[$this->strSessionKey]);
	}

}
?>