<?php //AdminModelCategoryOptions AlegroCart
class Model_Admin_CategoryOptions extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_category_options(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'categoryoptions'");
	}
	function install_category_options(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categoryoptions', `key` = 'category_options_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categoryoptions', `key` = 'category_columns', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'categoryoptions', `key` = 'category_display_lock', value = '0'");
	}
	function update_category_options(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'categoryoptions', `key` = 'category_options_status', `value` = '?'", $this->request->gethtml('catalog_category_options_status', 'post')));
			$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'categoryoptions', `key` = 'category_columns', `value` = '?'", $this->request->gethtml('catalog_category_columns', 'post')));
			$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'categoryoptions', `key` = 'category_display_lock', `value` = '?'", $this->request->gethtml('catalog_category_display_lock', 'post')));
	}
	function get__category_options(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'categoryoptions'");
		return $results;
	}
}
?>