<?php //AdminModelCalculateDiscount AlegroCart
class Model_Admin_CalculateDiscount extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_discount(){
		$this->database->query("delete from setting where `group` = 'discount'");
	}
	function install_discount(){
		$this->database->query("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_sort_order', value = '3'");
		$this->database->query("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_lprice', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_lprice_percent', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_gprice', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_gprice_percent', value = '0.00'");
	}
	function update_discount(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_status', `value` = '?'", (int)$this->request->gethtml('global_discount_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_sort_order', `value` = '?'", (int)$this->request->gethtml('global_discount_sort_order', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_lprice', value = '?'", (int)$this->request->gethtml('global_discount_lprice', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_lprice_percent', value = '?'",  (int)$this->request->gethtml('global_discount_lprice_percent', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_gprice', value = '?'",  (int)$this->request->gethtml('global_discount_gprice', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'discount', `key` = 'discount_gprice_percent', value = '?'", (int)$this->request->gethtml('global_discount_gprice_percent', 'post')));

}
	function get_discount(){
		$results = $this->database->getRows("select * from setting where `group` = 'discount'");
		return $results;
	}
}
?>