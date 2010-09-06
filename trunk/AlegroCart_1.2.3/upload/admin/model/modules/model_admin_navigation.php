<?php //AdminModelNavigation AlegroCart
class Model_Admin_Navigation extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_navigation(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'navigation'");
	}
	function update_navigation(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'navigation', `key` = 'navigation_status', `value` = '?'", (int)$this->request->gethtml('catalog_navigation_status', 'post')));
	}
	function get_navigation(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'navigation'");
		return $results;
	}
	function install_navigation(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'navigation', `key` = 'navigation_status', value = '1'");
	}
}
?>