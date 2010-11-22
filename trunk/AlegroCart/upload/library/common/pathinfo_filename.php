<?php

function pathinfo_filename($file) { //file.name.ext, returns file.name
	if (defined('PATHINFO_FILENAME')) { return pathinfo($file,PATHINFO_FILENAME); }
	$file=basename($file);
	if (strstr($file, '.')) { return substr($file,0,strrpos($file,'.')); }
}

?>