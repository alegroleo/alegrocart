<?php
final class Request {

	public function get($key, $type = 'GET', $default = NULL) {
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

	public function gethtml($key, $type = 'GET', $default = NULL) {
		return htmlspecialchars_deep($this->get($key,$type,$default));
	}

	public function sanitize($key, $type = 'GET', $default = NULL){
		$str = $this->get($key,$type,$default);
		return htmlspecialchars_deep($this->sanitizer($str));
	}

	public function clean($key){
		return htmlspecialchars_deep($this->sanitizer($key));
	}

	public function has($key, $type = 'GET') {
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

	public function set($key, $value, $type = 'GET') {
		switch (strtoupper($type)) {
			case 'GET':
				return $_GET[$key] = $value;
				break;
			case 'POST':
				return $_POST[$key] = $value;
				break;
		}
	}

	public function isPost() {
		return (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST');
	}

	public function isSecure() { //centralised check for SSL based on environment information; this will be used everywhere
		return ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? TRUE : FALSE;
	}

	public function checkSSL() { //check SSL based on real connection
		if (!$SSL = @fsockopen('ssl://' . $_SERVER['HTTP_HOST'], 443, $errno, $errstr, 30)) {
			return FALSE;
		} else {
			fclose($SSL);
			return TRUE;
		}
	}

	public function sanitizer($searchstring){
		$searchstring = trim($searchstring);
		$searchstring = str_replace("&", "&amp;", $searchstring);
		$searchstring = preg_replace('/javascript/i', '', $searchstring );
		$searchstring = preg_replace('~(\r\n|\r|\n|%0a|%0d|%0D|%0A|http://|ftp://|%|www.)~','', $searchstring);
		$str = preg_replace ( '/\s*=\s*/', '=', $searchstring );
		$searchstring = stripslashes( preg_replace("/(onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|onfocus|onblur|onabort|onerror|onload)/i" , 'forbidden' , $str ) );
		$searchstring = strip_tags($searchstring);
		return $searchstring;
	}

}
?>
