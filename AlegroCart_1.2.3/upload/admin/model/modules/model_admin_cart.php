<?php //AdminModelCart AlegroCart
class Model_Admin_Cart extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_cart(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'cart'");
	}
	function update_cart(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'cart', `key` = 'cart_status', `value` = '?'", (int)$this->request->gethtml('catalog_cart_status', 'post')));
	}
	function get_cart(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'cart'");
		return $results;
	}
	function install_cart(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'cart', `key` = 'cart_status', value = '1'");
	}
}
?>