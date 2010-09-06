<?php //AdminModelPopular AlegroCart
class Model_Admin_Popular extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_popular(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'popular'");
	}
	function install_popular(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_limit', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_image_width', value = '160'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_image_height', value = '160'");	
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_addtocart', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_total', value = '10'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_columns', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_image_display', value = 'image_link'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_lines_single', value = '6'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_lines_multi', value = '4'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_lines_char', value = '108'");
	}
	function update_popular(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_status', `value` = '" . $this->request->gethtml('catalog_popular_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_limit', `value` = '" . $this->request->gethtml('catalog_popular_limit', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_image_width', `value` = '" . $this->request->gethtml('catalog_popular_image_width', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_image_height', `value` = '" . $this->request->gethtml('catalog_popular_image_height', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_addtocart', `value` = '" . $this->request->gethtml('catalog_popular_addtocart', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_total', `value` = '" . $this->request->gethtml('catalog_popular_total', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_columns', `value` = '" . $this->request->gethtml('catalog_popular_columns', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_image_display', `value` = '" . $this->request->gethtml('catalog_popular_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_lines_single', `value` = '" . $this->request->gethtml('catalog_popular_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_lines_multi', `value` = '" . $this->request->gethtml('catalog_popular_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'popular', `key` = 'popular_lines_char', `value` = '" . $this->request->gethtml('catalog_popular_lines_char', 'post') . "'");
	}
	function get_popular(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'popular'");
		return $results;
	}
}
?>