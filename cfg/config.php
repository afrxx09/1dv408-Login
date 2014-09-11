<?php

define('IN_DEVELOPMENT', true);

define('APPLICATION_HTML_STANDARD', 'strict');
define('APPLICATION_TITLE', 'Log in');

define('DEFAULT_CONTROLLER', 'Session');
define('DEFAULT_ACTION', 'NewSession');

/*
	Shortcuts to commonly used directories
*/
define('APP_DIR', ROOT_DIR . 'app' . DS);
define('LIB_DIR', ROOT_DIR . 'lib' . DS);
define('PUB_DIR', ROOT_DIR . 'pub' . DS);

define('CSS_DIR', PUB_DIR . 'css' . DS);
define('JS_DIR', PUB_DIR . 'javascript' . DS);

/*
	Shortcuts to commonly used paths
*/
define('APP_PATH', ROOT_PATH . 'app' . '/');
define('LIB_PATH', ROOT_PATH . 'lib' . '/');
define('PUB_PATH', ROOT_PATH . 'pub' . '/');

define('CSS_PATH', PUB_PATH . 'css/');
define('JS_PATH', PUB_PATH . 'javascript/');


class Config{
	public static $DefaultCssFiles = array(
		'style.css'
	);

	public static $DefaultJavascriptFiles = array(
		'jquery-1.11.1.min.js',
		'application.js'
	);
}


?>