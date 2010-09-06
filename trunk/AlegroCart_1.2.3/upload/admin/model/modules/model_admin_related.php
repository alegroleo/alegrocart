<?php //AdminModelRelated AlegroCart
class Model_Admin_Related extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_related(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'related'");
	}
	function install_related(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_limit', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_height', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_columns', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_lines_multi', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_lines_char', value = '108'");
	}
	function update_related(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_status', `value` = '" . $this->request->gethtml('catalog_related_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_limit', `value` = '" . $this->request->gethtml('catalog_related_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_width', `value` = '" . $this->request->gethtml('catalog_related_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_height', `value` = '" . $this->request->gethtml('catalog_related_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_addtocart', `value` = '" . $this->request->gethtml('catalog_related_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_columns', `value` = '" . $this->request->gethtml('catalog_related_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_image_display', `value` = '" . $this->request->gethtml('catalog_related_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_lines_single', `value` = '" . $this->request->gethtml('catalog_related_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_lines_multi', `value` = '" . $this->request->gethtml('catalog_related_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'related', `key` = 'related_lines_char', `value` = '" . $this->request->gethtml('catalog_related_lines_char', 'post') . "'");
	}
	function get_related(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'related'");
		return $results;
	}
}
?>