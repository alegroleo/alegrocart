<?php //AdminModelHomepageModule AlegroCart
class Model_Admin_HomepageModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_homepage(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'homepage'");
	}
	function install_homepage(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'homepage', `key` = 'homepage_status', value = '1'");
	}
	function update_homepage(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'homepage', `key` = 'homepage_status', `value` = '?'", (int)$this->request->gethtml('catalog_homepage_status', 'post')));	
	}
	function get_homepage(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'homepage'");
		return $results;
	}
}
?>