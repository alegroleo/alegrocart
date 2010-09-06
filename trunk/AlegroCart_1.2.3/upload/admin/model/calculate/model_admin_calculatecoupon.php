<?php //AdminModelCalculateCoupon AlegroCart
class Model_Admin_CalculateCoupon extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_coupon(){
		$this->database->query("delete from setting where `group` = 'coupon'");
	}
	function install_coupon(){
		$this->database->query("insert into setting set type = 'global', `group` = 'coupon', `key` = 'coupon_status', value = '1'");
		$this->database->query("insert into setting set type = 'global', `group` = 'coupon', `key` = 'coupon_sort_order', value = '3'");
	}
	function update_coupon(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'coupon', `key` = 'coupon_status', `value` = '?'", (int)$this->request->gethtml('global_coupon_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'coupon', `key` = 'coupon_sort_order', `value` = '?'", (int)$this->request->gethtml('global_coupon_sort_order', 'post')));
	}
	function get_coupon(){
		$results = $this->database->getRows("select * from setting where `group` = 'coupon'");
		return $results;
	}
}
?>