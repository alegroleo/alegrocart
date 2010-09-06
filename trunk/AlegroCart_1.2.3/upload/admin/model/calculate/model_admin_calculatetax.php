<?php //AdminModelCalculateTax AlegroCart
class Model_Admin_CalculateTax extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_tax(){
		$this->database->query("delete from setting where `group` = 'tax'");
	}
	function install_tax(){
		$this->database->query("insert into setting set type = 'global', `group` = 'tax', `key` = 'tax_status', value = '1'");
		$this->database->query("insert into setting set type = 'global', `group` = 'tax', `key` = 'tax_sort_order', value = '4'");
	}
	function update_tax(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'tax', `key` = 'tax_status', `value` = '?'", (int)$this->request->gethtml('global_tax_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'tax', `key` = 'tax_sort_order', `value` = '?'", (int)$this->request->gethtml('global_tax_sort_order', 'post')));
	}
	function get_tax(){
		$results = $this->database->getRows("select * from setting where `group` = 'tax'");
		return $results;
	}
}
?>