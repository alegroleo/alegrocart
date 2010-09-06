<?php //AdminModelSpecials AlegroCart
class Model_Admin_Specials extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_specials(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'specials'");
	}
	function install_specials(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_limit', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_width', value = '160'");		
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_height', value = '160'");		
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_columns', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_display', value = 'image_link'");	
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_lines_char', value = '108'");
	}
	function update_specials(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_status', `value` = '" . $this->request->gethtml('catalog_specials_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_limit', `value` = '" . $this->request->gethtml('catalog_specials_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_width', `value` = '" . $this->request->gethtml('catalog_specials_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_height', `value` = '" . $this->request->gethtml('catalog_specials_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_addtocart', `value` = '" . $this->request->gethtml('catalog_specials_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_columns', `value` = '" . $this->request->gethtml('catalog_specials_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_image_display', `value` = '" . $this->request->gethtml('catalog_specials_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_lines_single', `value` = '" . $this->request->gethtml('catalog_specials_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_lines_multi', `value` = '" . $this->request->gethtml('catalog_specials_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'specials', `key` = 'specials_lines_char', `value` = '" . $this->request->gethtml('catalog_specials_lines_char', 'post') . "'");
	}
	function get_specials(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'specials'");
		return $results;
	}
}
?>