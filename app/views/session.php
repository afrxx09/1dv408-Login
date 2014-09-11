<?php

namespace view;

class SessionView extends \View{
	
	const EmptyUserNamePassword = 'Username and password can not be empty!';
	const LoginSuccess = 'Login successful!';
	const AuthFail = 'Incorrect Username or password.';

	public function CheckSignInTry(){
		return isset($_POST['username']);
	}

	public function GetSignInUserName(){
		return isset($_POST['username']) ? $_POST['username'] : '';
	}

	public function GetSignInPassword(){
		return isset($_POST['password']) ? $_POST['password'] : '';
	}

	public function GetKeepMeLoggedIn(){
		return isset($_POST['keep-me-signed-in']);
	}

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
	
	public function Success(){
		return '
			<h2>Signed in as: ' . $this->helper->CurrentUser() . '</h2>
			' . $this->RenderFlash() .'
			<div>
				<p>Page for logged in users.</p>
				<p><a href="' . ROOT_PATH . 'Session/DestroySession">Sign out</a></p>
			</div>'
		;
	}
}
?>