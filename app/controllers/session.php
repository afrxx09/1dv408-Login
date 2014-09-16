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
			$this->redirectTo('Session', 'successPage');
		}
		if($this->view->authCookieExists() && $this->view->signInWithCookie()){
			$this->view->addFlash(\View\SessionView::CookieLogin, \View::FlashClassSuccess);
			$this->redirectTo('Session', 'successPage');
		}
		$this->view->Render($this->view->NewSession());
	}
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
					if($arrUser !== false){
						$this->view->createAuthCookie($arrUser);
						$this->view->addFlash(\View\SessionView::CookieCreated, \View::FlashClassSuccess);
					}
				}
				$this->sessionModel->createLoginSession();
				//After Sign in, let user know it was signed in and Redirect to desired page(controller#action);
				$this->view->addFlash(\View\SessionView::LoginSuccess, \View::FlashClassSuccess);
				$this->redirectTo('Session', 'successPage');
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
	
	public function destroySession(){
		$this->sessionModel->destroyLoginSession();
		$this->view->destroyAuthCookie();
		$this->redirectTo('Session');
	}
	
	public function successPage(){
		if(!$this->sessionModel->loginSessionExists()){
			$this->redirectTo('Session');
		}
		if(!$this->view->authCookieExists() || !$this->view->signInWithCookie()){
			$this->view->addFlash(\View\SessionView::CookieLogin, \View::FlashClassSuccess);
			$this->redirectTo('Session');
		}
		$this->view->render($this->view->successPage());
	}
	
}
?>