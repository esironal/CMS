<?
// Helpers or files to be loaded on all system pages

function system_construct(){
	system_init();
	
	db_connect();
	
	//load helper to assist with user sessions
	load_helper('user');
	load_helper('session');
	session_start(); 
	
	//load default template and store it into user session
	load_model('global');	
	
	//load template from database and load into session to avoid querying database on each call
	//if(!isset($_SESSION['template'])){
		$_SESSION['template'] = model_exec('global', 'get_setting', array("template"));
	//}

	//declare global constants for template paths
	system_define_template();

	//load system debug helper
	load_helper('debugging');	
}

function system_destruct(){
	//disconnect from database
	db_disconnect();
}

function system_init(){
	//load default configuration file
	require(LOCAL_DIR . 'system/config.php');

	//load default system libraries
	//url redirector
	require(LOCAL_DIR . 'system/libs/url.php');
	//this library file provides functions to load controllers, models, views and helpers.
	require(LOCAL_DIR . 'system/libs/load.php');
	//database library
	require(LOCAL_DIR . 'system/libs/database.php');
}

//define global template paths
function system_define_template(){
	define("IMAGE_PATH", BASE_URL ."public/".$_SESSION['template']."/images/");
	define("CSS_PATH", BASE_URL ."public/".$_SESSION['template']."/");
	define("JS_PATH", BASE_URL ."public/".$_SESSION['template']."/js/");
}

?>
