<?php

namespace controller;

class Login extends Controller{
	
	public function __construct(){
		parent::__construct();
		$this->strDefaultAction = 'SignIn';
	}
	
	public function SignIn(){

		if($this->helper->UserHasCookie()){
			var_dump($_COOKIE);
			exit;
		}

		if($this->helper->IsUserSignedIn()){
			return $this->view->Success();
		}

		if($this->view->CheckSignInTry()){
			$strUserName = $this->view->GetSignInUserName();
			$strPassword = $this->view->GetSignInPassword();
			if($strUserName === ''){
				$this->view->AddFlash('Missing username!', 'error');
				return $this->view->SignIn();
			}
			
			$arrUser = $this->model->GetUserByUserName($strUserName);

			if($arrUser !== null && $this->model->Auth($arrUser, $strPassword)){
				$boolRemeber = $this->view->GetKeepMeLoggedIn();
				$this->helper->SignInUser($arrUser, $boolRemeber);

				$this->view->AddFlash('Login Successful!', 'success');
				return $this->view->Success();
			}
			else{
				$this->view->AddFlash('Username or password incorrect.', 'error');
				return $this->view->SignIn();
			}
			
		}
		else{
			return $this->view->SignIn();
		}
	}
	
	public function SignOut(){
		$this->helper->SignOut();
		$this->view->AddFlash('Sign out Successful!', 'success');
		return $this->view->SignIn();
	}
}
?>