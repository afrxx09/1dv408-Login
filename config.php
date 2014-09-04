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
	$strDirPath = Config::GetClassPath($strNamespace);
	$strFilePath = $strDirPath . $strClassName . '.php';
	
	require_once($strFilePath);
}

spl_autoload_register('AutoLoadClasses');

/*
*	Generic Configuration class with static attributes and methods for applicationwide access.
*/
class Config{
	public static $strCssDir = 'layout/css/';
	public static $strJavascriptDir = 'layout/javascript/';
	public static $strViewDir = 'app/views/';
	public static $strHelperDir = 'app/helpers/';
	public static $strControllerDir = 'app/controllers/';
	public static $strModelDir = 'app/models/';
	public static $strLayoutDir = 'layout/';
	
	public static function GetClassPath($strNamespace){
		$strReturn = '';
		switch($strNamespace){
			case 'view':
				$strReturn = self::$strViewDir;
				break;
			case 'helper':
				$strReturn = self::$strHelperDir;
				break;
			case 'controller':
				$strReturn = self::$strControllerDir;
				break;
			case 'model':
				$strReturn = self::$strModelDir;
				break;
			case 'layout':
				$strReturn = self::$strLayoutDir;
				break;
			default:
				$strReturn = '/';
				break;
		}
		return $strReturn;
	}
}

?>