<?php

namespace Layout;

class Layout{
	private $version;
	private $body;
	private $title;
	
	public function __construct($version = 'strict'){
		$this->SetVersion($version);
	}
	
	private function SetVersion($version){
		switch($version){
			case 'html5':
				$this->version = 'html5';
				break;
			case 'transitional':
				$this->version = 'transitional';
				break;
			default:
				$this->version = 'strict';
				break;
		}
	}
	
	public function RenderLayout($body, $title){
		if($body === null){
			throw new Exeption('HTML-body can not be null.');
		}
		if($title === null){
			throw new Exeption('Title can not be null');
		}
		
		$this->body = $body;
		$this->title = $title;
		
		switch($this->version){
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
		<title>' . $this->title . '</title> 
		<meta http-equiv="content-type" content="text/html; charset=utf-8" /> 
	</head> 
	<body>
		' . $this->body . '
	</body>
</html>';
		
		return $strict;
	}
	
	private function BuildTransitional(){
		$transitional = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>' . $this->title . '</title>
</head>
<body>
	' . $this->body . '
</body>
</html>';

		return $transitional;
	}
	
	private function BuildHTML5(){
		$html5 = '<!doctype html>
<html id="body">
	<head>
		<title>' . $this->title . '</title>
		<meta charset="utf-8">
	</head>
	<body>
		' . $this->body . '
	</body>
</html>';
		
		return $html5;
	}
}

?>