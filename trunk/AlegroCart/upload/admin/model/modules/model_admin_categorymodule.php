<?php //AdminModelCategoryModule AlegroCart
class Model_Admin_CategoryModule extends Model {
	function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->request		=& $locator->get('request');
		$this->session		=& $locator->get('session');
	}
	function delete_category(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'category'");
	}
	function update_category(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_status', `value` = '?'", (int)$this->request->gethtml('catalog_category_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_mcount', `value` = '?'", (int)$this->request->gethtml('catalog_category_mcount', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_pcount', `value` = '?'", (int)$this->request->gethtml('catalog_category_pcount', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_ratings', `value` = '?'", (int)$this->request->gethtml('catalog_category_ratings', 'post')));
	}
	function get_category(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'category'");
		return $results;
	}
	function install_category(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_mcount', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_pcount', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'category', `key` = 'category_ratings', value = '1'");
	}
}
?>
