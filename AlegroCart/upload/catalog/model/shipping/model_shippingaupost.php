<?php //ModelShipping AlegroCart
class Model_ShippingAUPost extends Model{
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
	
	function get_aupost_status(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('australiapost_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')");
		return $result;
	
	}
}
?>