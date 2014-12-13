<?php //AdminModelRecently AlegroCart
class Model_Admin_Recently extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->sessio		=& $locator->get('session');
	}
	function delete_recently(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'recently'");
	}
	function install_recently(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_limit', value = '5'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_image_width', value = '110'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_image_height', value = '110'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_columns', value = '5'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_ratings', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_lines_single', value = '0'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_lines_multi', value = '0'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_lines_char', value = '0'");
	}
	function update_recently(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_status', `value` = '" . $this->request->gethtml('catalog_recently_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_limit', `value` = '" . $this->request->gethtml('catalog_recently_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_image_width', `value` = '" . $this->request->gethtml('catalog_recently_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_image_height', `value` = '" . $this->request->gethtml('catalog_recently_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_addtocart', `value` = '" . $this->request->gethtml('catalog_recently_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_columns', `value` = '" . $this->request->gethtml('catalog_recently_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_image_display', `value` = '" . $this->request->gethtml('catalog_recently_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_ratings', `value` = '" . $this->request->gethtml('catalog_recently_ratings', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_lines_single', `value` = '" . $this->request->gethtml('catalog_recently_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_lines_multi', `value` = '" . $this->request->gethtml('catalog_recently_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'recently', `key` = 'recently_lines_char', `value` = '" . $this->request->gethtml('catalog_recently_lines_char', 'post') . "'");
	}
	function get_recently(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'recently'");
		return $results;
	}
}
?>
