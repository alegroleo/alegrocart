<?php //AdminModelFooter AlegroCart
class Model_Admin_Footer extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_footer(){
		$this->database->query("delete from setting where type = 'admin' and `group` = 'footer'");
	}
	function update_footer(){
		$this->database->query($this->database->parse("insert into setting set type = 'admin', `group` = 'footer', `key` = 'footer_status', `value` = '?'", (int)$this->request->gethtml('admin_footer_status', 'post')));
	}
	function get_footer(){
		$results = $this->database->getRows("select * from setting where type = 'admin' and `group` = 'footer'");
		return $results;
	}
	function install_footer(){
		$this->database->query("insert into setting set type = 'admin', `group` = 'footer', `key` = 'footer_status', value = '1'");
	}
}
?>