<?php

namespace controller;

class SessionController extends \Controller{
	
	public function __construct(){
		parent::__construct();
		$this->strDefaultAction = 'NewSession';
	}
	
	/*
	*	If the user isn't signed in already a form will be renderd.
	*/
	public function NewSession(){
		if($this->helper->IsSignedIn()){
			$this->RedirectTo('Session', 'Success');
		}
		
		return $this->view->NewSession();
	}
	
	public function CreateSession(){
		//Check that the form was posted
		if($this->view->CheckSignInTry()){
			//Get all necessary variables from sign in form
			$strUserName = $this->view->GetSignInUserName();
			$strPassword = $this->view->GetSignInPassword();
			$boolRemeber = $this->view->GetKeepMeLoggedIn();
			
			//Make sure user provided username and password
			if($strUserName === '' || $strPassword === ''){
				$this->view->AddFlash('Must provide Username and password.', 'error');
				$this->RedirectTo('Session');
			}
			
			//Ask model to find a user based on the form data
			$arrUser = $this->model->GetUserByUserName($strUserName);
			
			//If a user was found it's also needs to be authenticated
			if($arrUser !== null && $this->model->Auth($arrUser, $strPassword)){
				//Session helper sets necessary sessions, cookies etc that defines "singed in"
				$this->helper->SignIn($arrUser, $boolRemeber);
				
				//After Sign in, let user know it was signed in and Redirect to desired page(controller#action);
				$this->view->AddFlash('Login Successful!', 'success');
				$this->RedirectTo('Session', 'Success');
			}
			else{
				//Could not Auth user, this means that either username or password was faulty. Redirect back to Sign in form
				$this->view->AddFlash('Username or password incorrect.', 'error');
				$this->RedirectTo('Session');
			}
		}
		//if the login-form was not posted, url was changed manually so redirect to Sign in form
		$this->RedirectTo('Session');
	}
	
	public function DestroySession(){
		$this->helper->SignOut();
		$this->view->AddFlash('Sign out Successful!', 'success');
		$this->RedirectTo('Session');
	}
	
	public function Success(){
		return $this->view->Success();
	}
}
?>