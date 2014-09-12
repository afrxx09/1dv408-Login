<?php

namespace helper;

class SessionHelper Extends \Helper{

	private $strCookieName = 'user';
	
	public function SignIn($user, $boolRemeber = false){
		if($boolRemeber){
			$user->SetToken($this->model->GenerateToken());
			$user->Setidentifier($this->model->GenerateIdintifier($user));
			$user = $this->model->SaveUser($user);
			if($user !== null){
				$this->CreateAuthCookie($user);
				$_SESSION['token'] = $user->GetToken();
			}
		}
		$_SESSION['username'] = $user->GetUserName();
	}
	
	public function IsSignedIn(){
		if(isset($_SESSION['username'])){
			return true;
		}
		if($this->UserHasCookie()){
			$arrCookie = explode(':', $_COOKIE[$this->strCookieName]);
			$token = $arrCookie[0];
			$identifier = $arrCookie[1];
			$user = $this->model->GetUserByToken($token);
			if($user !== null){
				$this->SignIn($user);
				return true;
			}
			return false;
		}
	}
	public function UserHasCookie(){
		return isset($_COOKIE[$this->strCookieName]);
	}

	private function CreateAuthCookie($user){
		$strCookieValue = $user->GetToken() . ':' . $user->GetIdentifier();
		setcookie($this->strCookieName, $strCookieValue, time()+60*60*24*30, '/');
	}

	private function DeleteAuthCookie(){
		unset($_COOKIE[$this->strCookieName]);
		setcookie($this->strCookieName, '', time()-3600, '/');
	}

	public function SignOut(){
		$this->DeleteAuthCookie();
		$_SESSION['token'] = false;
		$_SESSION['username'] = false;
		unset($_SESSION['token']);
		unset($_SESSION['username']);
	}

	public function CurrentUser(){
		return $_SESSION['username'];
	}
}

?>