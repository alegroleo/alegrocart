<?php //AdminModelSearchOptions AlegroCart
class Model_Admin_SearchOptions extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_search_options(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'searchoptions'");
	}
	function install_search_options(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'searchoptions', `key` = 'search_options_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'searchoptions', `key` = 'search_columns', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'searchoptions', `key` = 'search_display_lock', value = '0'");
	}
	function update_search_options(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'searchoptions', `key` = 'search_options_status', `value` = '?'", $this->request->gethtml('catalog_search_options_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'searchoptions', `key` = 'search_columns', `value` = '?'", $this->request->gethtml('catalog_search_columns', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'searchoptions', `key` = 'search_display_lock', `value` = '?'", $this->request->gethtml('catalog_search_display_lock', 'post')));
	}
	function get_search_options(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'searchoptions'");
		return $results;
	}
}
?>