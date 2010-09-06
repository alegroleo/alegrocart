<?php //ModelCheckout AlegroCart
class Model_Checkout extends Model {
	
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	function get_information($checkout_id){
		$result = $this->database->getRow("select * from information i left join information_description id on (i.information_id = id.information_id) where i.information_id = '" . (int)$checkout_id . "'");
		return $result;
	}
}
?>