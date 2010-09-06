<?php //AdminModelPaymentCod AlegroCart
class Model_Admin_PaymentCod extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_cod(){
		$this->database->query("delete from setting where `group` = 'cod'");
	}
	function install_cod(){
		$this->database->query("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_sort_order', value = '1'");
	}
	function update_cod(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_status', `value` = '?'", (int)$this->request->gethtml('global_cod_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_cod_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'cod', `key` = 'cod_sort_order', `value` = '?'", (int)$this->request->gethtml('global_cod_sort_order', 'post')));
	}
	function get_cod(){
		$results = $this->database->getRows("select * from setting where `group` = 'cod'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>