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
	
	public function checkSignIn(){
		$boolSuccess = false;
		if($this->sessionModel->loginSessionExists()){
			$arrUser = $this->userModel->getUserByToken($this->sessionModel->getSessionToken());
			if($arrUser !== false){
				//Check if the User agent is the same in the DB as on the client
				if(!$this->userModel->checkAgent($arrUser)){
					$this->view->addFlash(\View\SessionView::UnknownAgent, \View::FlashClassError);
					//$this->sessionModel->destroyLoginSession();
				}
				//Check the IP-address from DB and client
				else if(!$this->userModel->checkIp($arrUser)){
					$this->view->addFlash(\View\SessionView::UnknownIp, \View::FlashClassError);
					//$this->sessionModel->destroyLoginSession();
				}
				else{
					$boolSuccess = true;
				}
			}
		}
		else{
			if($this->view->authCookieExists()){
				if(!$this->view->signInWithCookie()){
					$this->view->addFlash(\View\SessionView::CookieLoginFail, \View::FlashClassError);
				}
				else{
					$this->view->addFlash(\View\SessionView::CookieLogin, \View::FlashClassSuccess);
					$boolSuccess = true;
				}
			}
				
		}
		return $boolSuccess;
	}
	
	/*
	*	If the user isn't signed in already a form will be renderd.
	*/
	public function newSession(){
		if(!$this->checkSignIn()){
			$this->view->Render($this->view->NewSession());
		}
		else{
			$this->redirectTo('Session', 'successPage');
		}
	}
	
	public function createSession(){
		//Check that the form was posted
		if($this->view->checkSignInTry()){
			//Get all necessary variables from sign in form
			$strUserName = $this->view->getSignInUserName();
			$strPassword = $this->view->getSignInPassword();
			$boolRemeber = $this->view->getKeepMeLoggedIn();
			
			//Make sure user provided username and password
			if($strUserName === ''){
				$this->view->addFlash(\View\SessionView::EmptyUserName, \View::FlashClassError);
				$this->redirectTo('Session');
			}
			if($strPassword === ''){
				$this->view->addFlash(\View\SessionView::EmptyPassword, \View::FlashClassError);
				$this->redirectTo('Session');
			}
			
			//Get auser based on input from sign in form
			$arrUser = $this->userModel->getUserByUserName($strUserName);
			//Make sure a user was found and also that the password was correct
			if($arrUser !== false && $this->userModel->auth($arrUser, $strPassword)){
				//Create sign in-token. Update login time, user agent and ip on the user
				$arrUser = $this->userModel->updateSignInData($arrUser);
				//Create a persistent cookie if that was requested
				if($boolRemeber){
					$this->view->createAuthCookie($arrUser); 
				}
				//Finally set login-session that detemines a successfull login
				$this->sessionModel->createLoginSession($this->userModel->getToken($arrUser));
				$this->view->addFlash(\View\SessionView::SignInSuccess, \View::FlashClassSuccess);
				$this->redirectTo('Session', 'successPage');
			}
			//Could not auth user, either username and/or password was faulty. 
			else{
				$this->view->addFlash(\View\SessionView::AuthFail, \View::FlashClassError);
				$this->redirectTo('Session');
			}
		}
		//if the login-form was not posted, url was changed manually so redirect to Sign in form
		$this->redirectTo('Session');
	}
	
	public function destroySession(){
		$this->sessionModel->destroyLoginSession();
		if($this->view->authCookieExists()){
			$this->view->destroyAuthCookie();
		}
		$this->view->addFlash(\View\SessionView::SignOutSuccess, \View::FlashClassSuccess);
		$this->redirectTo('Session');
	}
	
	public function successPage(){
		if(!$this->checkSignIn()){
			$this->redirectTo('Session');
		}
		else{
			$this->view->render($this->view->successPage());
		}
		/*
		//replace with "BeforeAction-function" later
		if(!$this->sessionModel->loginSessionExists()){
			if($this->view->authCookieExists() && !$this->view->signInWithCookie()){
				$this->view->addFlash(\View\SessionView::CookieLoginFail, \View::FlashClassError);
			}
			$this->redirectTo('Session');
		}
		$this->view->render($this->view->successPage());
		*/
		
	}
	
}
?>