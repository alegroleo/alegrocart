<?php //AdminModelManufacturerSliderModule AlegroCart
class Model_Admin_ManufacturerSlider extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_manufacturerslider(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'manufacturerslider'");
	}
	function install_manufacturerslider(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerslider', `key` = 'manufacturerslider_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerslider', `key` = 'manufacturerslider_image_width', value = '110'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'manufacturerslider', `key` = 'manufacturerslider_image_height', value = '110'");
	}
	function update_manufacturerslider(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturerslider', `key` = 'manufacturerslider_status', `value` = '?'", $this->request->gethtml('catalog_manufacturerslider_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturerslider', `key` = 'manufacturerslider_image_width', `value` = '?'", $this->request->gethtml('catalog_manufacturerslider_image_width', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'manufacturerslider', `key` = 'manufacturerslider_image_height', `value` = '?'", $this->request->gethtml('catalog_manufacturerslider_image_height', 'post')));
	}
	function get_manufacturerslider(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'manufacturerslider'");
		return $results;
	}
	function get_extension_id($controller) {
		$result = $this->database->getRow("SELECT extension_id FROM extension WHERE controller ='" . $controller . "'");
		return $result['extension_id'];
	}
}
?>
