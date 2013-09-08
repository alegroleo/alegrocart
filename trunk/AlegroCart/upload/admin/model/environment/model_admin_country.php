<?php //AdminModelCountry AlegroCart
class Model_Admin_Country extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_country(){
		$sql = "insert into country set name = '?', country_status = '?', iso_code_2 = '?', iso_code_3 = '?', address_format = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('country_status', 'post'), $this->request->gethtml('iso_code_2', 'post'), $this->request->gethtml('iso_code_3', 'post'), $this->request->gethtml('address_format', 'post')));
	}
	function update_country(){
		$sql = "update country set name = '?', country_status = '?', iso_code_2 = '?', iso_code_3 = '?', address_format = '?' where country_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('country_status', 'post'), $this->request->gethtml('iso_code_2', 'post'), $this->request->gethtml('iso_code_3', 'post'), $this->request->gethtml('address_format', 'post'), $this->request->gethtml('country_id')));
	}
	function set_status($status){
		$this->database->query("update country set country_status = '" . $status . "' where country_id != '" . $this->config->get('config_country_id')  ."'");
	}
	function delete_country(){
		$this->database->query("delete from country where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
	}
	function check_status(){
		$result = count($this->database->getRows("select country_status from country where country_status = '1'"));
		return $result>1 ? TRUE : FALSE;
	}
	function get_country_info(){
		$result = $this->database->getRow("select distinct * from country where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('country.search')) {
			$sql = "select country_id, name, country_status, iso_code_2, iso_code_3 from country";
		} else {
			$sql = "select country_id, name, country_status, iso_code_2, iso_code_3 from country where name like '?' or iso_code_2 like '?' or iso_code_3 like '?'";
		}
		$sort = array('name', 'country_status',	'iso_code_2', 'iso_code_3');
		if (in_array($this->session->get('country.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('country.sort') . " " . (($this->session->get('country.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('country.search') . '%', $this->session->get('country.search'), $this->session->get('country.search')), $this->session->get('country.page'), $this->config->get('config_max_rows')));
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
	function check_address(){
		$result = $this->database->getRow("select count(*) as total from address where country_id = '" . (int)$this->request->gethtml('country_id') . "' and customer_id !='0'");
		return $result;
	}
	function check_zone(){
		$result = $this->database->getRow("select count(*) as total from zone where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}
	function check_zone_to_geo(){
		$result = $this->database->getRow("select count(*) as total from zone_to_geo_zone where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}
	function check_vendor(){
		$result = $this->database->getRow("select count(*) as total from address where country_id = '" . (int)$this->request->gethtml('country_id') . "' and vendor_id !='0'");
		return $result;
	}
	function change_country_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update country set country_status = '?' where country_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}
	function get_countryToAddress(){
		$result = $this->database->getRows("select customer_id, firstname, lastname from address where country_id = '" . (int)$this->request->gethtml('country_id') . "'  and customer_id !='0'");
		return $result;
	}
	function get_countryToZone(){
		$result = $this->database->getRows("select zone_id, name from zone where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}
	function get_countryToZoneToGeoZone(){
		$result = $this->database->getRows("select distinct z2g.geo_zone_id, gz.name from zone_to_geo_zone z2g left join geo_zone gz on (z2g.geo_zone_id=gz.geo_zone_id) where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}
	function get_countryToVendor(){
		$result = $this->database->getRows("select a.vendor_id, v.name from vendor v left join address a on (a.vendor_id=v.vendor_id) where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}
	function get_vendors(){
		$result = $this->database->getRows("select distinct country_id from address where vendor_id !='0'");
		return $result;
	}
}
?>
