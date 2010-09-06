<?php //AdminModelCore AlegroCart
class Model_Admin_Core extends Model {
	
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	
	
	
}
?>