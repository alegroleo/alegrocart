<?php //AdminModelSearch AlegroCart
class Model_Admin_Search extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_search(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'search'");
	}
	function update_search(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'search', `key` = 'search_status', `value` = '?'", (int)$this->request->gethtml('catalog_search_status', 'post')));
	}
	function get_search(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'search'");
		return $results;
	}
	function install_search(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'search', `key` = 'search_status', value = '1'");
	}
}
?>