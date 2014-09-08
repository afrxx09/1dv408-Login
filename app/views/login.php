<?php

namespace view;

class Login extends View{
	
	public function SignIn(){
		return '
			' . $this->RenderFlash() .'
			<div>
				<form method="post" action="signin">
					<div>
						<label for="username">Username</label>
						<input type="text" name="username" id="username" />
					</div>
					<div>
						<label for="password">Password</label>
						<input type="password" name="password" id="password" />
					</div>
					<div>
						<input type="submit" value="Sign in" />
					</div>
				</form>
			</div>
		';
	}
	
	public function Success(){
		return '<p>success</p>';
	}
}
?>