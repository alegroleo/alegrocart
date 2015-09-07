<?php //AdminModelCategoryList AlegroCart
class Model_Admin_CategoryList extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}
	function delete_categorylist(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'categorylist'");
	}
	function install_categorylist(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_limit', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_image_height', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_total', value = '10'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_columns', value = '3'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_ratings', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_lines_char', value = '108'");
	}
	function update_categorylist(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_status', `value` = '" . $this->request->gethtml('catalog_categorylist_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_limit', `value` = '" . $this->request->gethtml('catalog_categorylist_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_image_width', `value` = '" . $this->request->gethtml('catalog_categorylist_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_image_height', `value` = '" . $this->request->gethtml('catalog_categorylist_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_addtocart', `value` = '" . $this->request->gethtml('catalog_categorylist_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_total', `value` = '" . $this->request->gethtml('catalog_categorylist_total', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_columns', `value` = '" . $this->request->gethtml('catalog_categorylist_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_image_display', `value` = '" . $this->request->gethtml('catalog_categorylist_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_ratings', `value` = '" . $this->request->gethtml('catalog_categorylist_ratings', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_lines_multi', `value` = '" . $this->request->gethtml('catalog_categorylist_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categorylist', `key` = 'categorylist_lines_char', `value` = '" . $this->request->gethtml('catalog_categorylist_lines_char', 'post') . "'");
	}
	function get_categorylist(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'categorylist'");
		return $results;
	}
}
?>
