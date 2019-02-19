<!-- booting up the setup -->
<?php

	$SOURCE['site_title'] = "Set A Title";

	$SOURCE['host'] = 'localhost';

	$SOURCE['dbname'] = '';

	$SOURCE['username'] = 'root';

	$SOURCE['password'] = '';

	$SOURCE['base_url'] = 'http://localhost/workspace';

	$SOURCE['bootstrap_css'] = $SOURCE['base_url'] . '/dist/css/bootstrap.min.css';

	$SOURCE['bootstrap_js'] = $SOURCE['base_url'] . '/dist/js/bootstrap.min.js';

	$SOURCE['jquery'] = $SOURCE['base_url'] . '/dist/js/jquery.min.js';

	function e($str){
		$SOURCE = $GLOBALS['SOURCE'];
		echo $SOURCE["$str"];
	}
	

	
	/*

	$url = $_SERVER['REQUEST_URI'];

	$url_new = explode("/", substr($url, 1));

	to access the links

	*/

?>