<?php //AdminModelShippingZonePlus AlegroCart
class Model_Admin_ShippingZonePlus extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_zoneplus(){
		$this->database->query("delete from setting where `group` = 'zoneplus'");
	}
	function install_zoneplus(){
		$this->database->query("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_status', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_tax_class_id', value = '0'");
		$this->database->query("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_sort_order', value = '0'");
	}
	function update_zoneplus(){
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_status', `value` = '?'", (int)$this->request->gethtml('global_zoneplus_status', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_tax_class_id', `value` = '?'", (int)$this->request->gethtml('global_zoneplus_tax_class_id', 'post')));
		$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_sort_order', `value` = '?'", (int)$this->request->gethtml('global_zoneplus_sort_order', 'post')));
		
		if($this->request->has('geo_zone', 'post')){
			foreach($this->request->gethtml('geo_zone', 'post') as $key => $value) {
				$data = array(
					'base_cost' 	=> $value['base_cost'],
					'base_weight'	=> $value['base_weight'],
					'added_cost'	=> $value['added_cost'],
					'added_weight'	=> $value['added_weight'],
					'max_weight'	=> $value['max_weight'],
					'free_amount'	=> $value['free_amount'],
					'message'		=> $value['message'],
					'status'		=> $value['status']
				);
				$this->database->query($this->database->parse("insert into setting set type = 'global', `group` = 'zoneplus', `key` = 'zoneplus_" . (int)$key . "_data', `value` = '?'", serialize($data)));
			}
		}
	}
	function get_zones(){
		$results = $this->database->getRows("select * from setting where `group` = 'zoneplus'");
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
	function get_weight_class(){
		$result = $this->database->getRow("select weight_class_id, title from weight_class where weight_class_id = '" . $this->config->get('config_weight_class_id') . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result['title'];
	}
}
?>