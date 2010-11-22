<?php //ModelShipping AlegroCart
class Model_Shipping extends Model{
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_flatstatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_itemstatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('item_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_geo_zones(){
		$results = $this->database->cache('geo_zone', "select * from geo_zone order by name");
		return $results;
	}
	function get_zonestatus($geo_zone_id){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$geo_zone_id . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')");
		return $result;
	}
}	
?>