<?php

class Controller{
	
	protected $view;
	protected $model;
	protected $helper;
	
	protected $strDefaultAction = 'Index';
	
	public function __construct(){
		$r = new \ReflectionClass($this);
		$s = ucfirst(str_ireplace('controller', '', $r->GetShortName()));
		
		$strModel = '\model\\' . $s . 'Model';
		$strHelper = '\helper\\' . $s . 'Helper';
		$strView = '\view\\' . $s . 'View';
		
		$this->model = new $strModel();
		$this->helper = new $strHelper($this->model);
		$this->view = new $strView($this->model, $this->helper);
	}
	
	public function GetDefaultAction(){
		return $this->strDefaultAction;
	}
	
}
?>