<?php
function getbasepath($basepath='..') {
	if (@chdir($basepath)) { $basepath=getcwd(); }
	@chdir(dirname(__FILE__));
	return add_trailing_slash($basepath,DIRECTORY_SEPARATOR);
}
function getbaseurl($baseurl='') {
	$local = array('127.0.0.1', '::1');
	if(in_array($_SERVER['REMOTE_ADDR'], $local)){ // we are on localhost, www is not needed
		$host = $_SERVER['HTTP_HOST'];
	} else { // add www if subdomain is not used
		preg_match('/(.*?)\.(?=[^\/]*\..{2,5})/i', $_SERVER['HTTP_HOST'], $match); //works in almost all cases
		if(!$match) {
			$host = 'www.' . $_SERVER['HTTP_HOST'];
		} else {
			$host = $_SERVER['HTTP_HOST'];
		}
	}

	$baseurl='http://'.$host.str_replace('/'.basename(dirname($_SERVER['REQUEST_URI'])),'',dirname($_SERVER['REQUEST_URI']));
	return add_trailing_slash($baseurl);
}
function add_trailing_slash($path,$slash='/') {
	return (substr($path, -1) != $slash)?$path.$slash:$path;
}
?>
