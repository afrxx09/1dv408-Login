<?php

namespace Layout;

class Layout{
	private $strVersion;
	private $strBody;
	private $strTitle = 'Login by: afrxx09';
	private $arrCssFiles;
	private $arrJavascriptFiles;
	
	public function __construct($strVersion = 'strict'){
		$this->SetVersion($strVersion);
		$this->SetCssFiles();
		$this->SetJavascriptFiles();
	}
	
	private function SetVersion($strVersion){
		switch($strVersion){
			case 'html5':
				$this->strVersion = 'html5';
				break;
			case 'transitional':
				$this->strVersion = 'transitional';
				break;
			default:
				$this->strVersion = 'strict';
				break;
		}
	}
	
	private function SetCssFiles(){
		$this->arrCssFiles = array(
			'style.css'
		);
	}
	
	private function SetJavascriptFiles(){
		$this->arrJavascriptFiles = array(
			'jquery-1.11.1.min.js',
			'app.js'
		);
	}
	
	private function RenderCssTags(){
		$strCssTags = '';
		foreach($this->arrCssFiles as $strCssFileName){
			$strCssTags .= '<link href="' . CSS_PATH . $strCssFileName . '" media="all" rel="stylesheet" type="text/css" />' . "\n";
		}
		return $strCssTags;
	}
	
	private function RenderJavascriptTags(){
		$strJavascriptTags = '';
		foreach($this->arrJavascriptFiles as $strJavascriptFileName){
			$strJavascriptTags .= '<script src="' . JS_PATH . $strJavascriptFileName . '" type="text/javascript"></script>' . "\n";
		}
		return $strJavascriptTags;
		
	}
	
	public function RenderLayout($strBody){
		if($strBody === null){
			throw new \Exception('HTML-body can not be null.');
		}
		
		$this->strBody = $strBody;
		
		switch($this->strVersion){
			case 'html5':
				$html = $this->BuildHTML5();;
				break;
			case 'transitional':
				$html = $this->BuildTransitional();;
				break;
			default:
				$html = $this->BuildStrict();
				break;
		}
		
		echo $html;
	}
	
	private function BuildStrict(){
		$strict =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head> 
		<title>' . $this->strTitle . '</title> 
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		' . $this->RenderCssTags() . '
	</head> 
	<body>
		' . $this->strBody . '
		' . $this->RenderJavascriptTags() . '
	</body>
</html>';
		
		return $strict;
	}
	
	private function BuildTransitional(){
		$transitional = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>' . $this->strTitle . '</title>
	' . $this->RenderCssTags() . '
</head>
<body>
	' . $this->strBody . '
	' . $this->RenderJavascriptTags() . '
</body>
</html>';

		return $transitional;
	}
	
	private function BuildHTML5(){
		$html5 = '<!doctype html>
<html id="body">
	<head>
		<title>' . $this->strTitle . '</title>
		<meta charset="utf-8">
		' . $this->RenderCssTags() . '
	</head>
	<body>
		' . $this->strBody . '
		' . $this->RenderJavascriptTags() . '
	</body>
</html>';
		
		return $html5;
	}
}

?>