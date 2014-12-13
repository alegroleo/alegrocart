<?php //AdminModelAlsobought AlegroCart
class Model_Admin_Alsobought extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}
	function delete_alsobought(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'alsobought'");
	}
	function install_alsobought(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_limit', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_image_height', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_total', value = '10'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_columns', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_ratings', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_lines_char', value = '108'");
	}
	function update_alsobought(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_status', `value` = '" . $this->request->gethtml('catalog_alsobought_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_limit', `value` = '" . $this->request->gethtml('catalog_alsobought_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_image_width', `value` = '" . $this->request->gethtml('catalog_alsobought_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_image_height', `value` = '" . $this->request->gethtml('catalog_alsobought_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_addtocart', `value` = '" . $this->request->gethtml('catalog_alsobought_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_total', `value` = '" . $this->request->gethtml('catalog_alsobought_total', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_columns', `value` = '" . $this->request->gethtml('catalog_alsobought_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_image_display', `value` = '" . $this->request->gethtml('catalog_alsobought_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_ratings', `value` = '" . $this->request->gethtml('catalog_alsobought_ratings', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_lines_single', `value` = '" . $this->request->gethtml('catalog_alsobought_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_lines_multi', `value` = '" . $this->request->gethtml('catalog_alsobought_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'alsobought', `key` = 'alsobought_lines_char', `value` = '" . $this->request->gethtml('catalog_alsobought_lines_char', 'post') . "'");
	}
	function get_alsobought(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'alsobought'");
		return $results;
	}
}
?>
