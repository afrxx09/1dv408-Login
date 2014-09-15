<?php

namespace helper;

class SessionHelper Extends \Helper{

	private $strCookieName = 'user';
	
	public function SignIn($user, $boolRemeber = false){
		if($boolRemeber){
			$user->SetToken($this->model->GenerateToken());
			$user->SetIp($_SERVER['REMOTE_ADDR']);
			$user->SetAgent($_SERVER['HTTP_USER_AGENT']);
			$user->SetLoginTime(time());
			$user = $this->model->SaveUser($user);
			if($user !== null){
				$this->CreateAuthCookie($user);
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
			$strToken = $arrCookie[0];
			$strIdentifier = $arrCookie[1];
			$user = $this->model->GetUserByToken($strToken);
			if($user !== null){
				if($this->model->GenerateIdintifier($user) === $strIdentifier){
					$this->SignIn($user);
					return true;
				}
			}
		}
		return false;
	}
	public function UserHasCookie(){
		return isset($_COOKIE[$this->strCookieName]);
	}

	private function CreateAuthCookie($user){
		$strIdentifier = $this->model->GenerateIdintifier($user->GetLoginTime(), $user->GetAgent(), $user->GetIp());
		$strCookieValue = $user->GetToken() . ':' . $strIdentifier;
		setcookie($this->strCookieName, $strCookieValue, time()+60*60*24*30, '/');
	}

	private function DeleteAuthCookie(){
		unset($_COOKIE[$this->strCookieName]);
		setcookie($this->strCookieName, '', time()-3600, '/');
	}

	public function SignOut(){
		$this->DeleteAuthCookie();
		unset($_SESSION['username']);
	}

	public function CurrentUser(){
		return $_SESSION['username'];
	}
}

?>