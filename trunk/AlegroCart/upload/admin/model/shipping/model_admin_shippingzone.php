<?php //AdminModelShippingZone AlegroCart
class Model_Admin_ShippingZone extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_zone(){
		$this->database->query("delete from setting where `group` = 'zone'");
	}
	function install_zone(){
		$this->database->query("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_tax_class_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_sort_order', value = '0'");
	}
	function update_zone(){
		foreach ($this->request->gethtml('geo_zone', 'post') as $key => $value) {
				$this->database->query("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_" . (int)$key . "_cost', `value` = '" . $value['cost'] . "'");
				$this->database->query("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_" . (int)$key . "_status', `value` = '" . $value['status'] . "'");
				$this->database->query("insert into setting set `type` = 'global', `group` = 'zone', `key` = 'zone_" . (int)$key . "_free_amount', `value` = '" . $value['free_amount'] . "'");
				$this->database->query("insert into setting set `type` = 'global', `group` = 'zone', `key` = 'zone_" . (int)$key . "_message', `value` = '" . $value['message'] . "'");
		}
		
		
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_status', `value` = '?'", (int)$this->request->gethtml('global_zone_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_zone_tax_class_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zone', `key` = 'zone_sort_order', `value` = '?'", (int)$this->request->gethtml('global_zone_sort_order', 'post')));
	}
	function get_zones(){
		$results = $this->database->getRows("select * from setting where `group` = 'zone'");
		return $results;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone order by name");
		return $results;
	}
	function get_geozone($geozone_id){
		$result = $this->database->getRow("select name, description from geo_zone where geo_zone_id = '" . $geozone_id . "'");
		return $result;
	}
	function get_tax_classes(){
		$results = $this->database->cache('tax_class', "select * from tax_class");
		return $results;
	}
}
?>