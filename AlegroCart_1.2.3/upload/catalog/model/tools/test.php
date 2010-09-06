<?php
class ModelTest extends Model {
	function index($test,$i) {
	$database =& $this->locator->get('database');
	echo $test;
	$i ++;
	return 'So it is'.$i;
	}
}
?>