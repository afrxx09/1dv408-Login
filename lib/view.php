<?php

class View{
	protected $model;
	protected $helper;
	protected $arrFlash = array();
	
	public function __construct($model, $helper){
		$this->model = $model;
		$this->helper = $helper;
	}
	
	public function AddFlash($strMessage, $strType){
		$this->arrFlash[$strType][] = $strMessage;
	}
	
	protected function RenderFlash(){
		$strFlash = '';
		foreach($this->arrFlash as $type => $arrMessages){
			$strMessages = '';
			foreach($arrMessages as $strMessage){
				$strMessages .= '<p class="flash-message">' . $strMessage . '</p>';
			}
			$strFlash .= '<div class="flash flash-' . $type . '" />' . $strMessages . '</div>';
		}
		return $strFlash;
	}
}

?>