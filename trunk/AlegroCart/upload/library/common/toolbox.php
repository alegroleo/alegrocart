<?php

function roundDigits($value, $precision=0 ){
	$half_up =  5 / pow( 10, ($precision == 0 ? 1 :$precision) +1);
	$precisionFactor = ($precision == 0) ? 1 : pow( 10, $precision );
	$rounded = round($value * $precisionFactor + $half_up) / $precisionFactor;
	return $rounded;
}

function increment_number($value){
	preg_match('#(?<digit>\d+)#', $value, $matches);
	$number = $matches[0];
	$number ++;
	$new_value = preg_replace('#(?<digit>\d+)#', $number, $value);
	return $new_value;
}

function convert_bytes($size){
	switch (substr ($size, -1)){
		case 'M': case 'm': return (int)$size * 1048576;
		case 'K': case 'k': return (int)$size * 1024;
		case 'G': case 'g': return (int)$size * 1073741824;
		default: return $size;
	}
}
?>
