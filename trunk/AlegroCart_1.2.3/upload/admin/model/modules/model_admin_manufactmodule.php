<?php //AdminModelManufacturerModule AlegroCart
class Model_Admin_ManufactModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_manufacturer(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'manufacturer'");
	}
	function install_manufacturer(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_columns', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_display_lock', value = '0'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_width', value = '175'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_height', value = '175'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_options_select', value = 'select'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_rows', value = '0'");
	}
	function update_manufacturer(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_status', `value` = '?'", $this->request->gethtml('catalog_manufacturer_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_addtocart', `value` = '?'", $this->request->gethtml('catalog_manufacturer_addtocart', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_width', `value` = '?'", $this->request->gethtml('catalog_manufacturer_image_width', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_image_height', `value` = '?'", $this->request->gethtml('catalog_manufacturer_image_height', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_columns', `value` = '?'", $this->request->gethtml('catalog_manufacturer_columns', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_display_lock', `value` = '?'", $this->request->gethtml('catalog_manufacturer_display_lock', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_options_select', `value` = '?'", $this->request->gethtml('catalog_manufacturer_options_select', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturer', `key` = 'manufacturer_rows', `value` = '?'", $this->request->gethtml('catalog_manufacturer_rows', 'post')));
	}
	function get_manufacturer(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'manufacturer'");
		return $results;
	}
}
?>