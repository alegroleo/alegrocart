<?php //AdminModelManufacturerOptions AlegroCart
class Model_Admin_ManufacturerOptions extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_manufacturer_options(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'manufactureroptions'");
	}
	function install_manufacturer_options(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufactureroptions', `key` = 'manufacturer_options_status', value = '1'");
	}
	function update_manufacturer_options(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufactureroptions', `key` = 'manufacturer_options_status', `value` = '?'", $this->request->gethtml('catalog_manufacturer_options_status', 'post')));
	}
	function get_manufacturer_options(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'manufactureroptions'");
		return $results;
	}
}
?>