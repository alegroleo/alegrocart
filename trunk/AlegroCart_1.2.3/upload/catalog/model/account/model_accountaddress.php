<?php //ModelAccountAddress AlegroCart
class Model_AccountAddress extends Model{
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function insert_default_address($customer_id){
		$this->database->query("update customer set address_id = '" . (int)$this->database->getLastId() . "' where customer_id = '" . (int)$customer_id . "'");
	
	}
	function insert_address($customer_id){
		$sql = "insert into address set customer_id = '?', company = '?', firstname = '?', lastname = '?', address_1 = '?', address_2 = '?', postcode = '?', city = '?', zone_id = '?', country_id = '?'";
		$this->database->query($this->database->parse($sql, $customer_id, $this->request->sanitize('company', 'post'), $this->request->sanitize('firstname', 'post'), $this->request->sanitize('lastname', 'post'), $this->request->sanitize('address_1', 'post'), $this->request->sanitize('address_2', 'post'), $this->request->sanitize('postcode', 'post'), $this->request->sanitize('city', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('country_id', 'post')));
	}
	function update_default_address($customer_id){ 
		$this->database->query("update customer set address_id = '" . (int)$this->request->gethtml('address_id') . "' where customer_id = '" . (int)$customer_id . "'");
	}
	function update_address($customer_id){
		$sql = "update address set company = '?', firstname = '?', lastname = '?', address_1 = '?', address_2 = '?', postcode = '?', city = '?', zone_id = '?', country_id = '?' where address_id  = '?' and customer_id = '?'";
      		$this->database->query($this->database->parse($sql, $this->request->sanitize('company', 'post'), $this->request->sanitize('firstname', 'post'), $this->request->sanitize('lastname', 'post'), $this->request->sanitize('address_1', 'post'), $this->request->sanitize('address_2', 'post'), $this->request->sanitize('postcode', 'post'), $this->request->sanitize('city', 'post'), $this->request->sanitize('zone_id', 'post'), $this->request->gethtml('country_id', 'post'), $this->request->gethtml('address_id'), $customer_id));
	}
	function delete_address($customer_id){
		$this->database->query("delete from address where address_id = '" . (int)$this->request->gethtml('address_id') . "' and customer_id = '" . (int)$customer_id . "'");
	}
	function get_addresses($customer_id){
		$results = $this->database->getRows("select *, c.name as country, z.name as zone from address a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.customer_id = '" . (int)$customer_id . "'");
		return $results;
	}
	function get_address($address_id,$customer_id){
		$results = $this->database->getRow("select distinct * from address where address_id = '" . (int)$address_id . "' and customer_id = '" . (int)$customer_id . "'");
		return $results;
	}
	function get_last_ID(){
		return $this->database->getLastId();
	}
	function get_countries(){
		$results = $this->database->cache('country', "select * from country where country_status = '1'  order by name");
		return $results;
	}
	function get_zones(){ 
		$results = $this->database->cache('zone', "select * from zone where zone_status = '1' order by country_id, name");
		return $results;
	}
	function return_zones($country_id){
		$results = $this->database->cache('zone-' . (int)$country_id, "select zone_id, name, zone_status from zone where country_id = '" . (int)$country_id . "' AND zone_status = '1' order by name");
		return $results;
	}
	function check_address_count($customer_id){
		$result = $this->database->getRow("select count(*) as total from address where customer_id = '" . (int)$customer_id . "'");
		return $result;
	}
}
?>