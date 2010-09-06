<?php //AdminModelCatalogLanguage AlegroCart
class Model_Admin_CatalogLanguage extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_language(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'language'");
	}
	function update_language(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'language', `key` = 'language_status', `value` = '?'", (int)$this->request->gethtml('catalog_language_status', 'post')));
	}
	function get_language(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'language'");
		return $results;
	}
	function install_language(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'language', `key` = 'language_status', value = '1'");
	}
}
?>