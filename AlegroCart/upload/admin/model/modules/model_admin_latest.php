<?php //AdminModelLatest AlegroCart
class Model_Admin_Latest extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_latest(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'latest'");
	}
	function install_latest(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_limit', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_image_height', value = '160'");	
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_total', value = '10'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_columns', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_image_display', value = 'image_link'");	
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_lines_char', value = '108'");
	}
	function update_latest(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_status', `value` = '" . $this->request->gethtml('catalog_latest_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_limit', `value` = '" . $this->request->gethtml('catalog_latest_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_image_width', `value` = '" . $this->request->gethtml('catalog_latest_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_image_height', `value` = '" . $this->request->gethtml('catalog_latest_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_addtocart', `value` = '" . $this->request->gethtml('catalog_latest_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_total', `value` = '" . $this->request->gethtml('catalog_latest_total', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_columns', `value` = '" . $this->request->gethtml('catalog_latest_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_image_display', `value` = '" . $this->request->gethtml('catalog_latest_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_lines_single', `value` = '" . $this->request->gethtml('catalog_latest_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_lines_multi', `value` = '" . $this->request->gethtml('catalog_latest_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'latest', `key` = 'latest_lines_char', `value` = '" . $this->request->gethtml('catalog_latest_lines_char', 'post') . "'");
	}
	function get_latest(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'latest'");
		return $results;
	}
}
?>