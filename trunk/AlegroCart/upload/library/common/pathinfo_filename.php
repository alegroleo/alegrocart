<?php

function pathinfo_filename($file) { //file.name.ext, returns file.name
	if (defined('PATHINFO_FILENAME')) { return pathinfo($file,PATHINFO_FILENAME); }
	$file=basename($file);
	if (strstr($file, '.')) { return substr($file,0,strrpos($file,'.')); }
}

function pathinfo_extension($file) { //file.name.ext, returns file.ext
	if (defined('PATHINFO_EXTENSION')) { return pathinfo($file,PATHINFO_EXTENSION); }
	$file=basename($file);
	if (strstr($file, '.')) { return substr($file,strrpos($file,'.')); }
}

?>
