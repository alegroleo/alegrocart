<?php //AdminModelShippingFlat AlegroCart
class Model_Admin_ShippingFlat extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_flatrate(){
		$this->database->query("delete from setting where `group` = 'flat'");
	}
	function install_flatrate(){
		$this->database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_cost', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_tax_class_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_sort_order', value = '0'");
	}
	function update_flatrate(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_status', `value` = '?'", (int)$this->request->gethtml('global_flat_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_flat_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_cost', `value` = '?'", (float)$this->request->gethtml('global_flat_cost', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_flat_tax_class_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'flat', `key` = 'flat_sort_order', `value` = '?'", (int)$this->request->gethtml('global_flat_sort_order', 'post')));
	}
	function get_flatrate(){
		$results = $this->database->getRows("select * from setting where `group` = 'flat'");
		return $results;
	}
	function get_tax_classes(){
		$results = $this->database->cache('tax_class', "select * from tax_class");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone");
		return $results;
	}
}
?>