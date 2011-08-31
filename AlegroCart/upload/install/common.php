<?php

// common functions - used for the installer, will be eventually use classes

function mysql_import_file($file,$link=false) {
	if ($sql=file($file)) {
		$query = '';
		foreach($sql as $line) {
			if ((substr(trim($line), 0, 2) == '--') || (substr(trim($line), 0, 1) == '#')){
				$line='';
			}
			if (!empty($line)) {
				$query .= $line;
				if (strstr($query,'ALTER TABLE') == TRUE){
					$query = trim($query).' ';
				}
				if (preg_match('/;\s*$/', $query)){
					if(preg_match('/^ALTER TABLE (.+?) ADD (.+?) /',$query,$matches)){
						if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])), $link)) > 0){
							$query='';
						}
					}
					if(preg_match('/^ALTER TABLE (.+?) DROP (.+?) /',$query,$matches)){
						$matches[2] = str_replace(';','',$matches[2]);
						if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])), $link)) == NULL){
							$query = '';
						}
					}
					if(preg_match('/^ALTER TABLE (.+?) CHANGE (.+?) /',$query,$matches)){
						$matches[2] = str_replace(';','',$matches[2]);
						if (mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'",$matches[1],str_replace('`','',$matches[2])), $link)) == NULL){
							$query = '';
						}
					}
					if((strlen($query) > 3) && (preg_match('/;\s*$/', $line))){
						mysql_query($query, $link);
						$query = '';
					}
				}
			}
			echo mysql_error();
		}
	}
}

function getbasepath($basepath='..') {
	if (@chdir($basepath)) { $basepath=getcwd(); }
	@chdir(dirname(__FILE__));
	return add_trailing_slash($basepath,DIRECTORY_SEPARATOR);
}

function getbaseurl($baseurl='') {
	$baseurl='http://'.$_SERVER['HTTP_HOST'].str_replace('/'.basename(dirname($_SERVER['REQUEST_URI'])),'',dirname($_SERVER['REQUEST_URI']));
	return add_trailing_slash($baseurl);
}

function add_trailing_slash($path,$slash='/') {
	return (substr($path, -1) != $slash)?$path.$slash:$path;
}

?>