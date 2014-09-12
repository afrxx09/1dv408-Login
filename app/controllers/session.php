<?php

namespace controller;

class SessionController extends \Controller{
	/*Index is the default function for all controllers, this redirects the default to NewSession instead.*/
	public function Index(){
		return $this->NewSession();
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
				$this->view->AddFlash(\View\SessionView::EmptyUserNamePassword, \View::FlashClassError);
				$this->RedirectTo('Session');
			}
			
			//Ask model to find a user based on the form data
			//$arrUser = $this->model->GetUserByUserName($strUserName);
			$user = $this->model->GetUserByUserName($strUserName);
			//If a user was found it's also needs to be authenticated
			if($user !== null && $this->model->Auth($user, $strPassword)){
				//Session helper sets necessary sessions, cookies etc that defines "singed in"
				$this->helper->SignIn($user, $boolRemeber);
				
				//After Sign in, let user know it was signed in and Redirect to desired page(controller#action);
				$this->view->AddFlash(\View\SessionView::LoginSuccess, \View::FlashClassSuccess);
				$this->RedirectTo('Session', 'Success');
			}
			else{
				//Could not Auth user, this means that either username or password was faulty. Redirect back to Sign in form
				$this->view->AddFlash(\View\SessionView::AuthFail, \View::FlashClassError);
				$this->RedirectTo('Session');
			}
		}
		//if the login-form was not posted, url was changed manually so redirect to Sign in form
		$this->RedirectTo('Session');
	}
	
	public function DestroySession(){
		$this->helper->SignOut();
		$this->view->AddFlash('Sign out Successful!', \View::FlashClassSuccess);
		$this->RedirectTo('Session');
	}
	
	public function Success(){
		if(!$this->helper->IsSignedIn()){
			$this->RedirectTo('Session');
		}
		return $this->view->Success();
	}




	/*
	*	Temporary functions Used for easy testning
	*/
	public function CreateNewUser($strUserName, $strPassword){
		$db = new \db();
		$strUserName = $db->Wash($strUserName);
		$strPassword = $db->Wash($strPassword);
		$strPassword = $this->model->ScramblePassword($strPassword);
		$strSql = "
			INSERT INTO
				user (username, password)
			VALUES(
				'" . $strUserName . "',
				'" . $strPassword . "'
			)
		";
		$res = ($db->Query($strSql));
		var_dump($strUserName);
		var_dump($strPassword);
		var_dump($strSql);
		var_dump($db->GetError());
		exit;
	}

	public function ListUsers(){
		$db = new \db();
		$strSql = "
			SELECT * FROM user
		";
		$res = $db->GetRow($strSql);
		var_dump($res);
		exit;
	}

	public function Scalar(){
		$db = new \db();
		$strSql = "
			SELECT user.username FROM user WHERE user.id = 1
		";
		$db->GetScalar($strSql);
	}
}
?>