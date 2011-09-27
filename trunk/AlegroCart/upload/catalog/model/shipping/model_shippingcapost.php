<?php //ModelShipping AlegroCart
class Model_ShippingCAPost extends Model{
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
	function get_canpost_status(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('canadapost_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('shipping_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('shipping_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_canpost_dimension(){
		$result = $this->database->getRow("select `dimension_id` from `dimension` where unit = 'cm' and language_id = '" . (int)$this->language->getId() . "'");
		return $result['dimension_id'];
	}
	function get_canpost_weight(){
		$result = $this->database->getRow("select `weight_class_id` from `weight_class` where unit = 'kg' and language_id = '" . (int)$this->language->getId() . "'");
		return $result['weight_class_id'];
	}
}
?>