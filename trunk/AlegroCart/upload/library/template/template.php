<?php
class Template {
  	var $data = array();
	var $directory;
	function __construct($directory, $styles, $color, $columns) {
		$this->directory = $directory;
		$this->set('template',$directory);
		$this->style = $styles;
		$this->color = $color;
		$this->page_columns = $columns;
		
	}
	    
  	function set($key, $value = NULL) {
    	if (!is_array($key)) {
      		$this->data[$key] = $value;
    	} else {
	  		$this->data = array_merge($this->data, $key);
		}
  	}

  	function fetch($filename, $directory = DIR_TEMPLATE) {
    	$file = $directory . $this->directory . '/' . $filename;
    
		if (file_exists($file)) {
      		extract($this->data);

      		ob_start();
      
	  		include($file);
      
	  		$content = ob_get_contents();

      		ob_end_clean();

      		return $content;
    	} else {
      		exit('Error: Template ' . $file . ' not found!');
    	}
  	}
}
?>