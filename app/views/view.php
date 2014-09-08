<?php

namespace view;

class View{
	private $model;
	private $helper;
	
	public function __construct($model, $helper){
		$this->model = $model;
		$this->helper = $helper;
	}
}

?>