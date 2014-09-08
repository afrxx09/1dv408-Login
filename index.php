<?php
session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__));
define('ROOT_PATH', '/' . basename(dirname(__FILE__)) . '/');

require_once(ROOT_DIR . DS . 'cfg' . DS . 'config.php');
require_once(ROOT_DIR . DS . 'lib' . DS . 'loader.php');

try{
	$url = isset($_GET['url']) ? $_GET['url'] : '';
	$Loader = new \Loader($url);
	$Layout = new \Layout\Layout();
	$Layout->RenderLayout($Loader->GetBody());
}
catch(Exception $e){
	if(IN_DEVELOPMENT){
		var_dump($e);
	}
	else{
		echo '404 page not found';
	}
}


?>