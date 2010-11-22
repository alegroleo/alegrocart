<?php

function htmlspecialchars_deep ($mixed, $quote_style=ENT_QUOTES, $charset='UTF-8') {
	if (is_array($mixed)) {
		foreach ($mixed as $key => $value) {
			$mixed[$key]=htmlspecialchars_deep($value, $quote_style, $charset);
		}
	} elseif (is_string($mixed)) {
		$mixed=htmlspecialchars(htmlspecialchars_decode($mixed, $quote_style),$quote_style,$charset);
	}
	return $mixed;
}

?>