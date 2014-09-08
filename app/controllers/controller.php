<?php

namespace controller;

class Controller{
	
	protected $view;
	protected $model;
	protected $helper;
	private $output;
	
	public function __construct(){
		$this->model = new \model\Login();
		$this->helper = new \helper\Login($this->model);
		$this->view = new \view\Login($this->model, $this->helper);
	}
	
	public function RunAction($a, $q){
		$this->output = $this->$a($q);
	}
	
	public function Draw(){
		echo $this->output;
	}
	
}
?>