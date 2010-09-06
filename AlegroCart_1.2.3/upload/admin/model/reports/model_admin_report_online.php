<?php //AdminModelReportOnline AlegroCart
class Model_Admin_Report_Online extends Model {
	function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
	}
	function get_sessions(){
		$results = $this->database->getRows("select `value`, `ip`, `time`, `url` from `session` where `expire` > '" . (time() - 86400) . "'");
		return $results;
	}
	function get_user($user_id){
		$result = $this->database->getRow("select username from user where user_id = '" . (int)$user_id . "'");
		return $result;
	}
	function get_customer($customer_id){
		$result = $this->database->getRow("select concat(firstname,' ',lastname) as name from customer where customer_id = '" . (int)$customer_id . "'");
		return $result;
	}
	function get_product($product_id){
		$result = $this->database->getRow("select name from product p left join product_description pd on (p.product_id = pd.product_id) where p.product_id = '" . $product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "'"); 
		return $result;
	}
}
?>