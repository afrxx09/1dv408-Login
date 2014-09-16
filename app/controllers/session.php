<?php

namespace controller;

class SessionController extends \Controller{
	
	private $view;
	private $sessionModel;
	private $userModel;
	
	public function __construct(){
		parent::__construct();
		$this->sessionModel = new \model\SessionModel();
		$this->userModel = new \model\UserModel();
		$this->view = new \view\SessionView($this->sessionModel, $this->userModel);
	}
	
	/*Index is the default function for all controllers, this redirects the default to NewSession instead.*/
	public function index(){
		$this->newSession();
	}

	/*
	*	If the user isn't signed in already a form will be renderd.
	*/
	public function newSession(){
		if($this->sessionModel->loginSessionExists()){
			$this->redirectTo('Session', 'Success');
		}
		if($this->view->authCookieExists()){
			$this->view->signInWithCookie();
		}
		$this->view->Render($this->view->NewSession());
	}
	/*
	public function NewSession(){
		if($this->helper->IsSignedIn()){
			$this->RedirectTo('Session', 'Success');
		}
		
		$this->view->Render($this->view->NewSession());
	}
	*/
	public function createSession(){
		//Check that the form was posted
		if($this->view->checkSignInTry()){
			//Get all necessary variables from sign in form
			$strUserName = $this->view->getSignInUserName();
			$strPassword = $this->view->getSignInPassword();
			$boolRemeber = $this->view->getKeepMeLoggedIn();
			
			//Make sure user provided username and password
			if($strUserName === '' || $strPassword === ''){
				$this->view->addFlash(\View\SessionView::EmptyUserNamePassword, \View::FlashClassError);
				$this->redirectTo('Session');
			}
			
			$arrUser = $this->userModel->getUserByUserName($strUserName);
			if($arrUser !== false && $this->userModel->auth($arrUser, $strPassword)){
				if($boolRemeber){
					$arrUser = $this->userModel->prepareUserDataForCookie($arrUser);
					$this->view->createAuthCookie($arrUser);
				}
				$this->sessionModel->createLoginSession();
				//After Sign in, let user know it was signed in and Redirect to desired page(controller#action);
				$this->view->addFlash(\View\SessionView::LoginSuccess, \View::FlashClassSuccess);
				$this->redirectTo('Session', 'Success');
			}
			else{
				//Could not Auth user, this means that either username or password was faulty. Redirect back to Sign in form
				$this->view->addFlash(\View\SessionView::AuthFail, \View::FlashClassError);
				$this->redirectTo('Session');
			}
		}
		//if the login-form was not posted, url was changed manually so redirect to Sign in form
		$this->redirectTo('Session');
	}
	/*
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
	*/
	
	public function DestroySession(){
		
	}
	/*
	public function DestroySession(){
		$this->helper->SignOut();
		$this->view->AddFlash('Sign out Successful!', \View::FlashClassSuccess);
		$this->RedirectTo('Session');
	}
	*/
	public function SuccessPage(){
		
	}
	/*
	public function Success(){
		if(!$this->helper->IsSignedIn()){
			$this->RedirectTo('Session');
		}
		$this->view->Render($this->view->Success());
	}
	*/
}
?>