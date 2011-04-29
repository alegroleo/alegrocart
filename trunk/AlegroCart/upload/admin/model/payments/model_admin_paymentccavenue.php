<?php //AdminModelPaymentccAvenue AlegroCart
class Model_Admin_PaymentccAvenue extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_ccAvenue(){
		$this->database->query("delete from setting where `group` = 'ccavenue'");
	}
	function update_ccAvenue(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_status', `value` = '?'", (int)$this->request->gethtml('global_ccavenue_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_ccavenue_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_merchant_id', `value` = '?'", $this->request->gethtml('global_ccavenue_merchant_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_working_key', `value` = '?'", $this->request->gethtml('global_ccavenue_working_key', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_currency', `value` = '?'", implode(',', $this->request->gethtml('global_ccavenue_currency', 'post', array()))));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_sort_order', `value` = '?'", (int)$this->request->gethtml('global_ccavenue_sort_order', 'post')));
	}
	
	function install_ccAvenue(){
		$this->database->query("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_merchant_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_working_key', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_currency', value = 'INR'");
		$this->database->query("insert into setting set type = 'global', `group` = 'ccavenue', `key` = 'ccavenue_sort_order', value = '1'");
	}
	function get_ccAvenue(){
		$results = $this->database->getRows("select * from setting where `group` = 'ccavenue'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>