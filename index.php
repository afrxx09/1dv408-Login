<?php

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

?>