<?php //AdminModelFeatured AlegroCart
class Model_Admin_Featured extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_featured(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'featured'");
	}
	function install_featured(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_status', value = '1'");	
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_limit', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_image_width', value = '140'");			
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_image_height', value = '140'");		
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_columns', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_lines_char', value = '108'");
	}
	function update_featured(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_status', `value` = '" . $this->request->gethtml('catalog_featured_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_limit', `value` = '" . $this->request->gethtml('catalog_featured_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_image_width', `value` = '" . $this->request->gethtml('catalog_featured_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_image_height', `value` = '" . $this->request->gethtml('catalog_featured_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_addtocart', `value` = '" . $this->request->gethtml('catalog_featured_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_columns', `value` = '" . $this->request->gethtml('catalog_featured_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_image_display', `value` = '" . $this->request->gethtml('catalog_featured_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_lines_single', `value` = '" . $this->request->gethtml('catalog_featured_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_lines_multi', `value` = '" . $this->request->gethtml('catalog_featured_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'featured', `key` = 'featured_lines_char', `value` = '" . $this->request->gethtml('catalog_featured_lines_char', 'post') . "'");
	}
	function get_featured(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'featured'");
		return $results;
	}
}
?>