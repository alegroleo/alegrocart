<?php //AdminModelPaymentPayMate AlegroCart
class Model_Admin_PaymentPayMate extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_paymate(){
		$this->database->query("delete from setting where `group` = 'paymate'");
	}
	function install_paymate(){
		$this->database->query("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_mid', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_test', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_currency', value = 'AUD'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_sort_order', value = '1'");
	}
	function update_paymate(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_status', `value` = '?'", $this->request->gethtml('global_paymate_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_geo_zone_id', `value` = '?'", $this->request->gethtml('global_paymate_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_mid', `value` = '?'", $this->request->gethtml('global_paymate_mid', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_test', `value` = '?'", $this->request->gethtml('global_paymate_test', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_currency', `value` = '?'", implode(',', $this->request->gethtml('global_paymate_currency', 'post', array()))));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_sort_order', `value` = '?'", $this->request->gethtml('global_paymate_sort_order', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paymate', `key` = 'paymate_order_status', `value` = '?'", $this->request->gethtml('paymate_order_status', 'post')));
	}
	function get_paymate(){
		$results = $this->database->getRows("select * from setting where `group` = 'paymate'");
		return $results;
	}
	function get_order_status(){
		$results = $this->database->getRows("select * from order_status where language_id = '" . $this->language->getId() . "'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>