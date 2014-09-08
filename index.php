<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

$url = $_GET['url'];

require_once(ROOT . DS . 'cfg' . DS . 'config.php');
require_once(ROOT . DS . 'lib' . DS . 'loader.php');

$Loader = new \Loader($url);
$Loader->Draw();
/*
require_once('config.php');

$LoginController = new \controller\Login();
$strHtml = $LoginController->GetHTML();
$strTitle = $LoginController->GetTitle();

$Layout = new \Layout\Layout();
try{
	$Layout->RenderLayout($strHtml, $strTitle);
}
catch (Exception $e){
	if(Config::$boolInDev){
		var_dump($e);
	}
}
*/
?>