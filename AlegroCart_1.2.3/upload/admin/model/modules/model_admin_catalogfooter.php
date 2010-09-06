<?php //AdminModelCatalogFooter AlegroCart
class Model_Admin_CatalogFooter extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_footer(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'footer'");
	}
	function update_footer(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'footer', `key` = 'footer_status', `value` = '?'", (int)$this->request->gethtml('catalog_footer_status', 'post')));
	}
	function get_footer(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'footer'");
		return $results;
	}
	function install_footer(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'footer', `key` = 'footer_status', value = '1'");
	}
}
?>