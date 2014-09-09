<?php

namespace controller;

class SessionController extends \Controller{
	
	public function __construct(){
		parent::__construct();
		$this->strDefaultAction = 'NewSession';
	}
	
	public function SignIn(){
		return $this->NewSession();
	}
	public function NewSession(){
		
		if($this->helper->IsSignedIn()){
			return $this->view->Success();
		}

		if($this->view->CheckSignInTry()){
			$strUserName = $this->view->GetSignInUserName();
			$strPassword = $this->view->GetSignInPassword();
			$boolRemeber = $this->view->GetKeepMeLoggedIn();
			
			if($strUserName === ''){
				$this->view->AddFlash('Missing username!', 'error');
				return $this->view->NewSession();
			}
			
			$arrUser = $this->model->GetUserByUserName($strUserName);

			if($arrUser !== null && $this->model->Auth($arrUser, $strPassword)){
				$this->helper->SignIn($arrUser, $boolRemeber);

				$this->view->AddFlash('Login Successful!', 'success');
				return $this->view->Success();
			}
			else{
				$this->view->AddFlash('Username or password incorrect.', 'error');
				return $this->view->NewSession();
			}
			
		}
		else{
			return $this->view->NewSession();
		}
		
		
	}
	public function SignOut(){
		return $this->DestroySession();
	}
	public function DestroySession(){
		$this->helper->SignOut();
		$this->view->AddFlash('Sign out Successful!', 'success');
		return $this->view->NewSession();
	}
	
	public function Success(){
		return $this->view->Success();
	}
}
?>