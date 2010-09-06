<?php //AdminModelPaymentPaypal AlegroCart
class Model_Admin_PaymentPaypal extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_paypal(){
		$this->database->query("delete from setting where `group` = 'paypal'");
	}
	function install_paypal(){
		$this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_email', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_test', value = '0'");		
		$this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_currency', value = 'USD'");
		$this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_sort_order', value = '1'");
        $this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_auth_type', value = 'sale'");
        $this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_pdt_token', value = ''");
        $this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_itemized', value = '0'");
        $this->database->query("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_ipn_debug', value = '0'");
	}
	function update_paypal(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_status', `value` = '?'", (int)$this->request->gethtml('global_paypal_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_paypal_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_email', `value` = '?'", $this->request->gethtml('global_paypal_email', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_test', `value` = '?'", (int)$this->request->gethtml('global_paypal_test', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_currency', `value` = '?'", implode(',', $this->request->gethtml('global_paypal_currency', 'post', array()))));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_sort_order', `value` = '?'", (int)$this->request->gethtml('global_paypal_sort_order', 'post')));
        $this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_auth_type', `value` = '?'", $this->request->gethtml('global_paypal_auth_type', 'post')));
        $this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_pdt_token', `value` = '?'", $this->request->gethtml('global_paypal_pdt_token', 'post')));
        $this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_itemized', `value` = '?'", (int)$this->request->gethtml('global_paypal_itemized', 'post')));
        $this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'paypal', `key` = 'paypal_ipn_debug', `value` = '?'", (int)$this->request->gethtml('global_paypal_ipn_debug', 'post')));
	}
	function get_paypal(){
		$results = $this->database->getRows("select * from setting where `group` = 'paypal'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>