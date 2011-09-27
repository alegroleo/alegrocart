<?php //AdminModelShippingCanadaPost AlegroCart
class Model_Admin_ShippingCanPost extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	
	function delete_CANpost(){
		$this->database->query("delete from setting where `group` = 'canadapost'");
	}
	
	
	
	function get_CANpost(){
		$results = $this->database->getRows("select * from setting where `group` = 'canadapost'");
		return $results;
	}
	
	function update_CANpost(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_geo_zone_id', `value` = '?'", (int)$this->request->gethtml('global_canadapost_geo_zone_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_status', `value` = '?'", (int)$this->request->gethtml('global_canadapost_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_canadapost_tax_class_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_sort_order', `value` = '?'", (int)$this->request->gethtml('global_canadapost_sort_order', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_postcode', `value` = '?'", $this->request->gethtml('global_canadapost_postcode', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_ip', `value` = '?'", $this->request->gethtml('global_canadapost_ip', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_port', `value` = '?'", $this->request->gethtml('global_canadapost_port', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_merchant_id', `value` = '?'", $this->request->gethtml('global_canadapost_merchant_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_language', `value` = '?'", $this->request->gethtml('global_canadapost_language', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_handling', `value` = '?'", $this->request->gethtml('global_canadapost_handling', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_turnaround', `value` = '?'", $this->request->gethtml('global_canadapost_turnaround', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_readytoship', `value` = '?'", $this->request->gethtml('global_canadapost_readytoship', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_package', `value` = '?'", $this->request->gethtml('global_canadapost_package', 'post')));
	}
	
	function install_CANpost(){
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_tax_class_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_sort_order', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_postcode', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_ip', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_port', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_merchant_id', value = ''");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_language', value = 'en'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_handling', value = '0.00'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_turnaround', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_readytoship', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'canadapost', `key` = 'canadapost_package', value = '0'");
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
		$results = $this->database->getRows("select * from setting where `group` = 'canadapost'");
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