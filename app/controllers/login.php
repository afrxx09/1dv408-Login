<?php

namespace controller;

class Login extends Controller{
	
	public function SignIn($q){
		if(isset($_POST['username'])){
			echo $_POST['username'];
			exit;
		}
		
		return $this->view->SignIn();
	}
}
?>