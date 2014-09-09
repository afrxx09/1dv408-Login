<?php
/*
*	Base class for all Controllers
*	
*	Purpose is to have generic "controller functionality" accessible in all controllers.
*	It's constructor creates a view, model and helper too so that the other controllers don't need a constructor at all in most cases
*	
*	NOTICE:(Might have to change behavior and location)
*	RedirectTo function is a lazy way of jumping between controllers and/or actions.
*	
*/
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
	
	public function RedirectTo($strController = '', $strAction = ''){
		$strLocaton = ROOT_PATH . (($strController != '') ? $strController . '/' : '') . (($strAction != '') ? $strAction . '/' : '');
		header('location: ' . $strLocaton);
		die();
	}
	
}
?>