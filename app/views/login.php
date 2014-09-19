<?php

namespace view;

class LoginView extends \View{
	
	const EmptyUserName = 'Username can not be empty!';
	const EmptyPassword = 'Password can not be empty!';
	const SignInSuccess = 'Sign in successful!';
	const SignOutSuccess = 'Sign out successfull';
	const AuthFail = 'Incorrect Username or password.';
	const UnknownIp = 'The IP-address is not known to this account, Sign out was forced(add IP to known list later).';
	const UnknownAgent = 'Unknown user agent. Suspected session hijacking forced sign out.';
	const CookieCreated = 'Cookie for persistent connection created.';
	const CookieDestroyed = 'Cookie for persistent connection destroyed';
	const CookieLogin = 'Successfully signed in with persistent cookie.';
	const CookieLoginFail = 'Could not Sign in with persistent cookie.';
	
	private $loginModel;
	
	//private $intCookieTime = 2592000; //60*60*24*30 = 30 days
	private $intCookieTime = 20; //60*60*24*30 = 30 days
	private $strCookieName = 'auth';
	
	public function __construct($loginModel){
		parent::__construct();
		$this->loginModel = $loginModel;
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
	
	public function createAuthCookie($user){
		$strCookieContent = $this->loginModel->generateCookieContent($user);
		$intCookieTime = $user->getCookieTime() + $this->intCookieTime;
		setcookie($this->strCookieName, $strCookieContent, $intCookieTime, '/');
		$this->addFlash(self::CookieCreated, self::FlashClassWarning);
	}
	
	public function destroyAuthCookie(){
		unset($_COOKIE[$this->strCookieName]);
		setcookie($this->strCookieName, '', time()-3600, '/');
		$this->addFlash(self::CookieDestroyed, self::FlashClassWarning);
	}
	
	public function getAuthCookie(){
		return $_COOKIE[$this->strCookieName];
	}
	
	public function getAuthCookieTime(){
		return $this->intCookieTime;
	}
	
	//	HTML-for render
	public function NewSession(){
		return '
			<h2>Not signed in</h2>
			' . $this->RenderFlash() .'
			<div id="SignInForm">
				<form method="post" action="' . ROOT_PATH . 'Login/CreateSession">
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
			' . $this->renderDateTimeString() . '
		';
	}
	
	public function successPage(){
		$user = $this->loginModel->getUserByToken($this->loginModel->getSessionToken());
		return '
			<h2>Signed in as: ' . $user->getUsername() . '</h2>
			' . $this->RenderFlash() .'
			<div>
				<p>Page for logged in users.</p>
				<p><a href="' . ROOT_PATH . 'Login/DestroySession">Sign out</a></p>
			</div>
			' . $this->renderDateTimeString() . '
		';
	}

	private function renderDateTimeString(){
		$arrDays = array('Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag', 'Söndag');
		$arrMonths = array('Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December');
		$strDay = $arrDays[$date = date('N') - 1];
		$strMonth = $arrMonths[$date = date('n') - 1];
		return $strDay .', den ' . date('j') . ' ' . $strMonth . ' år ' . date('Y') . '. Klockan är [' . date('H:i:s') . '].';
	}
}
?>