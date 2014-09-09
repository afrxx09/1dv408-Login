<?php

/*
*	Base class for views.
*
*	Purpose is to show success/error/warning-messages to the user.
*	They can be added from a controller or the view it self.
*	They are stored as an array in the session-variable so that they live longer than a page load.
*
*/

class View{
	protected $model;
	protected $helper;
	protected $strFlashKey = 'View::FlashMessages';
	
	public function __construct($model, $helper){
		$this->model = $model;
		$this->helper = $helper;
	}
	
	public function AddFlash($strMessage, $strType){
		$_SESSION[$this->strFlashKey][$strType][] = $strMessage;
	}
	
	protected function RenderFlash(){
		$arrFlash = (isset($_SESSION[$this->strFlashKey])) ? $_SESSION[$this->strFlashKey] : array();
		$strFlash = '';
		foreach($arrFlash as $type => $arrMessages){
			$strMessages = '';
			foreach($arrMessages as $strMessage){
				$strMessages .= '<p class="flash-message">' . $strMessage . '</p>';
			}
			$strFlash .= '<div class="flash flash-' . $type . '" />' . $strMessages . '</div>';
		}
		unset($_SESSION[$this->strFlashKey]);
		return $strFlash;
	}
}

?>