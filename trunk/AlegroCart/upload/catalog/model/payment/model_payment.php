<?php //ModelPayment AlegroCart
class Model_Payment extends Model{
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
	function get_paypalstatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('paypal_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_ccavenuestatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('ccavenue_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_paymatestatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('paymate_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_googlestatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('google_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_authnetaimstatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('authnetaim_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_codstatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('cod_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_moneyorderstatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('moneyorder_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}
	function get_chequestatus(){
		$result = $this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . (int)$this->config->get('cheque_geo_zone_id') . "' and country_id = '" . (int)$this->address->getCountryId($this->session->get('payment_address_id')) . "' and (zone_id = '" . (int)$this->address->getZoneId($this->session->get('payment_address_id')) . "' or zone_id = '0')");
		return $result;
	}

	function get_currency($currency){
		$result = $this->database->getRow("select value from currency where code = '" . $currency . "'");
		return $result;
	}
	function get_orderstatus_id($status, $language_id){
		$result = $this->database->getRow("select order_status_id from order_status where name = '" . $status . "' and `language_id` = '" . $language_id . "'");
		return $result;
	}
	function get_order_id($reference){
		$result = $this->database->getrow("select order_id from order where reference = '" . $reference ."'");
		return $result;
	}
	function update_order_status($orderStatusId, $orderReference){
		$this->database->query("update order set order_status_id = '" . $orderStatusId . "' where reference = '" . $orderReference . "'");
	}
	function update_order_status_override($finalStatusId,$reference){
		$result = $this->database->countAffected($this->database->query("update order set order_status_id = '" . $finalStatusId . "' where reference = '" . $reference . "'"));
		return $result;
	}
	function update_order_status_paidunconfirmed($finalStatusId, $reference, $paidUnconfirmedStatusId){
		$result = $this->database->countAffected($this->database->query("update order set order_status_id = '" . $finalStatusId . "' where reference = '" . $reference . "' and order_status_id = '" . $paidUnconfirmedStatusId . "'"));
		return $result;
	}
	function update_order_history($order_id, $finalStatusId,$comment){
		$this->database->query("insert into order_history set order_id = '" . $order_id . "', order_status_id = '" . $finalStatusId . "', date_added = now(), notify = '0', comment = '" . $comment . "'");
	}
	function delete_order_google($orderReference){
		$this->database->query("delete from order_google where order_reference = '" . $orderReference . "'");
	}
	function insert_order_google($orderReference, $orderNumber, $orderTotal){
		$this->database->query("insert into order_google set order_reference = '" . $orderReference . "', order_number = '" . $orderNumber . "', total = '" . $orderTotal . "'");
	}
	function get_google_order($orderNumber){
		$result = $this->database->getRow("select order_reference, total from order_google where order_number = '" . $orderNumber . "'");
		return $result;
	}
}
?>