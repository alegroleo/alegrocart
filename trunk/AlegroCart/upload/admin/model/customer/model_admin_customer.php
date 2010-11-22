<?php //AdminModelCustomer AlegroCart
class Model_Admin_Customer extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_customer(){
		$sql = "insert into customer set firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', newsletter = '?', password = '?', status = '?', date_added = now()";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('firstname', 'post'), $this->request->gethtml('lastname', 'post'), $this->request->gethtml('email', 'post'), $this->request->gethtml('telephone', 'post'), $this->request->gethtml('fax', 'post'), $this->request->gethtml('newsletter', 'post'), md5($this->request->gethtml('password', 'post')), $this->request->gethtml('status', 'post')));
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function insert_address($customer_id){
		$sql = "insert into address set customer_id = '?', company = '?', firstname = '?', lastname = '?', address_1 = '?', address_2 = '?', postcode = '?', city = '?', zone_id = '?', country_id = '?'";
		$this->database->query($this->database->parse($sql, $customer_id, $this->request->gethtml('company', 'post'), $this->request->gethtml('firstname', 'post'), $this->request->gethtml('lastname', 'post'), $this->request->gethtml('address_1', 'post'), $this->request->gethtml('address_2', 'post'), $this->request->gethtml('postcode', 'post'), $this->request->gethtml('city', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('country_id', 'post')));
	}
	function insert_default_address($customer_id){
		$this->database->query("update customer set address_id = '" . (int)$this->database->getLastId() . "' where customer_id = '" . (int)$customer_id . "'");
	}
	function update_customer(){
		$sql = "update customer set firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', newsletter = '?', status = '?' where customer_id = '?'";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('firstname', 'post'), $this->request->gethtml('lastname', 'post'), $this->request->gethtml('email', 'post'), $this->request->gethtml('telephone', 'post'), $this->request->gethtml('fax', 'post'), $this->request->gethtml('newsletter', 'post'), $this->request->gethtml('status', 'post'), (int)$this->request->gethtml('customer_id')));
	}
	function update_address(){
		$sql = "update address set company = '?', firstname = '?', lastname = '?', address_1 = '?', address_2 = '?', postcode = '?', city = '?', zone_id = '?', country_id = '?' where address_id  = '?' and customer_id = '?'";
      		$this->database->query($this->database->parse($sql, $this->request->gethtml('company', 'post'), $this->request->gethtml('firstname', 'post'), $this->request->gethtml('lastname', 'post'), $this->request->gethtml('address_1', 'post'), $this->request->gethtml('address_2', 'post'), $this->request->gethtml('postcode', 'post'), $this->request->gethtml('city', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('country_id', 'post'), (int)$this->request->gethtml('address_id','post'), (int)$this->request->gethtml('customer_id')));
	}
	function delete_customer(){
		$this->database->query("delete from customer where customer_id = '" . (int)$this->request->gethtml('customer_id') . "'");
      	$this->database->query("delete from address where customer_id = '" . (int)$this->request->gethtml('customer_id') . "'"); 
	}
	function get_customer(){
		$result = $this->database->getRow("select distinct * from customer where customer_id = '" . (int)$this->request->gethtml('customer_id') . "'");
		return $result;
	}
	function get_address($address_id){
		$result = $this->database->getRow("select distinct * from address where address_id = '" . (int)$address_id . "' and customer_id = '" . (int)$this->request->gethtml('customer_id') . "'");
		return $result;
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
	function get_page(){
		if (!$this->session->get('customer.search')) {
      		$sql = "select customer_id, lastname, firstname, status, date_added from customer";
		} else {
      		$sql = "select customer_id, lastname, firstname, status, date_added from customer where lastname like '?' or firstname like '?'";
    	}
		$sort = array('lastname', 'firstname', 'status', 'date_added');
		if (in_array($this->session->get('customer.sort'), $sort)) {
      		$sql .= " order by " . $this->session->get('customer.sort') . " " . (($this->session->get('customer.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by lastname, firstname asc";
    	}
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('customer.search') . '%', '%' . $this->session->get('customer.search') . '%'), $this->session->get('customer.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_pagination(){
    	$page_data = array();
    	for ($i = 1; $i <= $this->get_pages(); $i++) {
      		$page_data[] = array(
        		'text'  => $this->language->get('text_pages', $i, $this->get_pages()),
        		'value' => $i
      		);
    	}
		return $page_data;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
}
?>