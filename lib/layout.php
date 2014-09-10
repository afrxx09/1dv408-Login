<?php
/*
*	A class for rendering full HTML-pages with help of template-files.
*	Gives option to choose what HTML-standard to render: Strict, Transitional or HTML5.

*	TODO: Figure out how to add content, includes, css, javascript etc dynamically from view or controller classes.
*/
class Layout{
	private $strVersion;

	private $arrCssFiles = array();
	private $arrJavascriptFiles = array();
	
	private $strBody;
	
	//accepts optional argument for HTML-standard
	public function __construct($strVersion = 'strict'){
		$this->SetVersion($strVersion);
		$this->SetCssFiles();
		$this->SetJavascriptFiles();
	}
	
	//Checks choosen standard to make sure it is one of 3 supported and that has templates
	private function SetVersion($strVersion){
		$this->strVersion = (in_array(strtolower($strVersion), array('html5', 'strict', 'transitional'))) ? strtolower($strVersion) : 'strict';
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
	
	/*
	*	Reads content of a template file for choosen HTML-standard and returns it.
	*/
	private function GetApplicationHtml(){
		$strFileName = 'application-' . $this->strVersion . '.html.php';
		$strFullPath = APP_DIR . 'layout/' . $strFileName;
		$f = fopen($strFullPath, 'r');
		$strHTML = fread($f, filesize($strFullPath));
		fclose($f);
		return $strHTML;
	}

	private function GetLayoutBody(){
		$strFullPath = APP_DIR . 'layout/layout-body.html.php';
		$f = fopen($strFullPath, 'r');
		$strLayoutBody = fread($f, filesize($strFullPath));
		fclose($f);
		return $strLayoutBody;
	}
	
	/*
	*	Uses a template and fills it with content before printing the final html-document. 
	*/
	public function PrintLayout(){
		$strLayoutBody = $this->GetLayoutBody();
		$strLayoutBody = str_replace('<!--{APPBODY}-->', $this->strBody, $strLayoutBody);

		$strHTML = $this->GetApplicationHtml(); 
		$strHTML = str_replace('<!--{CSS}-->', $this->RenderCssTags(), $strHTML);
		$strHTML = str_replace('<!--{JAVASCRIPT}-->', $this->RenderJavascriptTags(), $strHTML);
		$strHTML = str_replace('<!--{HTMLBODY}-->',$strLayoutBody, $strHTML);
		echo $strHTML;
	}
}

?>