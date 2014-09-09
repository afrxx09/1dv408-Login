<?php

namespace model;

class SessionModel extends \Model{
	
	public function GetUserByUserName($strUserName){
		/*Get user from DB later*/
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
	
	public function ScramblePassword($intUserId, $strPassword){
		$salt = $intUserId;
		$pepper = 'asd123';
		return sha1($salt . $strPassword . $pepper);
	}
	
	public function Auth($arrUser, $strPassword){
		$p1 = $this->ScramblePassword($arrUser['id'],  $strPassword);
		$p2 = $arrUser['password'];
		return ($p1 === $p2) ? true : false;
	}

	public function GenerateToken(){
		return sha1(uniqid(rand(), true));
	}

	public function GenerateIdintifier($arrUser){
		$strSalt = 'th3s4ltstr1n6';
		return sha1($strSalt . sha1($arrUser['username'] . $strSalt));
	}
}
?>