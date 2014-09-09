<?php

class Layout{
	private $strVersion;

	private $arrCssFiles = array();
	private $arrJavascriptFiles = array();
	
	private $strBody;
	
	public function __construct($strVersion = 'strict'){
		$this->SetVersion($strVersion);
		$this->SetCssFiles();
		$this->SetJavascriptFiles();
	}
	
	private function SetVersion($strVersion){
		$this->strVersion = (in_array(strtolower($strVersion), array('html5', 'strict'. 'transitional'))) ? strtolower($strVersion) : 'strict';
	}
	
	private function SetCssFiles(){
		$this->arrCssFiles = array(
			'style.css'
		);
	}
	
	private function SetJavascriptFiles(){
		$this->arrJavascriptFiles = array(
			'jquery-1.11.1.min.js',
			'application.js'
		);
	}
	
	public function SetBody($strBody){
		$this->strBody = $strBody;
	}
	
	private function RenderCssTags(){
		$strCssTags = '';
		foreach($this->arrCssFiles as $strCssFileName){
			$strCssTags .= '<link href="' . CSS_PATH . $strCssFileName . '" media="all" rel="stylesheet" type="text/css" />' . "\n";
		}
		return trim($strCssTags);
	}
	
	private function RenderJavascriptTags(){
		$strJavascriptTags = '';
		foreach($this->arrJavascriptFiles as $strJavascriptFileName){
			$strJavascriptTags .= '<script src="' . JS_PATH . $strJavascriptFileName . '" type="text/javascript"></script>' . "\n";
		}
		return trim($strJavascriptTags);
	}
	
	private function RenderHtml(){
		$strFileName = 'application-' . $this->strVersion . '.html.php';
		$strFullPath = APP_DIR . 'layout/' . $strFileName;
		$f = fopen($strFullPath, 'r');
		$strHTML = fread($f, filesize($strFullPath));
		fclose($f);
		return $strHTML;
	}
	
	public function PrintLayout(){
		$strHTML = $this->RenderHtml();
		$strHTML = str_replace('<!--{CSS}-->', $this->RenderCssTags(), $strHTML);
		$strHTML = str_replace('<!--{JAVASCRIPT}-->', $this->RenderJavascriptTags(), $strHTML);
		$strHTML = str_replace('<!--{BODY}-->', $this->strBody, $strHTML);
		echo $strHTML;
	}
}

?>