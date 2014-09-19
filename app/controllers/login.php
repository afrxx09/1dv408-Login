<?php

namespace controller;

class LoginController extends \Controller{
	
	private $view;
	private $loginModel;
	
	public function __construct(){
		parent::__construct();
		$this->loginModel = new \model\LoginModel();
		$this->view = new \view\LoginView($this->loginModel);
	}
	
	/*Index is the default function for all controllers, this redirects the default to NewSession instead.*/
	public function index(){
		$this->newSession();
	}
	
	public function checkSignIn(){
		$boolSuccess = false;
		if($this->loginModel->loginSessionExists()){
			$user = $this->loginModel->getUserByToken($this->loginModel->getSessionToken());
			if($user !== null){
				//Check if the User agent is the same in the DB as on the client
				if(!$this->loginModel->checkAgent($user)){
					$this->view->addFlash(\View\LoginView::UnknownAgent, \View::FlashClassError);
				}
				//Check the IP-address from DB and client
				//else if(!$this->loginModel->checkIp($arrUser)){
				else if(!$this->loginModel->checkIp($user)){
					$this->view->addFlash(\View\LoginView::UnknownIp, \View::FlashClassError);
				}
				else{
					$boolSuccess = true;
				}
			}
		}
		else{
			if($this->view->authCookieExists()){
				if(!$this->signInWithCookie()){
					$this->view->addFlash(\View\LoginView::CookieLoginFail, \View::FlashClassError);
				}
				else{
					$this->view->addFlash(\View\LoginView::CookieLogin, \View::FlashClassSuccess);
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
			$this->redirectTo('Login', 'successPage');
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
				$this->view->addFlash(\View\LoginView::EmptyUserName, \View::FlashClassError);
				$this->redirectTo('Login');
			}
			if($strPassword === ''){
				$this->view->addFlash(\View\LoginView::EmptyPassword, \View::FlashClassError);
				$this->redirectTo('Login');
			}
			
			//Get auser based on input from sign in form
			$user = $this->loginModel->getUserByUserName($strUserName);
			
			//Make sure a user was found and also that the password was correct
			if($user !== null && $user->auth($strPassword)){
				//Create sign in-token. Update login time, user agent and ip on the user
				$user = $this->loginModel->updateUserLoginData($user, $boolRemeber);
				if($user !== null){
					//Create a persistent cookie if that was requested
					if($boolRemeber){
						$this->view->createAuthCookie($user);
					}
					//Finally set login-session that detemines a successfull login
					$this->loginModel->createLoginSession($user->getToken());
					$this->view->addFlash(\View\LoginView::SignInSuccess, \View::FlashClassSuccess);
					$this->redirectTo('Login', 'successPage');
				}
				else{
					$this->view->addFlash(\View\LoginView::AuthFail, \View::FlashClassError);
					$this->redirectTo('Login');
				}
			}
			//Could not auth user, either username and/or password was faulty. 
			else{
				$this->view->addFlash(\View\LoginView::AuthFail, \View::FlashClassError);
				$this->redirectTo('Login');
			}
		}
		//if the login-form was not posted, url was changed manually so redirect to Sign in form
		$this->redirectTo('Login');
	}
	
	public function signInWithCookie(){
		$arrCookie = explode(':', $this->view->getAuthCookie());
		$strCookieToken = $arrCookie[0];
		$strCookieIdentifier = $arrCookie[1];
		$user = $this->loginModel->getUserByToken($strCookieToken);
		if($user !== null){
			$strCurrentVisitorIdentifier = $this->loginModel->generateIdentifier();
			//Compare identification string from cookie to newly generated one
			if($strCurrentVisitorIdentifier === $strCookieIdentifier){
				//Check in database on user when cookie was created, add the amount of time the view saves cookies.(time cookie was created + 30 days)
				//If the time right now is less than that(time created + 30 days) it's presumed that the cookie expire date has been tampered with
				if(!($user->getCookieTime() + $this->view->getAuthCookieTime()) > time()){
					$this->loginModel->createLoginSession($user->getToken());
					return true;
				}
			}
		}
		return false;
	}
	
	public function destroySession(){
		$this->loginModel->destroyLoginSession();
		if($this->view->authCookieExists()){
			$this->view->destroyAuthCookie();
		}
		$this->view->addFlash(\View\LoginView::SignOutSuccess, \View::FlashClassSuccess);
		$this->redirectTo('Login');
	}
	
	public function successPage(){
		if(!$this->checkSignIn()){
			$this->redirectTo('Login');
		}
		else{
			$this->view->render($this->view->successPage());
		}
	}
	

	/* Temporary Administrative functions */

}
?>