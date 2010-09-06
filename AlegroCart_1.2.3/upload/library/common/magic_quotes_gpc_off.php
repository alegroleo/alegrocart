<?php

function php_compat_stripslashes_deep ($mixed) {
	if (is_array($mixed)) { $mixed=array_map(__FUNCTION__, $mixed); }
	elseif (is_string($mixed)) { $mixed=stripslashes($mixed); }
	return $mixed;
}

function php_compat_sybase_unescape_deep ($mixed) { // remove Sybase-style magic quotes (escape ' with '' instead of \').
	if (is_array($mixed)) { $mixed=array_map(__FUNCTION__, $mixed); }
	elseif (is_string($mixed)) { $mixed=str_replace('\'\'', '\'', $mixed); }
	return $mixed;
}

//Can this be made more efficient?
function php_compat_magic_quotes_gpc_off () {
	//If the  magic_quotes_sybase directive is also ON it will completely override magic_quotes_gpc. Having both directives enabled means only single quotes are escaped as ''. Double quotes, backslashes and NUL's will remain untouched and unescaped.
	//Note that when magic_quotes_sybase  is ON it completely overrides magic_quotes_gpc . In this case even when magic_quotes_gpc  is enabled neither double quotes, backslashes or NUL's will be escaped.
	if (ini_get('magic_quotes_sybase')) {
		//Sets the magic_quotes state for GPC (Get/Post/Cookie) operations. When magic_quotes are on, all ' (single-quote), " (double quote), \ (backslash) and NUL's are escaped with a backslash automatically. 
		$_GET=php_compat_sybase_unescape_deep($_GET);
		$_POST=php_compat_sybase_unescape_deep($_POST);
		$_COOKIE=php_compat_sybase_unescape_deep($_COOKIE);
		$_REQUEST=php_compat_sybase_unescape_deep($_REQUEST);
		//In PHP 4, also $_ENV  variables are escaped.
		if (version_compare(PHP_VERSION, '4.1.0', '<')) {
			$_SERVER=php_compat_sybase_unescape_deep($_SERVER);
			$_ENV=php_compat_sybase_unescape_deep($_ENV);
		}
	} elseif (get_magic_quotes_gpc()) {
		//Sets the magic_quotes state for GPC (Get/Post/Cookie) operations. When magic_quotes are on, all ' (single-quote), " (double quote), \ (backslash) and NUL's are escaped with a backslash automatically. 
		$_GET=php_compat_stripslashes_deep($_GET);
		$_POST=php_compat_stripslashes_deep($_POST);
		$_COOKIE=php_compat_stripslashes_deep($_COOKIE);
		$_REQUEST=php_compat_stripslashes_deep($_REQUEST);
		//In PHP 4, also $_ENV  variables are escaped.
		if (version_compare(PHP_VERSION, '4.1.0', '<')) {
			$_SERVER=php_compat_stripslashes_deep($_SERVER);
			$_ENV=php_compat_stripslashes_deep($_ENV);
		}
	}
}

php_compat_magic_quotes_gpc_off();

?>