<?php
namespace view;
class Login{
	private $strAction;
	
	public function __construct(){
		$this->SetAction();
	}
	
	private function SetAction(){
		$this->strAction = (isset($_GET['a'])) ? $_GET['a'] : null;
	}
	
	public function GetAction(){
		return $this->strAction;
	}
	
	public function RenderLoginForm(){
		$strLoginForm ='
			<form>
				<div>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" />
				</div>
				<div>
					<label for="password">Password</label>
					<input type="password" name="password" id="password" />
				</div>
			</form>
		';
		return $strLoginForm;
	}
	
	public function RenderLogin(){
		return 'logged in';
	}
	
	public function RenderLogout(){
		return 'logged out';
	}
}
?>