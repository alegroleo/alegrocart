<?php // Error Handler
class ErrorHandler{
	var $ErrorCode = array();
	function __construct(&$locator){
		$this->config   =& $locator->get('config');
		$this->mail     =& $locator->get('mail');
		
		$this->ip = $this->config->get('error_developer_ip') ? $this->config->get('error_developer_ip') : $_SERVER['REMOTE_ADDR'];
		$this->show_user = $this->config->get('error_show_user') ? TRUE : FALSE;
		$this->show_developer = $this->config->get('error_show_developer') ? TRUE : FALSE;
		
		$this->email = $this->config->get('config_error_email') ? $this->config->get('config_error_email') : $this->config->get('config_email');
		$log_path = DIR_BASE . 'logs' . D_S . 'error_log' . D_S;
		if (is_writable($log_path)){
			$this->log_file = $log_path . 'error-' . date("Ymd") . '.txt';
		} else {
			$this->log_file = FALSE;
		}
		$this->log_message = NULL;
		$this->email_sent = FALSE;
		
		$this->error_codes =  E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_NOTICE;
		$this->warning_codes =  E_WARNING | E_CORE_WARNING | E_COMPILE_WARNING | E_USER_WARNING;
		$this->error_names = array('E_ERROR',
									'E_WARNING',
									'E_PARSE',
									'E_NOTICE',
									'E_CORE_ERROR',
									'E_CORE_WARNING',
									'E_COMPILE_ERROR','E_COMPILE_WARNING',
									'E_USER_ERROR',
									'E_USER_WARNING',
									'E_USER_NOTICE',
									'E_STRICT',
									'E_RECOVERABLE_ERROR');
		for($i=0,$j=1,$num=count($this->error_names); $i<$num; $i++,$j=$j*2){
			$this->error_numbers[$j] = $this->error_names[$i];
		}
	}
	
	//error handling function...
	function handler($errno, $errstr, $errfile, $errline)
		{
		$this->errno = $errno;
		$this->errstr = $errstr;
		$this->errfile = $errfile;
		$this->errline = $errline;

		if(error_reporting() != 0){
		  
			if($this->log_file){
				$this->log_error_msg();
			}
			if(!isset($this->ErrorCode[$errno]['errorcode'])){
				if ($this->config->get('error_email_status') ) {
					if($this->email){
						$this->send_error_msg();
					}
				}
			}
			if($this->show_user){
				$this->error_msg_user();
			}	
			if($this->show_developer && preg_match("/^$this->ip$/i", $_SERVER['REMOTE_ADDR'])){
				$this->error_msg_developer();
			}
			$this->ErrorCode[$errno] = array('errorcode' => $errno);
			return true;
		}
		
	}
		
	function error_msg_user(){
		$message = NULL;

		if($this->errno & $this->error_codes){
			$message .= "<b>ERROR:</b> There has been an error in the code.";
		}
		if($this->errno & $this->warning_codes){
			$message .= "<b>WARNING:</b> There has been an error in the code.";
		}
		if($message){
			$message .= ($this->email_sent)	? " The developer has been notified.<br />\n" : "<br />\n";
		}
		
		echo $message;
	}
		
	function error_msg_developer(){
		//settings for error display...
		$silent = (2 & $this->show_developer) ? true : false;
			
		switch(true){
			case (16 & $this->show_developer): $color='white'; break;
			case (32 & $this->show_developer): $color='black'; break;
			default: $color='red';
		}
		
		$message =  ($silent)?"<!--\n":'';
		$message .= "<span style='color:$color;font-size: 12px'>";
		$message .= "file: ".print_r( $this->errfile, true);
		$message .= " - line: ".print_r( $this->errline, true)."<br>\n";
		$message .= "code: ".print_r( $this->error_numbers[$this->errno], true);
		$message .= " - message: ".print_r( $this->errstr, true)."<br>\n";
		$message .= isset($_SERVER['REQUEST_URI']) ? 'Path: '. $_SERVER['REQUEST_URI']."<br>\n" : "";
		$message .= "---------------------------------------------------<br>\n";
		$message .= "</span>";
		$message .= ($silent)?"-->\n":'';
			
		echo $message;
	}
		
	function send_error_msg(){		
		$message = "file: ".print_r( $this->errfile, true)."\n";
		$message .= "line: ".print_r( $this->errline, true)."\n";
		$message .= "code: ".print_r( $this->error_numbers[$this->errno], true)."\n";
		$message .= "message: ".print_r( $this->errstr, true)."\n";
		$message .= isset($_SERVER['REQUEST_URI']) ? 'Path: '. @$_SERVER['REQUEST_URI'] . "\n" : "";
		$message .= isset($_SERVER['QUERY_STRING']) ? 'Query String: ' . @$_SERVER['QUERY_STRING'] . "\n" : "";
		$message .= isset($_SERVER['HTTP_REFERER']) ? 'HTTP Referer: ' . @$_SERVER['HTTP_REFERER'] . "\n" : "";
		$message .= 'IP:' . $_SERVER['REMOTE_ADDR'] . ' Remote Host:' . (isset($_SERVER['REMOTE_HOST']) ? @$_SERVER['REMOTE_HOST'] : $this->nslookup($_SERVER['REMOTE_ADDR'])) . "\n";
		$message .= "log: ".print_r( $this->log_message, true)."\n";
		$message .= "##################################################\n\n";
		
		$this->email_sent = false;
		
		$this->mail->setTo($this->email);
		$this->mail->setFrom($this->config->get('config_email'));
		$this->mail->setSender(HTTP_BASE);
		$this->mail->setSubject('ERROR');
		$this->mail->setText($message);
		$this->mail->send();
		
		$this->email_sent = true;

	}
		
	function log_error_msg(){
		$message =  "time: ".date("j-m-d H:i:s (T)", mktime())."\n";
		$message .= "file: ".print_r( $this->errfile, true)."\n";
		$message .= "line: ".print_r( $this->errline, true)."\n";
		$message .= "code: ".print_r( $this->error_numbers[$this->errno], true)."\n";
		$message .= "message: ".print_r( $this->errstr, true)."\n";
		$message .= isset($_SERVER['REQUEST_URI']) ? 'Path: '.$_SERVER['REQUEST_URI']."\n" : "";
		$message .= "##################################################\n\n";

		if (!$fp = fopen($this->log_file, 'a+')){ 
			$this->log_message = "Could not open/create file: $this->log_file to log error."; $log_error = true;
		}
		if (!fwrite($fp, $message)){
			$this->log_message = "Could not log error to file: $this->log_file. Write Error."; $log_error = true;
		}
		if(!$this->log_message){
			$this->log_message = "Error was logged to file: $this->log_file.";
		}
		fclose($fp); 
	}
	function nslookup($ip) {
		$host_name = gethostbyaddr($ip);
		return $host_name;
	}
/*
	show_user values:
	0 -> off (default)
	1 -> on 
	show_developer values:
	0 -> off (default)
	1 -> on 
	2 -> silent
	16 -> font color white (red default)
	32 -> font color black (red default)
	add numbers together for more than one to be turned on e.g: add context + silent = 6
*/
}
?>