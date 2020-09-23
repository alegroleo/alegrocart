<?php //AdminModelReportOnline AlegroCart
class Model_Admin_Report_Online extends Model {

	public function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
	}

	public function get_sessions(){
		$results = $this->database->getRows("select `value`, `ip`, `time`, `url` from `session` where `expire` > '" . (time() - 86400) . "'");
		return $results;
	}

	public function get_user($user_id){
		$result = $this->database->getRow("SELECT username, CONCAT(firstname, ' ', lastname) AS uname, ug.name AS usergroup FROM user u LEFT JOIN user_group ug ON (u.user_group_id = ug.user_group_id) WHERE user_id = '" . (int)$user_id . "'");
		return $result;
	}

	public function get_customer($customer_id){
		$result = $this->database->getRow("select concat(firstname,' ',lastname) as name from customer where customer_id = '" . (int)$customer_id . "'");
		return $result;
	}

	public function get_product($product_id){
		$result = $this->database->getRow("select name from product p left join product_description pd on (p.product_id = pd.product_id) where p.product_id = '" . $product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "'"); 
		return $result;
	}

}
?>
