<?php //AdminModelBoughtOptions AlegroCart
class Model_Admin_BoughtOptions extends Model {
	function __construct(&$locator) {
		$this->config	=& $locator->get('config');
		$this->database	=& $locator->get('database');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function delete_bought_options(){
		$this->database->query("delete from setting where type = 'catalog' and `group` = 'boughtoptions'");
	}
	function install_bought_options(){
		$this->database->query("insert into setting set type = 'catalog', `group` = 'boughtoptions', `key` = 'bought_options_status', value = '1'");
	}
	function update_bought_options(){
		$this->database->query($this->database->parse("insert into setting set type = 'catalog', `group` = 'boughtoptions', `key` = 'bought_options_status', `value` = '?'", $this->request->gethtml('catalog_bought_options_status', 'post')));
	}
	function get_bought_options(){
		$results = $this->database->getRows("select * from setting where type = 'catalog' and `group` = 'boughtoptions'");
		return $results;
	}
	function get_extension_id($controller) {
		$result = $this->database->getRow("SELECT extension_id FROM extension WHERE controller ='" . $controller . "'");
		return $result['extension_id'];
	}
}
?>
