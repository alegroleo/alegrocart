<?php //AdminModelPaymentMoneyOrder AlegroCart
class Model_Admin_PaymentMoneyOrder extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_moneyorder(){
		$this->database->query("delete from setting where `group` = 'moneyorder'");
	}
	function install_moneyorder(){
		$this->database->query("insert into setting set type = 'global', `group` = 'moneyorder', `key` = 'moneyorder_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'moneyorder', `key` = 'moneyorder_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'moneyorder', `key` = 'moneyorder_sort_order', value = '1'");
	}
	function update_moneyorder(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'moneyorder', `key` = 'moneyorder_status', `value` = '?'", (int)$this->request->gethtml('global_moneyorder_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'moneyorder', `key` = 'moneyorder_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_moneyorder_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'moneyorder', `key` = 'moneyorder_sort_order', `value` = '?'", (int)$this->request->gethtml('global_moneyorder_sort_order', 'post')));
	}
	function get_moneyorder(){
		$results = $this->database->getRows("select * from setting where `group` = 'moneyorder'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>