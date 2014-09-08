<?php

namespace controller;

class Login extends Controller{
	
	public function SignIn($q){
		if(isset($_POST['username'])){
			$strUserName = $_POST['username'];
			$strPassword = $_POST['password'];
			
			$arrUser = $this->model->GetUserByUserName($strUserName);
			
			if($arrUser != null && $this->model->Auth($arrUser, $strPassword)){
				$this->helper->SignIn($arrUser);
				
				$this->view->AddFlash('Login Successful!', 'success');
				return $this->view->Success();
			}
			else{
				$this->view->AddFlash('Could not log in.', 'error');
				return $this->view->SignIn();
			}
		}
		else{
			return $this->view->SignIn();
		}
	}
}
?>