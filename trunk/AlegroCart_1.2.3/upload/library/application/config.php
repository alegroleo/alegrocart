<?php
class Config {
	var $data = array();
		    
	function set($key, $value) {
    	$this->data[$key] = $value;
  	}
  
  	function get($key) {
    	return (isset($this->data[$key]) ? $this->data[$key] : NULL);
  	}
  	
	function has($key) {
    	return isset($this->data[$key]);
  	}
	    
  	function load($filename) {
		$file = DIR_CONFIG . $filename;
		
    	if (file_exists($file)) { 
	  		$cfg = array();
	  
	  		include($file);
	  
	  		$this->data = array_merge($this->data, $cfg);
		}
  	}
}
?>