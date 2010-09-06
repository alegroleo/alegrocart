<?php

function stripslashes_deep ($mixed) {
	if (is_array($mixed)) { $mixed=array_map(__FUNCTION__, $mixed); }
	elseif (is_string($mixed)) { $mixed=stripslashes($mixed); }
	return $mixed;
}

?>