<?php

class db{
	private $arrError = array();
	protected static $con;

	private function Connect(){
		if(!isset(self::$con)){
			self::$con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
		}
		return (self::$con === false) ? false : self::$con;
	}

	public function Query($strSql){
		$con = $this->Connect();
		$result = $con->query($strSql) or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);;
		if($result === false){
			$this->AddError($strSql);
			return false;
		}
		return true;
	}

	public function GetScalar($strSql){
		$con = $this->Connect();
		$result = $con->query($strSql);
		if($result->num_rows !== 1){
			$this->AddError($strSql);
			return false;
		}
		$arr = $result->fetch_row();
		$val = $arr[0];
		return $val;
	}

	public function GetRow($strSql){
		$con = $this->Connect();
		$result = $con->query($strSql);
		if($result === false || $result->num_rows != 1){
			$this->AddError($strSql);
			return false;
		}

		$row = $result->fetch_assoc();
		return $row;
	}

	public function GetAsoc($strSql){
		$con = $this->Connect();
		$rows = array();
		$result = $con->query($strSql);
		if($result === false){
			$this->AddError($strSql);	
			return false;
		}
		while($row = $result->fetch_assoc()){
			$rows[] = $row;
		}
		return $rows;
	}

	public function Wash($strString){
		$con = $this->Connect();
		return $con->real_escape_string($strString);
	}

	public function GetError(){
		$con = $this->Connect();
		return $con->error;
	}

	private function AddError($strSql){
		$this->arrError['message'] = $this->GetError();
		$this->arrError['sql'] = $strSql;
	}

	public function GetErrorArray(){
		return $this->arrError;
	}
}

?>