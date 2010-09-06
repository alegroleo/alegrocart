<?php //AdminModelCalculateSubtotal AlegroCart
class Model_Admin_CalculateSubtotal extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_subtotal(){
		$this->database->query("delete from setting where `group` = 'subtotal'");
	}
	function install_subtotal(){
		$this->database->query("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_status', value = '1'");
		$this->database->query("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_sort_order', value = '1'");
	}
	function update_subtotal(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_status', `value` = '?'", (int)$this->request->gethtml('global_subtotal_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'subtotal', `key` = 'subtotal_sort_order', `value` = '?'", (int)$this->request->gethtml('global_subtotal_sort_order', 'post')));
	}
	function get_subtotal(){
		$results = $this->database->getRows("select * from setting where `group` = 'subtotal'");
		return $results;
	}
}
?>