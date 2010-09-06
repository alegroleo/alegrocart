<?php //AdminModelDeveloper AlegroCart
class Model_Admin_Developer extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_developer(){
		$this->database->query("delete from setting where type = 'global' and `group` = 'developer'");
	}
	function install_developer(){
		$this->database->query("insert into setting set type = 'global', `group` = 'developer', `key` = 'developer_status', value = '1'");
		$this->database->query("insert into setting set type = 'global', `group` = 'developer', `key` = 'developer_link', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'developer', `key` = 'developer_developer', value = ''");
	}
	function update_developer(){
		$this->database->query("insert into setting set type = 'global', `group` = 'developer', `key` = 'developer_status', `value` = '" . (int)$this->request->gethtml('global_developer_status', 'post') . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'developer', `key` = 'developer_developer', `value` = '" . $this->request->gethtml('global_developer_developer', 'post') . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'developer', `key` = 'developer_link', `value` = '" . $this->request->gethtml('global_developer_link', 'post') . "'");
	}
	function get_developer(){
		$results = $this->database->getRows("select * from setting where type = 'global' and `group` = 'developer'");
		return $results;
	}
}
?>