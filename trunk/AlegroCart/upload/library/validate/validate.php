<?php

define('VALIDATE_EMAIL','/^[\w!#$%&\'*+\/=?^`{|}~.-]+@(?:[a-z\d][a-z\d-]*(?:\.[a-z\d][a-z\d-]*)?)+\.(?:[a-z][a-z\d-]+)$/iD');

class Validate {

  	function __construct(&$locator) {
		$this->language =& $locator->get('language');
	}

	function email($email) {
		if (preg_match(VALIDATE_EMAIL,$email)) { return $email; }
	}

    function strlen($str, $min=0, $max=false) {
		$strlen=function_exists('mb_strlen')?mb_strlen($str, $this->language->get('charset')):strlen($str);
        if ($strlen < $min) return; 
		if ($max && $strlen > $max) return;
        return TRUE;
    }

    function is_hexcolor($color) {
	return (bool)preg_match('/^#?+[0-9a-f]{3}(?:[0-9a-f]{3})?$/i', $color);
    }

}

?>