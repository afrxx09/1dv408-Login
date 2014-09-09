<?php

namespace helper;

class SessionHelper Extends \Helper{

	private $strCookieName = 'user';
	
	public function SignIn($arrUser, $boolRemeber){
		if($boolRemeber){
			$arrUser['token'] = $this->model->GenerateToken();
			$arrUser['identifier'] = $this->model->GenerateIdintifier($arrUser);
			$this->CreateAuthCookie($arrUser);
		}
		$_SESSION['signed_in'] = true;
	}
	
	public function IsSignedIn(){
		return isset($_SESSION['signed_in']);
	}
	public function UserHasCookie(){
		return isset($_COOKIE[$this->strCookieName]);
	}

	private function CreateAuthCookie($arrUser){
		$strCookieValue = $arrUser['identifier'] . ':' . $arrUser['token'];
		setcookie($this->strCookieName, $strCookieValue, time()+60*60*24*30, '/');
	}

	private function DeleteAuthCookie(){
		unset($_COOKIE[$this->strCookieName]);
		setcookie($this->strCookieName, '', time()-3600, '/');
	}

	public function SignOut(){
		$this->DeleteAuthCookie();
		session_destroy();
	}
}

?>