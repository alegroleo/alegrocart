<?php
function getbasepath($basepath='..') {
	if (@chdir($basepath)) { $basepath=getcwd(); }
	@chdir(dirname(__FILE__));
	return add_trailing_slash($basepath,DIRECTORY_SEPARATOR);
}
function getbaseurl($baseurl='') {
	$baseurl='http://'.$_SERVER['HTTP_HOST'].str_replace('/'.basename(dirname($_SERVER['REQUEST_URI'])),'',dirname($_SERVER['REQUEST_URI']));
	return add_trailing_slash($baseurl);
}
function add_trailing_slash($path,$slash='/') {
	return (substr($path, -1) != $slash)?$path.$slash:$path;
}
?>
