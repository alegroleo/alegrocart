<?php //AdminModelCurrencyConverter AlegroCart
class Model_Admin_Converter extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_converter(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'converter'");
	}
	function update_converter(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'converter', `key` = 'converter_status', `value` = '?'", (int)$this->request->gethtml('catalog_converter_status', 'post')));
	}
	function get_converter(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'converter'");
		return $results;
	}
	function install_converter(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'converter', `key` = 'converter_status', value = '1'");
	}
}
?>