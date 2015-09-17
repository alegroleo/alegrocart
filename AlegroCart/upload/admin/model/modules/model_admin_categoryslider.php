<?php //AdminModelCategorySliderModule AlegroCart
class Model_Admin_CategorySlider extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_categoryslider(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'categoryslider'");
	}
	function install_categoryslider(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categoryslider', `key` = 'categoryslider_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categoryslider', `key` = 'categoryslider_image_width', value = '110'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categoryslider', `key` = 'categoryslider_image_height', value = '110'");
	}
	function update_categoryslider(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'categoryslider', `key` = 'categoryslider_status', `value` = '?'", $this->request->gethtml('catalog_categoryslider_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'categoryslider', `key` = 'categoryslider_image_width', `value` = '?'", $this->request->gethtml('catalog_categoryslider_image_width', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'categoryslider', `key` = 'categoryslider_image_height', `value` = '?'", $this->request->gethtml('catalog_categoryslider_image_height', 'post')));
	}
	function get_categoryslider(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'categoryslider'");
		return $results;
	}
}
?>
