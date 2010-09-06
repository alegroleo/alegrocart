<?php //AdminModelInformationModule AlegroCart
class Model_Admin_InformationModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_information(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'information'");
	}
	function update_information(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'information', `key` = 'information_status', `value` = '?'", (int)$this->request->gethtml('catalog_information_status', 'post')));
	}
	function get_information(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'information'");
		return $results;
	}
	function install_information(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'information', `key` = 'information_status', value = '1'");
	}
}
?>