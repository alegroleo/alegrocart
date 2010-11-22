<?php //AdminModelPaymentCheque AlegroCart
class Model_Admin_PaymentCheque extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_cheque(){
		$this->database->query("delete from setting where `group` = 'cheque'");
	}
	function install_cheque(){
		$this->database->query("insert into setting set type = 'global', `group` = 'cheque', `key` = 'cheque_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'cheque', `key` = 'cheque_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'cheque', `key` = 'cheque_sort_order', value = '1'");
	}
	function update_cheque(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'cheque', `key` = 'cheque_status', `value` = '?'", (int)$this->request->gethtml('global_cheque_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'cheque', `key` = 'cheque_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_cheque_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'cheque', `key` = 'cheque_sort_order', `value` = '?'", (int)$this->request->gethtml('global_cheque_sort_order', 'post')));
	}
	function get_cheque(){
		$results = $this->database->getRows("select * from setting where `group` = 'cheque'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>