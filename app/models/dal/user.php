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
		return $db->GetRow($strSql);
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
		$result = $db->GetRow($strSql);
		return $result;
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
		return $db->GetRow($strSql);
	}

	public function saveUser($arrUser){
		$db = new \db();
		$strSql = "
			UPDATE
				user
			SET
				user.username = '" . $db->Wash($arrUser['username']) . "',
				user.password = '" . $db->Wash($arrUser['password']) . "',
				user.token = '" . $db->Wash($arrUser['token']) . "',
				user.ip = '" . $db->Wash($arrUser['ip']) . "',
				user.agent = '" . $db->Wash($arrUser['agent']) . "',
				user.cookietime = '" . $db->Wash($arrUser['cookietime']) . "'
			WHERE
				user.id = " . intval($arrUser['id']) . "
		";
		return $db->Query($strSql);
	}

}

?>