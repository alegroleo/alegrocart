<?php //AdminModelCalculateShipping AlegroCart
class Model_Admin_CalculateShipping extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_shipping(){
		$this->database->query("delete from setting where `group` = 'shipping'");
	}
	function install_shipping(){
		$this->database->query("insert into setting set type = 'global', `group` = 'shipping', `key` = 'shipping_status', value = '1'");
		$this->database->query("insert into setting set type = 'global', `group` = 'shipping', `key` = 'shipping_sort_order', value = '2'");
	}
	function update_shipping(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'shipping', `key` = 'shipping_status', `value` = '?'", (int)$this->request->gethtml('global_shipping_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'shipping', `key` = 'shipping_sort_order', `value` = '?'", (int)$this->request->gethtml('global_shipping_sort_order', 'post')));
	}
	Function get_shipping(){
		$results = $this->database->getRows("select * from setting where `group` = 'shipping'");
		return $results;
	}
}
?>