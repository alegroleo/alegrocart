<?php //ModelCheckout AlegroCart
class Model_Checkout extends Model {
	
	function __construct(&$locator) {
		$this->currency =& $locator->get('currency');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	function get_information($checkout_id){
		$result = $this->database->getRow("select * from information i left join information_description id on (i.information_id = id.information_id) where i.information_id = '" . (int)$checkout_id . "' and id.language_id = '" . (int)$this->language->getId()  . "'");
		return $result;
	}
	function get_currency(){
		$result = $this->database->getRow("select code, title from currency where code = '" . $this->currency->getCode() . "'");
		return $result;
	}
}
?>