<?php //AdminModelLanguageModule AlegroCart
class Model_Admin_LanguageModule extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_language(){
		$this->database->query("delete from setting where type = 'admin' and `group` = 'language'");
	}
	function install_language(){
		$this->database->query("insert into setting set type = 'admin', `group` = 'language', `key` = 'language_status', value = '1'");
	}
	function update_langauge(){
		$this->database->query($this->database->parse("insert into setting set type = 'admin', `group` = 'language', `key` = 'language_status', `value` = '?'", (int)$this->request->gethtml('admin_language_status', 'post')));
	}
	function get_langauge(){
		$results = $this->database->getRows("select * from setting where type = 'admin' and `group` = 'language'");
		return $results;
	}
	function get_language_cache(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
}
?>