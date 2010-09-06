<?php //AdminModelReportPurchased AlegroCart
class Model_Admin_Report_Purchased extends Model {
	function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
	}
	function get_purchases(){
		$results = $this->database->getRows("select name, model_number, sum(quantity) as quantity, sum(total) as total from order_product group by name order by total desc");
		return $results;
	}
}
?>