<?php
/*
*
*	Loader(or Routing) class.
*	Every request(or page-load) goes through this Loader class from index.php.
*	It interprets the request to determine what controller to use and what function to run in that controller.
*	
*	It requires mod_rewrite in apaches httpd.conf to be enabled. This so that .htaccess can rewrite urls like this:
*	/index.php?c=UserController&Action=Save&UserId=5
*	to:
*	/User/save/5
*
*	With this, URLs can easily be split into controller, action and arguments.
*	Checks are performed to make sure there is a controller class and action-function.
*	
*/
class Loader{
	private $strUrl;
	
	private $controller;
	
	private $strControllerName;
	private $strAction;
	private $arrArgs = array();
	
	public function __construct(){
		$this->strUrl = isset($_GET['url']) ? $_GET['url'] : '';
		$this->SplitUrlComponents();
		$this->SetupController();
	}
	
	//Split URL to get controller, action and arguments
	private function SplitUrlComponents(){
		$arrUrl = explode('/', $this->strUrl);
		
		$this->strControllerName = (isset($arrUrl[0]) && $arrUrl[0] !== '') ? '\controller\\' . $arrUrl[0] . 'controller' : DEFAULT_CONTROLLER;
		$this->strAction = (isset($arrUrl[1]) && $arrUrl[1] !== '') ? $arrUrl[1] : 'index';
		
		for($i = 2; $i < count($arrUrl); $i ++){
			$this->arrArgs[] = $arrUrl[$i];
		}
	}
	
	/*
	*	Confirm class and action-function. If passed, runs the controller class and it's function and sends the arguments as parameters to it.
	*	A public controller function like this always returns output to be presented to the user(mostly html-code).
	*	That resulting output is passed on to a Render-function, that uses a Layout class to render the complete page for the user.
	*/
	private function SetupController(){
		if(class_exists($this->strControllerName)){
			
			$this->controller = new $this->strControllerName();
			$this->strAction = (method_exists($this->controller, $this->strAction)) ? $this->strAction : $this->controller->GetDefaultAction();
			
			if(method_exists($this->controller, $this->strAction)){
				$strBody = call_user_func_array(array($this->controller, $this->strAction), $this->arrArgs);
				$this->RenderPage($strBody);
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
	
	/*
	*	Simply creates a Layout object, adds the result from the controller as content(body) and prints it
	*	Check /lib/layout.php for details on how this class works
	*/
	private function RenderPage($strBody){
		$Layout = new \Layout();
		$Layout->SetBody($strBody);
		$Layout->PrintLayout();
	}
	
}

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
}

spl_autoload_register('AutoLoadClasses');
?>