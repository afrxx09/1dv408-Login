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
			//$arrUser = $this->userModel->getUserByToken($this->sessionModel->getSessionToken());
			$user = $this->userModel->getUserByToken($this->sessionModel->getSessionToken());
			//if($arrUser !== false){
			if($user !== null){
				//Check if the User agent is the same in the DB as on the client
				//if(!$this->userModel->checkAgent($arrUser)){
				if(!$this->userModel->checkAgent($user)){
					$this->view->addFlash(\View\SessionView::UnknownAgent, \View::FlashClassError);
				}
				//Check the IP-address from DB and client
				//else if(!$this->userModel->checkIp($arrUser)){
				else if(!$this->userModel->checkIp($user)){
					$this->view->addFlash(\View\SessionView::UnknownIp, \View::FlashClassError);
				}
				else{
					$boolSuccess = true;
				}
			}
		}
		else{
			if($this->view->authCookieExists()){
				if(!$this->signInWithCookie()){
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
			//$arrUser = $this->userModel->getUserByUserName($strUserName);
			$user = $this->userModel->getUserByUserName($strUserName);
			
			//Make sure a user was found and also that the password was correct
			//if($arrUser !== false && $this->userModel->auth($arrUser, $strPassword)){
			if($user !== null && $user->auth($strPassword)){
				//Create sign in-token. Update login time, user agent and ip on the user
				//$arrUser = $this->userModel->updateSignInData($arrUser, $boolRemeber);
				$user->updateSignInData($this->userModel->GenerateToken(), $boolRemeber);
				$this->userModel->saveUser($user);
				//Create a persistent cookie if that was requested
				if($boolRemeber){
					//$this->view->createAuthCookie($arrUser);
					$this->view->createAuthCookie($user);
				}
				//Finally set login-session that detemines a successfull login
				$this->sessionModel->createLoginSession($user->getToken());
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
	
	public function signInWithCookie(){
		$arrCookie = explode(':', $this->view->getAuthCookie());
		$strCookieToken = $arrCookie[0];
		$strCookieIdentifier = $arrCookie[1];
		//$arrUser = $this->userModel->getUserByToken($strCookieToken);
		$user = $this->userModel->getUserByToken($strCookieToken);
		//if($arrUser !== false){
		if($user !== null){
			$strCurrentVisitorIdentifier = $this->userModel->generateIdentifier();
			//Compare identification string from cookie to newly generated one
			if($strCurrentVisitorIdentifier === $strCookieIdentifier){
				//Check in database on user when cookie was created, add the amount of time the view saves cookies.(time cookie was created + 30 days)
				//If the time right now is less than that(time created + 30 days) it's presumed that the cookie expire date has been tampered with
				if(($user->getCookieTime() + $this->view->getAuthCookieTime()) > time()){
					$this->sessionModel->createLoginSession($user->getToken());
				}
				return true;
			}
		}
		return false;
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
	}
	

	/* Temporary Administrative functions */

}
?>