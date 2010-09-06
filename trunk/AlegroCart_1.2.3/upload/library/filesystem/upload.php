<?php
class Upload {
	function get($key) {
		return (isset($_FILES[$key]) ? $_FILES[$key] : NULL);
	}
		
	function has($key){
		if (isset($_FILES) && isset($_FILES[$key])) {
			//return is_uploaded_file($_FILES[$key]['tmp_name']);
			return ($_FILES[$key]['name']);
		}
	}

	function getName($key) {
		return (isset($_FILES[$key]['name']) ? $_FILES[$key]['name'] : NULL);
	}
 
	function getType($key) {
		return (isset($_FILES[$key]['type']) ? $_FILES[$key]['type'] : NULL);
	}

	function getSize($key) {
		return (isset($_FILES[$key]['size']) ? $_FILES[$key]['size'] : NULL);
	}

	function getError($key) {
		return (isset($_FILES[$key]['error']) ? $_FILES[$key]['error'] : NULL);
	}

	function hasError($key) {
		if (!is_uploaded_file($_FILES[$key]['tmp_name'])) {
			if ($_FILES[$key]['error']) {
				return !empty($_FILES[$key]['error']);
			}
			return true;
		}
	}

	function save($key, $file) {
		if (file_exists($file)) @unlink($file);
		$status=@copy($_FILES[$key]['tmp_name'], $file);
		if ($status) {
			@chmod($file, 0644);
			@unlink($_FILES[$key]['tmp_name']);
		}
		return $status;
	}	
}
?>