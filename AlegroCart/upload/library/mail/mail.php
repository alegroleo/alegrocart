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
  	var $attachments = array();

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
		return md5(uniqid(mt_rand().'-'.$email, true));
	}

	function mime_str_encode($string) {
		if (function_exists('mb_encode_mimeheader')) { $string=mb_encode_mimeheader($string, $this->charset, 'B'); }
		return $string;
	}

	function chunk_prep($chunk) {
		return chunk_split(base64_encode($chunk));
	}

	function error_check() {
		$errors=array();
    	if (!$this->to) $errors[]='"To" not set';
    	if (!$this->from) $errors[]='"From" not set';
    	if (!$this->sender) $errors[]='"Sender" not set';
		if (!$this->subject) $errors[]='"Subject" not set';
		if ((!$this->text) && (!$this->html)) $errors[]='"Message" not set';
		
		if (!empty($errors)) {
			foreach ($errors as $error) { echo 'Error: '.$error."<br>\n"; }
			exit;
		}
	}

	function send() {
		//TODO: What if the size of the message/attachments exceeds memory? Use filesystem?

		//Internal Error Checking
		$this->error_check();

		$eol=defined('PHP_EOL')?PHP_EOL:"\n";

		//if (is_array($this->to)) $this->to=implode($this->to, ',');
      	//$to=$this->to;
		$to = is_array($this->to) ? implode($this->to, ',') : $this->to;
		$bcc = is_array($this->bcc) ? implode($this->bcc, ',') : $this->bcc;
		
		$subject=$this->mime_str_encode($this->subject);

		$boundary = '----=_NextPart_'.$this->mime_boundary($to);
		$from=$this->mime_str_encode($this->sender).' <'.$this->from.'>';

		$message_id=$this->get_message_id($this->from);

		$headers  = 'From: '.$from.$eol;
    	$headers .= 'Reply-To: '.$from.$eol;
		$headers .= 'Message-ID: <'.$message_id.'>'.$eol;
		if ($this->bcc) {
			$headers  .= 'Bcc: ' . $bcc . $eol;
		}
    	$headers .= 'X-Mailer: PHP/'.phpversion().$eol;
    	$headers .= 'MIME-Version: 1.0'.$eol;
    	$headers .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
		$headers .= $eol; // End of headers

		if (!$this->html) {
	  		$message  = '--'.$boundary.$eol;
	  		$message .= 'Content-Type: text/plain; charset="'.$this->charset.'"'.$eol;
	  		$message .= 'Content-Transfer-Encoding: base64'.$eol;
			$message .= $eol; // End of headers
      		$message .= $this->chunk_prep($this->text);
		} else {
	  		$message  = '--'.$boundary.$eol;
	  		$message .= 'Content-Type: multipart/alternative; boundary="'.$boundary.'_alt"'.$eol;
			$message .= $eol; // End of headers
	  		$message .= '--'.$boundary.'_alt'.$eol;
	  		$message .= 'Content-Type: text/plain; charset="'.$this->charset.'"'.$eol; 
	  		$message .= 'Content-Transfer-Encoding: base64'.$eol;
			$message .= $eol; // End of headers
	    	$message .= $this->chunk_prep(($this->text)?$this->text:'Your email client does not support HTML email.');

			$message .= '--'.$boundary.'_alt'.$eol;
      		$message .= 'Content-Type: text/html; charset="'.$this->charset.'"'.$eol;
      		$message .= 'Content-Transfer-Encoding: base64'.$eol;
			$message .= $eol; // End of headers
	  		$message .= $this->chunk_prep($this->html);
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
					$message .= '--'.$boundary.$eol;
					$message .= 'Content-Type: '.$filetype.'; name="'.$filename.'"'.$eol;
					$message .= 'Content-Transfer-Encoding: base64'.$eol;
					$message .= 'Content-Description: '.$filename.$eol;
					$message .= 'Content-Disposition: attachment; filename="'.$filename.'"'.$eol;
					$message .= 'Content-ID: <'.$filename.'>'.$eol;
					$message .= $eol; // End of headers
					$message .= $content;
				}
			}
		}

		// Send the mail
		$params=ini_get('safe_mode')?false:'-f'.$this->from;
		return ($params)?mail($to, $subject, $message, $headers, $params):mail($to, $subject, $message, $headers);
	} 
}
?>