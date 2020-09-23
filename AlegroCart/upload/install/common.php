<?php

function getbasepath($basepath='..') {
	if (@chdir($basepath)) {
		$basepath=getcwd();
	}
	@chdir(dirname(__FILE__));
	return add_trailing_slash($basepath, DIRECTORY_SEPARATOR);
}

function getHost($baseurl='') {
	$local = array('127.0.0.1', '::1'); //ipv4 and ipv6
	if(in_array($_SERVER['REMOTE_ADDR'], $local)){ // we are on localhost, www is not needed
		$host = $_SERVER['HTTP_HOST'];
	} else { // add www if subdomain is not used
		preg_match('/(.*?)\.(?=[^\/]*\..{2,5})/i', $_SERVER['HTTP_HOST'], $match); //works in almost all cases
		if(!$match) {
			$host = 'www.' . $_SERVER['HTTP_HOST']; //this is prefered; avoid naked domain
		} else {
			$host = $_SERVER['HTTP_HOST'];
		}
	}
	return $host;
}

function getbaseurl($host) {
	$baseurl = $host.preg_replace('/\/'.basename(dirname($_SERVER['REQUEST_URI'])).'$/', '', dirname($_SERVER['REQUEST_URI']));
	return add_trailing_slash($baseurl);
}

function add_trailing_slash($path, $slash='/') {
	return (substr($path, -1) != $slash) ? $path.$slash : $path;
}

function isSecure() { //check for SSL
		return ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? TRUE : FALSE;
	}

function checkSSL() { //check SSL based on real connection
	if (!$SSL = @fsockopen('ssl://' . $_SERVER['HTTP_HOST'], 443, $errno, $errstr, 30)) {
		return FALSE;
	} else {
		fclose($SSL);
		return TRUE;
	}
}

function checkWWW(){ //check if url is not naked
	preg_match('/(.*?)\.(?=[^\/]*\..{2,5})/i', $_SERVER['HTTP_HOST'], $match);
	return $match ? TRUE : FALSE;
}
?>
