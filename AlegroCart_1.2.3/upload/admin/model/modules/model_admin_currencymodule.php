<?php //AdminModelCurrency AlegroCart
class Model_Admin_CurrencyModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_currency(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'currency'");
	}
	function update_currency(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'currency', `key` = 'currency_status', `value` = '?'", (int)$this->request->gethtml('catalog_currency_status', 'post')));
	}
	function get_currency(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'currency'");
		return $results;
	}
	function install_currency(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'currency', `key` = 'currency_status', value = '1'");
	}
}
?>