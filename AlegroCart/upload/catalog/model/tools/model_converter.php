<?php //ModelCurrencyConverter AlegroCart
class Model_Converter extends Model{
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	function get_currencies(){
		$results = $this->database->getRows("select code, title from currency order by title asc");
		return $results;
	}
}
?>