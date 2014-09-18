<?php

namespace model\dal;

class UserDAL{
	
	public function __construct(){

	}

	public function getUserById($intId){
		$db = new \db();
		$strSql = "
			SELECT
				user.*
			FROM
				user
			WHERE
				user.id = " . intval($intId) . "
			LIMIT
				1
		";
		$r = $db->GetRow($strSql);
		if($r ==! false){
			try{
				$user = new \model\dobj\User($r);
				return $user;
			}
			catch(\Exception $e){
				//Empty
			} 
		}
		return null;
	}

	public function getUserByToken($strToken){
		$db = new \db();
		$strSql = "
			SELECT
				user.*
			FROM
				user
			WHERE
				user.token = '" . $db->Wash($strToken) . "'
			LIMIT
				1
		";
		$r = $db->GetRow($strSql);
		if($r ==! false){
			try{
				$user = new \model\dobj\User($r);
				return $user;
			}
			catch(\Exception $e){
				//Empty
			} 
		}
		return null;
	}

	public function getUserByUserName($strUserName){
		$db = new \db();
		$strSql = "
			SELECT
				user.*
			FROM
				user
			WHERE
				user.username = '" . $db->Wash($strUserName) . "'
			LIMIT
				1
		";
		$r = $db->GetRow($strSql);
		if($r ==! false){
			try{
				$user = new \model\dobj\User($r);
				return $user;
			}
			catch(\Exception $e){
				//Empty
			} 
		}
		return null;
	}

	public function saveUser($user){
		$db = new \db();
		$strSql = "
			UPDATE
				user
			SET
				user.username = '" . $db->Wash($user->getUsername()) . "',
				user.password = '" . $db->Wash($user->getPassword()) . "',
				user.token = '" . $db->Wash($user->getToken()) . "',
				user.ip = '" . $db->Wash($user->getIp()) . "',
				user.agent = '" . $db->Wash($user->getAgent()) . "',
				user.cookietime = '" . $db->Wash($user->getCookieTime()) . "'
			WHERE
				user.id = " . intval($user->getId()) . "
		";
		return $db->Query($strSql);
	}

}

?>