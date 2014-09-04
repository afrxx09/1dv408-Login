<?php

namespace controller;

class Login{
	private $view;
	private $strTitle;
	private $strHtml;
	
	public function __construct(){
		$this->view = new \view\Login();
		$this->SelectAction();
	}
	
	private function SelectACtion(){
		switch($this->view->GetAction()){
			case 'show':
				$this->ShowLoginForm();
				break;
			case 'login':
				$this->Login();
				break;
			case 'logout':
				$this->Logout();
				break;
			default:
				$this->ShowLoginForm();
				break;
		}
	}
	
	public function GetHTML(){
		return $this->strHtml;
	}
	
	public function GetTitle(){
		return $this->strTitle;
	}
	
	private function ShowLoginForm(){
		$this->strTitle = 'Log in';
		$this->strHtml = $this->view->RenderLoginForm();
	}
	
	private function Login(){
		$this->strTitle = 'Logged in';
		$this->strHtml = $this->view->RenderLogin();
	}
	
	
	private function Logout(){
		$this->strTitle = 'Logged out';
		$this->strHtml = $this->view->RenderLogout();
	}
}
?>