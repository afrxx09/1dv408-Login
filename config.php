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
	$strDirPath = Config::GetNamespacePath($strNamespace);
	$strFilePath = $strDirPath . $strClassName . '.php';
	if(file_exists($strFilePath)){
		require_once($strFilePath);
		//throw new \Exception('Could not require file: "' . $strFilePath . '". File does not exsist.');
	}
	
}

spl_autoload_register('AutoLoadClasses');

/*
*	Generic Configuration class with static attributes and methods for applicationwide access.
*/
class Config{
	public static $boolInDev = true;
	
	public static $strCssDir = 'layout/css/';
	public static $strJavascriptDir = 'layout/javascript/';
	public static $strViewDir = 'app/views/';
	public static $strHelperDir = 'app/helpers/';
	public static $strControllerDir = 'app/controllers/';
	public static $strModelDir = 'app/models/';
	public static $strLayoutDir = 'layout/';
	
	public static function GetNamespacePath($strNamespace){
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
				$strReturn = '';
				break;
		}
		return $strReturn;
	}
}

?>