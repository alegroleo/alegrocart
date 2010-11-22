<?php
class Download {
	var $mime     = 'application/octet-stream';
	var $encoding = 'binary';
	var $source;
	var $filename;
	var $error;

	function setMime($mime) {
		$this->mime = $mime;
	}

	function setEncoding($encoding) {
		$this->encoding = $encoding;
	}

	function setSource($source) {
		$this->source = $source;
	}

	function setFilename($filename) {
		$this->filename = basename($filename);
	}

	function output() {
		if (!headers_sent()) {
			header('Pragma: public');
			header('Expires: 0');
			header('Content-Description: File Transfer');
			header('Content-Type: ' . $this->mime);
			header('Content-Transfer-Encoding: ' . $this->encoding);
			header('Content-Disposition: attachment; filename=' . $this->filename);
			header('Content-Length: ' . filesize($this->source));
			
			if (file_exists($this->source)) {
				$file = readfile($this->source, "r");
			
				print($file);
			} else {
				$this->error = 'Error: Could not find file ' . $this->filename;
			}
		} else {
			$this->error = 'Error: Headers already sent out!';
		}
	}

	function getError() { 
		return $this->error; 
	}
}
?>