<?php
define('DIR_LANG','install/language');
define('D_LANG','english');
define('E_LANG','Error: Could not load language data from %s!');

class language {

var $lang      = array();
var $data      = array();
var $error;

function get_languages(){

$dir_handle = opendir(DIR_BASE.D_S.DIR_LANG);
  if ($dir_handle) {
	  while (false !== ($fname = readdir($dir_handle))) {
		  if (($fname != '.') && ($fname != '..')) {
			$this->langs[]  = substr($fname,0,-4);
			}
	  }
closedir($dir_handle);
  }
}

function check_default(){
  if (!in_array(D_LANG, $this->langs)) { $this->error= DIR_BASE.DIR_LANG.D_S.D_LANG. ".php was not found! (ensure you have uploaded it)";}
}

function load($filename='english') {
$directory = DIR_BASE.DIR_LANG.D_S;		
$_ = array();

		$dfile = $directory.D_LANG.'.php';
		include($dfile); 
		$file = $directory.$filename.'.php';
		if (($dfile != $file) && file_exists($file)) { include($file); }
		if (empty($_)) { echo sprintf(E_LANG,$filename); }
		$this->data = array_merge($this->data, $_);
}

function get($key) {
    	$args = func_get_args();
 
    	if (count($args) > 1) {
      		return vsprintf($this->get(array_shift($args)), $args);
    	} else {
		return $this->data[$key];
	}
}
}
?> 
