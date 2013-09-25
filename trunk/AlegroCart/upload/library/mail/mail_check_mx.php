<?php
class mail_check_mx {
	var $system_OS	= 'win';
	var $recType	= "MX";
	var $email;
	var $hostName;
	var $userName;
	var $email_log_text;
	var $log_message = NULL;

	function __construct(&$locator){
		$this->locator	=& $locator;
		$this->config	=& $locator->get('config');

		$this->system_OS = strtoupper(substr(PHP_OS, 0, 3)) == "WIN" ? 'win' : 'linux';
	}
	function checkDNS() {
		if ($this->system_OS == "linux") {
			if (@getmxrr($this->hostName, $mxhosts)) return TRUE;
				else return FALSE;
			} else {
					if(!empty($this->hostName)) {
						@exec("nslookup -type=".$this->recType." ".$this->hostName, $result);
						if (preg_replace('#\s#','',$result[0]) == 'Server:UnKnown') {return true;}
						foreach ($result as $line) {
							if(preg_match('/^'.$this->hostName.'/i',$line)) return true; 
							}
						return false; 
					}
					return false; 
					}
	}
	function check_email_dns() {
		list($this->userName, $this->hostName) = explode("@", $this->email);
		if (!$this->checkDNS ($this->hostName)) {
			$this->email_log_text .= "Address domain MX DNS record could NOT be found"."\n";
			return FALSE;
			} else {
				$this->email_log_text .= "Address DNS MX is OK"."\n";
				return TRUE;
			}
	}
	function check_email() {
		$this->email = strtolower($this->email);
		if (preg_match('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i', $this->email)) {
			$this->email_log_text .= "Address syntax is OK"."\n";
			return TRUE;
		} else{
			$this->email_log_text .= "Address syntax is WRONG"."\n";
			return FALSE;
		}
	}
	function final_mail_check($email_address) {
		$this->email = $email_address;
		$this->email_log_text = "time: ".date("j-m-d H:i:s (T)", time())."\n";
		if(isset($_SERVER['REQUEST_URI'])) {
			$this->email_log_text .= "Path: ".$_SERVER['REQUEST_URI']."\n";
		}
		$this->email_log_text .= "Affected E-Mail Address: ".$this->email."\n";

		if (!$this->check_email() || !$this->check_email_dns()) {
			if ($this->config->get('config_email_log')) {
				$this->debug_address();
			}
			return FALSE;
		} else {
			if ($this->config->get('config_email_log')) {
				$this->debug_address();
			}
			return TRUE;
		}
	}
	function debug_address() {
		$request =&  $this->locator->get('request');
		$controller =& $this->locator->get('controller');
		$class = $controller->getClass($request);
		$email_log = DIR_BASE . 'logs' . D_S . 'email_log' . D_S . date("YmdHis") . '_' . $class  . '.txt';
		if (!$fp = fopen($email_log, 'a+')){ 
			$this->log_message = "Could not open/create file: " . $email_log . " to log error."; $log_error = true;
		}
		if (!fwrite($fp, $this->email_log_text)){
			$this->log_message = "Could not log error to file: " . $email_log . " Write Error."; $log_error = true;
		}
		if(!$this->log_message){
			$this->log_message = "Error was logged to file: " . $email_log. ".";
		}
		fclose($fp); 
	}
}
?>
