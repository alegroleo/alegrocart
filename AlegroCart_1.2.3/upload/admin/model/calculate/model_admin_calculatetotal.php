<?php //AdminModelCalculateTotal AlegroCart
class Model_Admin_CalculateTotal extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_total(){
		$this->database->query("delete from setting where `group` = 'total'");
	}
	function install_total(){
		$this->database->query("insert into setting set type = 'global', `group` = 'total', `key` = 'total_status', value = '1'");
		$this->database->query("insert into setting set type = 'global', `group` = 'total', `key` = 'total_sort_order', value = '6'");
	}
	function update_total(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'total', `key` = 'total_status', `value` = '?'", (int)$this->request->gethtml('global_total_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'total', `key` = 'total_sort_order', `value` = '?'", (int)$this->request->gethtml('global_total_sort_order', 'post')));
	}
	function get_total(){
		$results = $this->database->getRows("select * from setting where `group` = 'total'");
		return $results;
	}
}
?>