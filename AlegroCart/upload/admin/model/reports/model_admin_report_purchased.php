<?php //AdminModelReportPurchased AlegroCart
class Model_Admin_Report_Purchased extends Model {
	function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
	}
	function get_purchases(){
		$results = $this->database->getRows("SELECT p.product_id, op.name, op.model_number, SUM(op.quantity) AS quantity, SUM(op.total) AS total FROM order_product op LEFT JOIN product p ON (op.product_id = p.product_id) GROUP BY name ORDER BY total DESC");
		return $results;
	}
}
?>
