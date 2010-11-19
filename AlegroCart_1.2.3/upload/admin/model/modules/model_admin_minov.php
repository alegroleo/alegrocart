<?php //AdminModelMinov AlegroCart
class Model_Admin_Minov extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_minov(){
		$this->database->query("delete from setting where `group` = 'minov'");
}
	function update_minov(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'minov', `key` = 'minov_status', `value` = '?'", (int)$this->request->gethtml('global_minov_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'minov', `key` = 'minov_value', `value` = '?'", (int)$this->request->gethtml('global_minov_value', 'post')));
}
	function get_minov(){
		$results = $this->database->getRows("select * from setting where `group` = 'minov'");
		return $results;
	}
	
}
?>
