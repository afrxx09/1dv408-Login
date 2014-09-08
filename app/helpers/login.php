<?php

namespace helper;

class Login Extends Helper{
	
	public function SignIn($arrUser){
		$_SESSION['user'] = $arrUser['username'];
	}
	
	public function IsUserSignedIn(){
		return isset($_SESSION['user']);
	}
}

?>