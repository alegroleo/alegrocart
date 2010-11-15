<?php

function roundDigits ($value, $precision=0 ){
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
?>