<?php //AdminModelMenur AlegroCart
class Model_Admin_Menu extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_menu(){
		$this->database->query("delete from setting where type = 'admin' and `group` = 'menu'");
	}
	function update_menu(){
		$this->database->query($this->database->parse("insert into setting set type = 'admin', `group` = 'menu', `key` = 'menu_status', `value` = '?'", (int)$this->request->gethtml('admin_menu_status', 'post')));
	}
	function get_menu(){
		$results = $this->database->getRows("select * from setting where type = 'admin' and `group` = 'menu'");
		return $results;
	}
	function install_menu(){
		$this->database->query("insert into setting set type = 'admin', `group` = 'menu', `key` = 'menu_status', value = '1'");
	}
}
?>