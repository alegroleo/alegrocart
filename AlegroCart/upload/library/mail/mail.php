<?php

class Mail {
	var $charset='UTF-8';
	var $to;
	var $bcc;
	var $from;
	var $sender;
	var $subject;
	var $text;
	var $html;
	var $attachments	= array();
	var $newLine		= "\n";
	var $crlf		= "\r\n";
//	var $tls		= false;
	var $log		='';
	var $log_message	= NULL;

	function __construct(&$locator) {
		$this->locator =& $locator;
		$this->config =& $locator->get('config');
	}

	function setCharacterSet($charset) {
		$this->charset = $charset;
	}

	function setTo($to) {
		$this->to = $to;
	}

	function setBcc($bcc) {
		$this->bcc = $bcc;
	}

	function setFrom($from) {
		$this->from = $from;
	}

	function setSender($sender) {
		$this->sender = $sender;
	}

	function setEmail() {
		$this->email = $email;
	}

	function setSubject($subject) {
		$this->subject = $subject;
	}

	function setText($text) {
		$this->text = $text;
	}

	function setHtml($html) {
		$this->html = $html;
	}

	function setAttachment($attachments) {
		$this->attachments[] = $attachments;
	}

	function get_mx($hostname) {
		if (strpos($hostname,'@')) list($user,$hostname)=explode('@',$hostname); // split hostname from email address
		if (function_exists('getmxrr')) @getmxrr($hostname,$mxhosts,$mxweight); // check for a true MX record
		if (isset($mxhosts) && !empty($mxhosts)) return array_shift($mxhosts); // get the first MX record
		else { // RFC says use the A line if there is no MX
			$ip=gethostbyname($hostname); // get the ip from hostname
			if ($ip != $hostname) { // continue if returned ip not hostname
				$hostname=gethostbyaddr($ip); // get the rdns (real) hostname
				$ip=gethostbyname($hostname); // check the (real) hostname has an A record
					if ($ip != $hostname) return $hostname; // return if returned ip not hostname
			}
		}
		// If all else fails...
		return $hostname;
	}

	function get_message_id ($email) {
		$mx=$this->get_mx($email);
		return md5(uniqid(mt_rand())).'@'.$mx;
	}

	function mime_boundary ($email='') {
		return md5(uniqid(mt_rand() . '-'.$email, true));
	}

	function mime_str_encode($string) {
		if (function_exists('mb_encode_mimeheader')) {
			$string=mb_encode_mimeheader($string, $this->charset, 'B');
		}
		return $string;
	}

	function chunk_prep($chunk) {
		return chunk_split(base64_encode($chunk));
	}

	function error_check() {
		$errors=array();
		if (!$this->to) $errors[]			='"To" not set';
		if (!$this->from) $errors[]			='"From" not set';
		if (!$this->sender) $errors[]			='"Sender" not set';
		if (!$this->subject) $errors[]			='"Subject" not set';
		if ((!$this->text) && (!$this->html)) $errors[]	='"Message" not set';

		if (!empty($errors)) {
			foreach ($errors as $error) {
				$this->log .= 'Error: ' . $error . $this->newLine; }
			exit;
		}
	}

	function send() {
		//TODO: What if the size of the message/attachments exceeds memory? Use filesystem?

		//Internal Error Checking
		$this->error_check();

		$to = is_array($this->to) ? implode($this->to, ',') : $this->to;
		$bcc = is_array($this->bcc) ? implode($this->bcc, ',') : $this->bcc;

		if ($this->config->get('config_email_auth')){
			if($bcc){
				$recipients = array_merge((array)$this->to, (array)$this->bcc);
			} else {
				$recipients = (array)$this->to;
			}
		}
		//subject could contain non ASCII characters, so it must be encoded
		$subject=$this->mime_str_encode($this->subject);

		$boundary = '----=_NextPart_'.$this->mime_boundary($to);

		//sender's name could contain non ASCII characters, so it must be encoded
		$from=$this->mime_str_encode($this->sender).' <'.$this->from.'>';

		$message_id=$this->get_message_id($this->from);

		if ($this->config->get('config_email_auth') != true) {
		// Send the mail
			$headers = $this->create_headers($from, $to, $subject, $message_id, $bcc, $boundary);
			$message = $this->create_message($boundary);
			$params=ini_get('safe_mode')?false:'-f'.$this->from;
			return ($params)?mail($to, $subject, $message, $headers, $params):mail($to, $subject, $message, $headers);
		} else {
			if (!@$smtpConnect  = fsockopen($this->config->get('config_email_host'), $this->config->get('config_email_port'), $errno, $errstr, $this->config->get('config_email_tout') ? $this->config->get('config_email_tout') : 10)) {
			$this->log .= "(EE)  unknown host " . $this->config->get('config_email_host') . ". Error number: " . $errno . ". Error: " . $errstr . $this->newLine;
			} else {
				foreach($recipients as $recipient){
					$boundary = '----=_NextPart_'.$this->mime_boundary($recipient);
					$headers = $this->create_headers($from, $to, $subject, $message_id, $bcc, $boundary);
					$message = $this->create_message($boundary);
					$this->smtpSocket($from, $recipient, $subject, $message, $headers);
				}
			}
		}
		if ($this->config->get('config_email_log')) {
			$this->debug_email();
		}
	}

	function create_headers($from, $to, $subject, $message_id, $bcc, $boundary){

		//According to RFC2822 \r\n should be used
		$headers  = 'From: '. $from . $this->crlf;
		$headers .= 'Reply-To: '. $from . $this->crlf;
		$headers .= 'Return-Path: ' . $this->from . $this->crlf;

		//extra headers in case of authentication
		if($this->config->get('config_email_auth')){
			$headers .= 'To: ' . $to . $this->crlf;
			$headers .= 'Subject: ' . $subject . $this->crlf;
		}

		$headers .= 'DATE: ' . date(DATE_RFC2822) . $this->crlf; 
		$headers .= 'Message-ID: <' . $message_id . '>' . $this->crlf;

		if ($this->bcc && !$this->config->get('config_email_auth')) {
			$headers  .= 'Bcc: ' . $bcc . $this->crlf;
		}

		$headers .= 'X-Mailer: PHP/' . phpversion() . $this->crlf;
		$headers .= 'MIME-Version: 1.0' . $this->crlf;
		$headers .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . $this->crlf;
		$headers .= $this->crlf; // End of headers
		return $headers;
	}

	function create_message($boundary){

			//our courtesy message
			$message  = 'Your email client does not support MIME.'. $this->crlf;

		if (!$this->html) {
			$message .= '--' . $boundary . $this->crlf;
			$message .= 'Content-Type: text/plain; charset="' . $this->charset . '"'.$this->crlf;
			$message .= 'Content-Transfer-Encoding: base64' . $this->crlf;
			$message .= $this->crlf; // End of headers
			$message .= $this->chunk_prep($this->text) . $this->crlf;
		} else {
			$message  = '--' . $boundary . $this->crlf;
			$message .= 'Content-Type: multipart/alternative; boundary="' . $boundary . '_alt"' . $this->crlf;
			$message .= $this->crlf; // End of headers
			$message .= '--' . $boundary . '_alt' . $this->crlf;
			$message .= 'Content-Type: text/plain; charset="' . $this->charset . '"' . $this->crlf; 
			$message .= 'Content-Transfer-Encoding: base64' . $this->crlf;
			$message .= $this->crlf; // End of headers
			$message .= $this->chunk_prep(($this->text)?$this->text:'Your email client does not support HTML email.');
			$message .= $this->crlf;
			$message .= '--' . $boundary . '_alt' . $this->crlf;
			$message .= 'Content-Type: text/html; charset="' . $this->charset . '"' . $this->crlf;
			$message .= 'Content-Transfer-Encoding: base64' . $this->crlf;
			$message .= $this->crlf; // End of headers
			$message .= $this->chunk_prep($this->html);
			$message .= $this->crlf;
			$message .= '--' . $boundary . '_alt' . '--' .$this->crlf;
		}

		if (!empty($this->attachments)) {
			foreach ($this->attachments as $file) {
				if (is_file($file)) {
					$filename = $this->mime_str_encode(basename($file));
					$filetype = (function_exists('mime_content_type'))?mime_content_type($file):'application/octetstream';
					$handle  = fopen($file, 'rb');
					$content = fread($handle, filesize($file));
					fclose($handle);
					$content = $this->chunk_prep($content);
					$message .= '--'. $boundary . $this->crlf;
					$message .= 'Content-Type: ' . $filetype . '; name="' . $filename . '"' . $this->crlf;
					$message .= 'Content-Transfer-Encoding: base64' . $this->crlf;
					$message .= 'Content-Description: ' . $filename . $this->crlf;
					$message .= 'Content-Disposition: attachment; filename="' . $filename . '"' . $this->crlf;
					$message .= 'Content-ID: <' . $filename . '>' . $this->crlf;
					$message .= $this->crlf; // End of headers
					$message .= $content;
				}
			}
		}
		$message  .= '--' . $boundary . '--' . $this->crlf;
		return $message;
	}

	function smtpSocket($from, $to, $subject, $message, $headers){

		//Connect to the host on the specified port
		$smtpConnect  = fsockopen($this->config->get('config_email_host'), $this->config->get('config_email_port'), $errno, $errstr, $this->config->get('config_email_tout') ? $this->config->get('config_email_tout') : 10);
		$smtpResponse = $this->get_return_value($smtpConnect, 'CONNECT');
		if(empty($smtpConnect)){
			$this->log .= "(EE) Failed to connect to " . $this->config->get('config_email_host').$this->newLine;
		} else {
			$this->log .= "(##) Connected to " . $this->config->get('config_email_host') . $this->newLine;
		}

		//Say EHLO to SMTP
		fputs($smtpConnect, "EHLO " . $this->config->get('config_email_lhost') . $this->crlf);
		$errorLog['ehloresponse'] = $this->get_return_value($smtpConnect, 'EHLO');

		if ($errorLog['ehloresponse']) {
		//as EHLO not supported, say HELO and try to send the E-Mail without authentication

			fputs($smtpConnect, "HELO " . $this->config->get('config_email_lhost') . $this->crlf);
			$errorLog['heloresponse'] = $this->get_return_value($smtpConnect, 'HELO');

		} else {
		//EHLO supported, so continue with authetication

		/* no tls handshake added, but for future reference:

		//check whether TLS is supported
		if ($tls=true) {
		//first say STARTTLS
		fputs($smtpConnect, "STARTTLS" . $this->crlf);
		$errorLog['tlsresponse'] = $this->get_return_value($smtpConnect, 'STARTTLS');
		//then resend EHLO to start the communication from the beginning, but this time encrypted
		fputs($smtpConnect, "EHLO " . $this->config->get('config_email_lhost') . $this->crlf);
		$errorLog['ehlowithtlsresponse'] = $this->get_return_value($smtpConnect, 'EHLO');
		}
		*/

		//Request Auth Login
		fputs($smtpConnect, "AUTH LOGIN" . $this->crlf);
		$errorLog['authrequest'] = $this->get_return_value($smtpConnect, 'AUTH LOGIN');

		//Send username
		fputs($smtpConnect, base64_encode($this->config->get('config_email_user')) . $this->crlf);
		$errorLog['authusername'] = $this->get_return_value($smtpConnect, 'USERNAME');

		//Send password
		fputs($smtpConnect, base64_encode($this->config->get('config_email_passw')) . $this->crlf);
		$errorLog['authpassword'] = $this->get_return_value($smtpConnect, 'PASSWORD');
		}

		//Email From
		fputs($smtpConnect, "MAIL FROM: <$this->from>" . $this->crlf);
		$errorLog['mailfromresponse'] = $this->get_return_value($smtpConnect, 'MAIL FROM');

		//Email To
		fputs($smtpConnect, "RCPT TO: $to" . $this->crlf);
		$errorLog['mailtoresponse'] = $this->get_return_value($smtpConnect, 'RCPT TO');

		//The Email
		fputs($smtpConnect, "DATA" . $this->crlf);
		$errorLog['data1response'] = $this->get_return_value($smtpConnect, 'DATA');

		fputs($smtpConnect, "$headers" . $this->crlf.$this->crlf);
		fputs($smtpConnect, "$message" . $this->crlf);
		fputs($smtpConnect, "." . $this->crlf);

		$errorLog['data2response'] = $this->get_return_value($smtpConnect, 'MESSAGE');

		// SMTP Loggin Out
		fputs($smtpConnect,"QUIT" . $this->crlf);
		$errorLog['quitresponse'] = $this->get_return_value($smtpConnect, 'QUIT');
	}

	function get_return_value($smtpConnect, $command){

		$error="";
		$this->log .= 'C: ' . $command . $this->newLine;
		while($smtpResponse = fgets($smtpConnect, 515)){
			$this->log .= 'S: ' . $smtpResponse . $this->newLine;
			if(substr($smtpResponse,3,1) == " ") { break; }
		}

		switch ($command) {

			case "CONNECT":
				break;
			case "EHLO":
				if(substr($smtpResponse,0,3) !== "250") { $error='(EE) EHLO is not supported!'.$this->newLine; }
//				if(strpos($smtpResponse, 'STARTTLS') !==false) { $tls=true;}
				break;
			case "HELO":
				if(substr($smtpResponse,0,3) !== "250") { $error='(EE) HELO is not supported!'.$this->newLine; }
				break;
			/* no tls handshake added
			case "STARTTLS":
				if(substr($smtpResponse,0,3) !== "220") { $error='(EE) STARTTLS is not accepted!'.$this->newLine; }
				break;*/
			case "AUTH LOGIN":
				if(substr($smtpResponse,0,3) !== "334") { $error='(EE) AUTH LOGIN is not accepted!'.$this->newLine; }
				break;
			case "USERNAME":
				if(substr($smtpResponse,0,3) !== "334") { $error='(EE) USERNAME is not accepted!'.$this->newLine; }
				break;
			case "PASSWORD":
				if(substr($smtpResponse,0,3) !== "235") { $error='(EE) PASSWORD is not accepted!'.$this->newLine; }
				break;
			case "MAIL FROM":
				if(substr($smtpResponse,0,3) !== "250") { $error='(EE) MAIL FROM is not accepted!'.$this->newLine; }
				break;
			case "RCPT TO":
				if(substr($smtpResponse,0,3) !== "250") { $error='(EE) RCPT TO is not accepted!'.$this->newLine; }
				break;
			case "DATA":
				if(substr($smtpResponse,0,3) !== "354") { $error='(EE) DATA is not accepted!'.$this->newLine; }
				break;
			case "MESSAGE":
				if(substr($smtpResponse,0,3) !== "250") { $error='(EE) MESSAGE is not accepted for delivery!'.$this->newLine; }
				break;
			case "QUIT":
				if(substr($smtpResponse,0,3) !== "221") { $error='(EE) QUIT is not accepted!'.$this->newLine; }
				break;
		}
		$this->log .= $error;
		return $error;
	}

	function debug_email() {
		$request =&  $this->locator->get('request');
		$controller =& $this->locator->get('controller');
		$class = $controller->getClass($request);
		$email_log = DIR_BASE . 'logs' . D_S . 'email_log' . D_S . date("YmdHis") . '_' . $class  . '.txt';
		if (!$fp = fopen($email_log, 'a+')){ 
			$this->log_message = "Could not open/create file: " . $email_log . " to log error."; $log_error = true;
		}
		if (!fwrite($fp, $this->log)){
			$this->log_message = "Could not log error to file: " . $email_log . " Write Error."; $log_error = true;
		}
		if(!$this->log_message){
			$this->log_message = "Error was logged to file: " . $email_log. ".";
		}
		fclose($fp); 
	}
}
?>
