<?php

namespace controller;

class Controller{
	
	protected $view;
	protected $model;
	protected $helper;
	
	protected $strDefaultAction = 'Index';
	
	public function __construct(){
		$r = new \ReflectionClass($this);
		$s = $r->GetShortName();
		
		$strModel = '\model\\' . $s;
		$strHelper = '\helper\\' . $s;
		$strView = '\view\\' . $s;
		
		$this->model = new $strModel();
		$this->helper = new $strHelper($this->model);
		$this->view = new $strView($this->model, $this->helper);
	}
	
	public function GetDefaultAction(){
		return $this->strDefaultAction;
	}
	
}
?>