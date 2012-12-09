<?php
class Session {
	var $expire = 1800;
	private $session_id = '';
	private $user_agent = '';
	private $session_data = array();
	private $bots = array("Teoma", "alexa","froogle","inktomi","looksmart","URL_Spider_SQL","Firefly","NationalDirectory","Ask Jeeves","TECNOSEEK","InfoSeek","WebFindBot","girafabot","crawler","Googlebot","Scooter","Slurp","appie","FAST","WebBug","Spade","ZyBorg","Baiduspider","bingbot","Ezooms","YodaoBot","spider");

  	function __construct($locator) {
		$this->database = $locator->get('database');
		$this->request  = $locator->get('request');
		$this->config   = $locator->get('config');

    	register_shutdown_function(array($this, 'save_session'));
		$this->expire = ($this->config->get('config_session_expire') > 1800) ? $this->config->get('config_session_expire') : 1800;
		if (!$this->request->has('alegro', 'cookie') && $this->request->isPost()) {
			$this->log_access();
			if(strtolower(@$_SERVER['HTTP_CONNECTION']) == 'keep-alive'){
				echo $this->close_connection();
				exit;
			}
			if($this->check_access()){
				echo $this->close_connection();
				exit;
			}
		}
		
		if (!$this->request->has('alegro', 'cookie')) {
	    	setcookie('alegro', 'accept', time() + 60 * 60 * 24 * 30, '/', NULL, false);
		}
		
		if ($this->request->has('alegro', 'cookie')) {
			$this->start_session();
  		}
			
	}
	
	function save_session(){
		if(!$this->check_bots()){
			$this->write($this->session_id);
		}
		$this->clean();
	}
	private function start_session(){
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if (!$this->request->has('alegro_sid', 'cookie')) {
			$this->CreateSID();
			setcookie('alegro_sid', $this->session_id . '_' . md5($this->user_agent), 0, '/', NULL, false);
		
		} else {
			$cookie = explode('_', $this->request->get('alegro_sid','cookie'));
			if(!isset($cookie[1]) || $cookie[1] != md5($this->user_agent)){
				echo $this->close_connection();
				exit;
			}
			$this->session_id = $cookie[0]; 
			$this->read($this->session_id);
		}
	}
	private function check_bots(){
		foreach($this->bots as $bot){
			if(stristr($this->user_agent,$bot)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	private function CreateSID(){
		$this->session_id = md5(time()-rand(10000,100000));
	}
	
	private function check_access(){
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
	
	private function log_access(){
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
	
	private function close_connection(){
		setcookie('alegro_sid','', time() - 86400 * 2,'/');
		setcookie('currency','', time() - 86400 * 2, '/');
		setcookie('catalog_language','', time() - 86400 * 2,'/');
		setcookie('admin_language','', time() - 86400 * 2,'/');
		setcookie('alegro','',time() - 86400 * 2,'/');
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
		$this->session_data[$key] = $value;
	}
	
	function get($key) {
		return (isset($this->session_data[$key]) ? $this->session_data[$key] : NULL);
	}
		
	function has($key) {
		return isset($this->session_data[$key]);
	}

	function delete($key) {
		if (isset($this->session_data[$key])) {
			unset($this->session_data[$key]);
		}
	}
	
  	function open() {
    	return TRUE;
  	}

  	function close() {
		return TRUE;
  	}

  	private function read($session_id) {
    	$result = $this->database->getRow($this->database->parse("select value from session where session_id = '?' and expire > '?'", $session_id, time()));
		if(isset($result['value']) && $result['value']){
			$this->session_data = unserialize($result['value']);
		}
  	}

  	private function write($session_id) {
		if (!$this->database->getRow($this->database->parse("select * from session where session_id = '?'", $session_id))) {
	  		$sql = "insert into session set session_id = '?', expire = '?', `value` = '?', ip = '?', time = now(), url = '?'";
      		$this->database->query($this->database->parse($sql, $session_id, time() + $this->expire, serialize($this->session_data), isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'', isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:''));
		} else {
      		$sql = "update session set expire = '?', `value` = '?', ip = '?', time = now(), url = '?' where session_id = '?'";
      		$this->database->query($this->database->parse($sql, time() + $this->expire, serialize($this->session_data), isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'', isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'', $session_id));
		}
  	}

  	function destroy($session_id) {
		$this->database->query($this->database->parse("delete from session where session_id = '?'", $session_id));
		$this->session_data = array();
  	}

  	function clean() {
    	$this->database->query($this->database->parse("delete from session where expire < '?'", time()));
  	}
}
?>