<?php
/*
*	Loads class files automaticly with help of namespaces
*	Inspiration from: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
*/
function AutoLoadClasses($class){
	$class = ltrim($class, '\\');
	
	$strNamespace = '';
	$strClassName = '';
	$strFilePath = '';
	$intSeparatorPos = strpos($class, '\\');
	if($intSeparatorPos !== false){
		$strNamespace = strToLower(substr($class, 0, $intSeparatorPos));
		$strClassName = strToLower(substr($class, $intSeparatorPos + 1));
		$strFilePath = APP_DIR . $strNamespace . 's' . DS . str_replace($strNamespace, '', $strClassName) . '.php';
	}
	else{
		$strClassName = strToLower($class);
		$strFilePath = LIB_DIR . $strClassName . '.php';
	}
	
	if(!file_exists($strFilePath)){
		return false;
	}
	require_once($strFilePath);
	
	/*
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
	*/
}

spl_autoload_register('AutoLoadClasses');

class Loader{
	private $strUrl;
	
	private $controller;
	
	private $strControllerName;
	private $strAction;
	private $arrArgs = array();
	
	private $strBody;
	
	public function __construct(){
		$this->strUrl = isset($_GET['url']) ? $_GET['url'] : '';
		$this->SplitUrlComponents();
		$this->SetupController();
	}
	
	private function SplitUrlComponents(){
		$arrUrl = explode('/', $this->strUrl);
		
		$this->strControllerName = (isset($arrUrl[0]) && $arrUrl[0] !== '') ? '\controller\\' . $arrUrl[0] . 'controller' : DEFAULT_CONTROLLER;
		$this->strAction = (isset($arrUrl[1]) && $arrUrl[1] !== '') ? $arrUrl[1] : 'index';
		
		for($i = 2; $i < count($arrUrl); $i ++){
			$this->arrArgs[] = $arrUrl[$i];
		}
	}
	
	private function SetupController(){
		if(class_exists($this->strControllerName)){
			
			$this->controller = new $this->strControllerName();
			$this->strAction = (method_exists($this->controller, $this->strAction)) ? $this->strAction : $this->controller->GetDefaultAction();
			
			if(method_exists($this->controller, $this->strAction)){
				$this->strBody = call_user_func_array(array($this->controller, $this->strAction), $this->arrArgs);
				$this->RenderPage();
			}
			else{
				//Proper 404 later
				throw new \Exception('Can not find Action: ' . $this->strAction . ' in Controller: ' . $this->strControllerName);
			}
			
		}
		else{
			//Proper 404 later
			throw new \Exception('Can not find Controller: ' . $this->strControllerName);
		}
	}
	
	private function RenderPage(){
		$Layout = new \Layout();
		$Layout->SetBody($this->strBody);
		$Layout->PrintLayout();
	}
	
}
?>