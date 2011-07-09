<?php
class Session {
	var $expire = 3600;
  	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->request  =& $locator->get('request');
		
    	session_set_save_handler(array(&$this, 'open'), 
	    	                     array(&$this, 'close'), 
	 					         array(&$this, 'read'), 
			 					 array(&$this, 'write'), 
								 array(&$this, 'destroy'), 
								 array(&$this, 'clean')); 
 
    	register_shutdown_function('session_write_close');

		if (!$this->request->has('test', 'cookie') && $this->request->isPost()) {
			$this->log_access();
			if(strtolower($_SERVER['HTTP_CONNECTION']) == 'keep-alive'){
				echo $this->close_connection();
				exit;
			}
			if($this->check_access()){
				echo $this->close_connection();
				exit;
			}
		}
		
		if (!$this->request->has('test', 'cookie')) {
	    	setcookie('test', 'accept', time() + 60 * 60 * 24 * 30, '/', NULL, false);
		}
		
		if ($this->request->has('test', 'cookie')) {
			session_set_cookie_params(0, '/');
	  		ini_set('session.hash_function', '0');
			session_start();
  		}
	}
	
	function check_access(){
		$contents = file_get_contents($this->log_file);
		$contents = preg_split("#((\r(?!\n))|((?!\r)\n)|(\r\n))#",$contents);
		$access_count = 0;
		$time_check = date("Y-m-d H:i:s", time()-300);
		foreach($contents as $content){
			$line = explode(',', $content);
			if($line[0] == $_SERVER['REMOTE_ADDR'] && $line[1] > $time_check){
				$access_count ++;
			}
			if($access_count > 20){return TRUE;}
		}
		return FALSE;
	}
	
	function log_access(){
		$log_path = DIR_BASE . 'logs' . D_S . 'access_log' . D_S;
		if (is_writable($log_path)){
			$this->log_file = $log_path . 'access-' . date("Ymd") . '.txt';
		} else {
			$this->log_file = FALSE;
		}
		$access = $_SERVER['REMOTE_ADDR'].',';
		$access .= date("Y-m-d H:i:s", time())."\n";
		if ($fp = fopen($this->log_file, 'a+')){ 
			fwrite($fp, $access);
			fclose($fp); 
		}
	}
	
	function close_connection(){
		header("Connection: close\r\n");
		header("Content-Encoding: none\r\n");
		$error_response = "Invalid Request!" . "\n";
		$error_response .= 'IP:' . $_SERVER['REMOTE_ADDR'] . ' Remote Host:' . (isset($_SERVER['REMOTE_HOST']) ? @$_SERVER['REMOTE_HOST'] : $this->nslookup($_SERVER['REMOTE_ADDR'])) . "\n";
		return $error_response;
	}
	
	function nslookup($ip) {
		$host_name = gethostbyaddr($ip);
		return $host_name;
	}
		
	function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	function get($key) {
		return (isset($_SESSION[$key]) ? $_SESSION[$key] : NULL);
	}
		
	function has($key) {
		return isset($_SESSION[$key]);
	}

	function delete($key) {
		if (isset($_SESSION[$key])) {
			unset($_SESSION[$key]);
		}
	}
	
  	function open() {
    	return TRUE;
  	}

  	function close() {
		return TRUE;
  	}

  	function read($session_id) {
    	$result = $this->database->getRow($this->database->parse("select value from session where session_id = '?' and expire > '?'", $session_id, time()));
	
		return (isset($result['value']) ? $result['value'] : NULL); 
  	}

  	function write($session_id, $data) {
		if (!$this->database->getRow($this->database->parse("select * from session where session_id = '?'", $session_id))) {
	  		$sql = "insert into session set session_id = '?', expire = '?', `value` = '?', ip = '?', time = now(), url = '?'";
      		$this->database->query($this->database->parse($sql, $session_id, time() + $this->expire, $data, isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'', isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:''));
		} else {
      		$sql = "update session set expire = '?', `value` = '?', ip = '?', time = now(), url = '?' where session_id = '?'";
      		$this->database->query($this->database->parse($sql, time() + $this->expire, $data, isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'', isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'', $session_id));
		}

    	return $this->database->countAffected();
  	}

  	function destroy($session_id) {
		$this->database->query($this->database->parse("delete from session where session_id = '?'", $session_id));
		//See http://forum.opencart.com/index.php?topic=1373.0
		session_destroy();
    	return $this->database->countAffected();
  	}

  	function clean($maxlifetime) {
    	$this->database->query($this->database->parse("delete from session where expire < '?'", time()));

    	return $this->database->countAffected();
  	}
}
?>
