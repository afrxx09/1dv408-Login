<?php

namespace view;

class Login extends View{
	
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

	public function SignIn(){
		return '
			' . $this->RenderFlash() .'
			<div>
				<form method="post" action="' . ROOT_PATH . 'Login/SignIn">
					<div>
						<label for="username">Username</label>
						<input type="text" name="username" id="username" />
					</div>
					<div>
						<label for="password">Password</label>
						<input type="password" name="password" id="password" />
					</div>
					<div>
						<label for="keep-me-signed-in">Keep me signed in</label>
						<input type="checkbox" id="keep-me-signed-in" name="keep-me-signed-in" />
					</div>
					<div>
						<input type="submit" value="Sign in" />
					</div>
				</form>
			</div>
		';
	}
	
	public function Success(){
		return '
			' . $this->RenderFlash() .'
			<div>
				<p>Page for logged in users.</p>
				<p><a href="' . ROOT_PATH . 'login/signout">Sign out</a></p>
			</div>'
		;
	}
}
?>