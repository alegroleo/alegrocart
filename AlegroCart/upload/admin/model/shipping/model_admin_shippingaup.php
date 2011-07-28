<?php //AdminModelShippingZone AlegroCart
class Model_Admin_ShippingAup extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	
	function delete_AUP(){
		$this->database->query("delete from setting where `group` = 'australiapost'");
	}
	
	function update_AUP(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_australiapost_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_status', `value` = '?'", (int)$this->request->gethtml('global_australiapost_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_australiapost_tax_class_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_sort_order', `value` = '?'", (int)$this->request->gethtml('global_australiapost_sort_order', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_weight_class', `value` = '?'", (int)$this->request->gethtml('global_australiapost_weight_class', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_dimension_class', `value` = '?'", (int)$this->request->gethtml('global_australiapost_dimension_class', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_default_method', `value` = '?'", $this->request->gethtml('global_australiapost_default_method', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_postcode', `value` = '?'", $this->request->gethtml('global_australiapost_postcode', 'post')));
	}
	
	function install_AUP(){
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_tax_class_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_sort_order', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_weight__class', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_dimension__class', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_default_method', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'australiapost', `key` = 'australiapost_postcode', value = ''");
	}
	
	function get_AUPost(){
		$results = $this->database->getRows("select * from setting where `group` = 'australiapost'");
		return $results;
	}
	
	function get_weight_classes(){
		$results = $this->database->cache('weight_class-' . (int)$this->language->getId(), "select weight_class_id, title from weight_class where language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	
	function get_dimension_classes($type_id){
		$results = $this->database->getRows("select * from dimension where type_id = '" . $type_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	
	function get_zones(){
		$results = $this->database->getRows("select * from setting where `group` = 'australiapost'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone order by name");
		return $results;
	}
	function get_tax_classes(){
		$results = $this->database->cache('tax_class', "select * from tax_class");
		return $results;
	}
}
?>