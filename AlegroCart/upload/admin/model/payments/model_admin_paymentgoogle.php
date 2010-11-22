<?php //AdminModelPaymentGoogle AlegroCart
class Model_Admin_PaymentGoogle extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_google(){
		$this->database->query("delete from setting where `group` = 'google'");
	}
	function install_google(){
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantid', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantkey', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_test', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_currency', value = 'USD'");
		$this->database->query("insert into setting set type = 'global', `group` = 'google', `key` = 'google_sort_order', value = '1'");
	}
	function update_google(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_status', `value` = '?'", (int)$this->request->get('global_google_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_geo_zone_id', `value` = '?'", (int)$this->request->get('global_google_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantid', `value` = '?'", $this->request->get('global_google_merchantid', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_merchantkey', `value` = '?'", $this->request->get('global_google_merchantkey', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_test', `value` = '?'", (int)$this->request->get('global_google_test', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_currency', `value` = '?'", $this->request->get('global_google_currency', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'google', `key` = 'google_sort_order', `value` = '?'", (int)$this->request->get('global_google_sort_order', 'post')));
	}
	function get_google(){
		$results = $this->database->getRows("select * from setting where `group` = 'google'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>