<?php //AdminModelBoughtProductsModule AlegroCart
class Model_Admin_BoughtModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_bought(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'bought'");
	}
	function install_bought(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_status', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_columns', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_display_lock', value = '0'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_image_width', value = '175'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_image_height', value = '175'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_ratings', value = '1'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_rows', value = '0'");
	}
	function update_bought(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_status', `value` = '?'", $this->request->gethtml('catalog_bought_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_image_width', `value` = '?'", $this->request->gethtml('catalog_bought_image_width', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_image_height', `value` = '?'", $this->request->gethtml('catalog_bought_image_height', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_ratings', `value` = '?'", $this->request->gethtml('catalog_bought_ratings', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_columns', `value` = '?'", $this->request->gethtml('catalog_bought_columns', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_display_lock', `value` = '?'", $this->request->gethtml('catalog_bought_display_lock', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'bought', `key` = 'bought_rows', `value` = '?'", $this->request->gethtml('catalog_bought_rows', 'post')));
	}
	function get_bought(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'bought'");
		return $results;
	}
	function get_extension_id($controller) {
		$result = $this->database->getRow("SELECT extension_id FROM extension WHERE controller ='" . $controller . "'");
		return $result['extension_id'];
	}
}
?>
