<?php //AdminModelCatalogHeader AlegroCart
class Model_Admin_CatalogHeader extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_header(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'header'");
	}
	function update_header(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'header', `key` = 'header_status', `value` = '?'", (int)$this->request->gethtml('catalog_header_status', 'post')));
	}
	function get_header(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'header'");
		return $results;
	}
	function install_header(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'header', `key` = 'header_status', value = '1'");
	}
}
?>