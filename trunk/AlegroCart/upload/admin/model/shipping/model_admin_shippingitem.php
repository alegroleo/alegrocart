<?php //AdminModelShippingItem AlegroCart
class Model_Admin_ShippingItem extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_item(){
		$this->database->query("delete from setting where `group` = 'item'");
	}
	function install_item(){
		$this->database->query("insert into setting set type = 'global', `group` = 'item', `key` = 'item_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'item', `key` = 'item_geo_zone_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'item', `key` = 'item_cost', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'item', `key` = 'item_max', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'item', `key` = 'item_tax_class_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'item', `key` = 'item_sort_order', value = '0'");
	}
	function update_item(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'item', `key` = 'item_status', `value` = '?'", (int)$this->request->gethtml('global_item_status', 'post')));			 
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'item', `key` = 'item_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_item_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'item', `key` = 'item_cost', `value` = '?'", (float)$this->request->gethtml('global_item_cost', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'item', `key` = 'item_max', `value` = '?'", (float)$this->request->gethtml('global_item_max', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'item', `key` = 'item_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_item_tax_class_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'item', `key` = 'item_sort_order', `value` = '?'", (int)$this->request->gethtml('global_item_sort_order', 'post')));
	}
	function get_item(){
		$results = $this->database->getRows("select * from setting where `group` = 'item'");
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