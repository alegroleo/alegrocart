<?php
class Request {					        
  	function get($key, $type = 'GET', $default = NULL) {
		switch (strtoupper($type)) {
			case 'GET':
				return (isset($_GET[$key]) ? $_GET[$key] : $default);
				break;
			case 'POST':
				return (isset($_POST[$key]) ? $_POST[$key] : $default);
				break;
			case 'COOKIE':
				return (isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default);
				break;
		}
	}

  	function gethtml($key, $type = 'GET', $default = NULL) {
		return htmlspecialchars_deep($this->get($key,$type,$default));
	}
    
	function sanitize($key, $type = 'GET', $default = NULL){
		$str = $this->get($key,$type,$default);
		return htmlspecialchars_deep($this->sanitizer($str));
	
	}
	
	function clean($key){
		return htmlspecialchars_deep($this->sanitizer($key));
	}
	
	function has($key, $type = 'GET') {
		switch (strtoupper($type)) {
			case 'GET':
				return isset($_GET[$key]);
				break;
			case 'POST':
				return isset($_POST[$key]);
				break;
			case 'COOKIE':
				return isset($_COOKIE[$key]); 
				break;
		}	
	}
	
	function set($key, $value, $type = 'GET') {
		switch (strtoupper($type)) {
			case 'GET':
				return $_GET[$key] = $value;
				break;
			case 'POST':
				return $_POST[$key] = $value;
				break;
		}	
	}
	
	function isPost() {
		return (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
	}

	function isSecure() {
		return (isset($_SERVER['HTTPS']))?true:false;
	}
	function sanitizer($searchstring){
		$searchstring = preg_replace('/javascript/i', '', $searchstring );
		$searchstring = preg_replace('~(\r\n|\r|\n|%0a|%0d|%0D|%0A|http://|ftp://|%|www.)~','', $searchstring);
		$str = preg_replace ( '/\s*=\s*/', '=', $searchstring );
		$searchstring = stripslashes( preg_replace("/(onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|onfocus|onblur|onabort|onerror|onload)/i" , 'forbidden' , $str ) );
		$searchstring = strip_tags($searchstring);
    return $searchstring;
}
}
?>