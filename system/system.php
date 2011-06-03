<?
// Helpers or files to be loaded on all system pages

function system_construct(){
	system_init();
	db_connect();
	
	//load helper to assist with user sessions
	load_helper('session');
	session_start(); 
	
	//load default template and store it into user session
	load_model('global');
	//if(!isset($_SESSION['template'])){
		$_SESSION['template'] = model_exec('global', 'get_setting', array("template"));
	//}
}

function system_destruct(){
	//disconnect from database
	db_disconnect();
}

function system_init(){
	//load default configuration file
	require(BASE_URL . 'system/config.php');

	//load default system libraries
	//url redirector
	require(BASE_URL . 'system/libs/url.php');
	//this library file provides functions to load controllers, models, views and helpers.
	require(BASE_URL . 'system/libs/load.php');
	//database library
	require(BASE_URL . 'system/libs/database.php');

	//load default system helper
	require(BASE_URL . 'system/helpers/default.php');
}

?>