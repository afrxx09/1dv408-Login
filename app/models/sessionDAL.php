<?php

namespace model;

class SessionDAL{
	
	public function __construct(){

	}

	public function GetUserById($intId){
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
		return $db->GetRow($strSql);
	}

	public function GetUserByToken($strToken){
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
		$result = $db->GetRow($strSql);
		return $result;
	}

	public function GetUserByUserName($strUserName){
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
		return $db->GetRow($strSql);
	}

	public function SaveUser($user){
		$db = new \db();
		$strSql = "
			UPDATE
				user
			SET
				user.username = '" . $db->Wash($user->GetUserName()) . "',
				user.password = '" . $db->Wash($user->GetPassword()) . "',
				user.token = '" . $db->Wash($user->GetToken()) . "',
				user.identifier = '" . $db->Wash($user->GetIdentifier()) . "'
			WHERE
				user.id = " . intval($user->GetId()) . "
		";
		$res = $db->Query($strSql);
		if($res === false){
			var_dump($db->GetErrorArray());
			exit;
		}
		return $res;

	}

}

?>