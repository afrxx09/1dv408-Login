<?php

namespace view;

class SessionView extends \View{
	
	const EmptyUserNamePassword = 'Username and password can not be empty!';
	const LoginSuccess = 'Login successful!';
	const AuthFail = 'Incorrect Username or password.';
	const CookieCreated = 'Cookie for persistent connection created.';
	const CookieLogin = 'Successfully logged in with persistent cookie.';
	
	private $sessionModel;
	private $userModel;
	
	private $intCookieTime = 2592000; //60*60*24*30 = 30 days
	private $strCookieName = 'auth';
	
	public function __construct($sessionModel, $userModel){
		parent::__construct();
		$this->sessionModel = $sessionModel;
		$this->userModel = $userModel;
	}
	
	//	Get $_POST-values
	public function checkSignInTry(){
		return isset($_POST['username']);
	}

	public function getSignInUserName(){
		return isset($_POST['username']) ? $_POST['username'] : '';
	}

	public function getSignInPassword(){
		return isset($_POST['password']) ? $_POST['password'] : '';
	}

	public function getKeepMeLoggedIn(){
		return isset($_POST['keep-me-signed-in']);
	}
	
	//	Cookie stuff
	public function authCookieExists(){
		return isset($_COOKIE[$this->strCookieName]);
	}
	
	public function createAuthCookie($arrUser){
		$strCookieContent = $this->userModel->generateCookieContent($arrUser);
		$intCookieTime = $this->userModel->GetLoginTime($arrUser) + $this->intCookieTime;
		setcookie($this->strCookieName, $strCookieContent, $intCookieTime, '/');
	}
	
	public function destroyAuthCookie(){
		unset($_COOKIE[$this->strCookieName]);
		setcookie($this->strCookieName, '', time()-3600, '/');
	}
	
	public function getAuthCookie(){
		return $_COOKIE[$this->strCookieName];
	}

	public function signInWithCookie(){
		$arrCookie = explode(':', $this->getAuthCookie());
		$strToken = $arrCookie[0];
		$strIdentifier = $arrCookie[1];
		$strLoginTime = intval($arrCookie[2]);
		$arrUser = $this->userModel->getUserByToken($strToken);
		if($arrUser !== false){
			$strCurrentVisitorIdentifier = $this->userModel->generateIdentifier();
			if($strCurrentVisitorIdentifier === $strIdentifier && $this->userModel->getLoginTime($arrUser) === $strLoginTime){
				$this->sessionModel->createLoginSession();
				return true;
			}
		}
		return false;
	}

	
	//	HTML-for render
	public function NewSession(){
		return '
			<h2>Not signed in</h2>
			' . $this->RenderFlash() .'
			<div id="SignInForm">
				<form method="post" action="' . ROOT_PATH . 'Session/CreateSession">
					<div class="form-row">
						<label for="username">Username</label>
						<input type="text" name="username" id="username" />
					</div>
					<div class="form-row">
						<label for="password">Password</label>
						<input type="password" name="password" id="password" />
					</div>
					<div class="form-row">
						<label for="keep-me-signed-in">Keep me signed in</label>
						<input type="checkbox" id="keep-me-signed-in" name="keep-me-signed-in" />
					</div>
					<div class="form-row">
						<input type="submit" value="Sign in" />
					</div>
					<div class="clear"></div>
				</form>
			</div>
		';
	}
	
	public function successPage(){
		return '
			<h2>Signed in as: ' . /*$this->helper->CurrentUser()*/'' . '</h2>
			' . $this->RenderFlash() .'
			<div>
				<p>Page for logged in users.</p>
				<p><a href="' . ROOT_PATH . 'Session/DestroySession">Sign out</a></p>
			</div>'
		;
	}
}
?>