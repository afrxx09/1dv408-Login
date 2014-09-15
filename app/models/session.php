<?php

namespace model;

class SessionModel extends \Model{
	private $DAL;
	public function __construct(){
		$this->DAL = new \model\SessionDAL();
		parent::__construct();
	}

	public function GetUserByToken($strToken){
		$arrUser = $this->DAL->GetUserByToken($strToken);
		return ($arrUser === false) ? null : new \model\user($arrUser);
	}

	public function GetUserById($intId){
		$arrUser = $this->DAL->GetUserById($intId);
		return ($arrUser === false) ? null : new \model\user($arrUser);
	}

	public function GetUserByUserName($strUserName){
		$arrUser = $this->DAL->GetUserByUserName($strUserName);
		return ($arrUser === false) ? null : new \model\User($arrUser);
	}

	public function SaveUser($user){
		return ($this->DAL->SaveUser($user)) ? $user : null;
	}

	/*
	public function GetUserByUserNameOLD($strUserName){
		//Get user from DB later
		$UserDoesExistInDb = true;
		
		if($UserDoesExistInDb){
			$arrUser = array(
				'id' => 1,
				'username' => 'admin',
				'password' => 'pwd'
			);
			
			//Passwords will be scrambled when they are received from DB
			$arrUser['password'] = $this->ScramblePassword($arrUser['id'], $arrUser['password']);
			
			return $arrUser;
		}
		else{
			return null;
		}
	}
	*/
	
	public function ScramblePassword($strPassword){
		//Will make more complex if there is time.
		$salt = 'asd123';
		return sha1($salt . $strPassword);
	}
	
	public function Auth($user, $strPassword){
		$p1 = $this->ScramblePassword($strPassword);
		$p2 = $user->GetPassword();
		return ($p1 === $p2) ? true : false;
	}

	public function GenerateToken(){
		return sha1(uniqid(rand(), true));
	}

	public function GenerateIdintifier($strLoginTime, $strAgent, $strIp){
		return sha1($strLoginTime . $strAgent . $strIp);
	}
}
?>