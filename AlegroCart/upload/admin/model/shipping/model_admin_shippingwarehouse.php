<?php //AdminModelShippingWarehouse AlegroCart
class Model_Admin_ShippingWarehouse extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_warehouse(){
		$this->database->query("delete from setting where `group` = 'warehouse'");
	}
	function install_warehouse(){
		$this->database->query("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_sort_order', value = '4'");
		$this->database->query("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_location', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_handling_fee', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_tax_class_id', value = '0'");
}
	function update_warehouse(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_status', `value` = '?'", (int)$this->request->gethtml('global_warehouse_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_warehouse_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_sort_order', `value` = '?'", (int)$this->request->gethtml('global_warehouse_sort_order', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_location', `value` = '?'", $this->request->gethtml('global_warehouse_location', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_handling_fee', `value` = '?'", (float)$this->request->gethtml('global_warehouse_handling_fee', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'warehouse', `key` = 'warehouse_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_warehouse_tax_class_id', 'post')));
}
	function get_warehouse(){
		$results = $this->database->getRows("select * from setting where `group` = 'warehouse'");
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