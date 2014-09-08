<?php
/*
*	Loads class files automaticly
*	Inspiration from: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
*/
function AutoLoadClasses($class){
	$class = ltrim($class, '\\');
	
	$strNamespace = '';
	$strClassName = '';
	$intSeparatorPos = strpos($class, '\\');
	if($intSeparatorPos !== false){
		$strNamespace = strToLower(substr($class, 0, $intSeparatorPos));
		$strClassName = strToLower(substr($class, $intSeparatorPos + 1));
	}
	else{
		$strClassName = strToLower($class);
	}
	
	//$strDirPath = Loader::GetNamespacePath($strNamespace);
	
	$strDirPath = '';
	switch($strNamespace){
		case 'view':
			$strDirPath = VIEWS_DIR;
			break;
		case 'helper':
			$strDirPath = HELPERS_DIR;
			break;
		case 'controller':
			$strDirPath = CONTROLLERS_DIR;
			break;
		case 'model':
			$strDirPath = MODELS_DIR;
			break;
		case 'layout':
			$strDirPath = LAYOUT_DIR;
			break;
		default:
			$strDirPath = '';
			break;
	}
	
	$strFilePath = $strDirPath . $strClassName . '.php';
	if(!file_exists($strFilePath)){
		return false;
	}
	require_once($strFilePath);
	
}

spl_autoload_register('AutoLoadClasses');

class Loader{
	private $url;
	private $controller;
	private $action;
	private $query;
	private $output;
	
	public function __construct($url){
		$this->url = $url;
		$this->SplitUrlComponents();
		$this->LoadController();
	}
	
	private function SplitUrlComponents(){
		$arrUrl = explode('/', $this->url);
		
		$this->controller = '\controller\\' . $arrUrl[0];
		array_shift($arrUrl);
		$this->action = $arrUrl[0];
		array_shift($arrUrl);
		$this->query = $arrUrl;
	}
	
	private function LoadController(){
		try{
			if(method_exists($this->controller, $this->action)){
				$o = new $this->controller();
				$o->RunAction($this->action, $this->query);
				$o->Draw();
			}
			else{
				if(!class_exists($this->controller)){
					echo 'Cannot find Controller: ' . $this->controller;
				}
				else{
					echo 'Controller "' . $this->controller . '" does not have action :' . $this->action;
				}
			}
		}
		catch(Exception $e){
			var_dump($e);
		}
	}
	
	public function Draw(){
		echo $this->output;
	}
}
?>