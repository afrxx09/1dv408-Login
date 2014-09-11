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
	protected $action;
	protected $view;
	protected $model;
	protected $helper;
	
	public function __construct($view, $model = null, $helper = null){
		$this->view = $view;
		$this->helper = $helper;
		$this->model =  $model;
	}
	
	public function RedirectTo($strController = '', $strAction = ''){
		$strLocaton = ROOT_PATH . (($strController != '') ? $strController . '/' : '') . (($strAction != '') ? $strAction . '/' : '');
		header('location: ' . $strLocaton);
		die();
	}
}
?>