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
		$sql = "insert into country set name = '?', country_status = '?', iso_code_2 = '?', iso_code_3 = '?', address_format = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('country_status', 'post'), $this->request->gethtml('iso_code_2', 'post'), $this->request->gethtml('iso_code_3', 'post'), $this->request->gethtml('address_format', 'post')));
	}

	function update_country(){
		$sql = "update country set name = '?', country_status = '?', iso_code_2 = '?', iso_code_3 = '?', address_format = '?' where country_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('country_status', 'post'), $this->request->gethtml('iso_code_2', 'post'), $this->request->gethtml('iso_code_3', 'post'), $this->request->gethtml('address_format', 'post'), $this->request->gethtml('country_id')));
	}

	function delete_country(){
		$this->database->query("delete from country where country_id = '" . (int)$this->request->gethtml('country_id') . "'");
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM country_log cl INNER JOIN user u ON (u.user_id = cl.trigger_modifier_id) WHERE country_id = '" . (int)$this->request->gethtml('country_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function get_modified_log($date_modified){
		$result = $this->database->getRow("SELECT cl.*, CONCAT(firstname, ' ', lastname) AS modifier FROM country_log cl INNER JOIN user u ON (u.user_id = cl.trigger_modifier_id) WHERE country_id = '" . (int)$this->request->gethtml('country_id') . "' AND date_modified =  '" . $date_modified . "'");
		return $result;
	}

	function set_status($status){
		$zone_to_geo_zones = $this->get_zone_to_geo_zoneCountries();
		$zone_to_geo_zonecountry = array();
		foreach ($zone_to_geo_zones as $zone_to_geo_zone){
			$zone_to_geo_zonecountry[] = $zone_to_geo_zone['country_id'];
		}
		$zone_to_geo_zone_list = implode(',', $zone_to_geo_zonecountry);
		$this->database->query("UPDATE country SET country_status = '" . (int)$status . "' WHERE country_id != '" . $this->config->get('config_country_id')  ."' AND country_id NOT IN (" . $zone_to_geo_zone_list . ")");
		$this->database->query("UPDATE zone SET zone_status = '" . (int)$status . "' WHERE country_id != '" . $this->config->get('config_country_id')  ."' AND country_id NOT IN (" . $zone_to_geo_zone_list . ")");
	}

	function check_status(){
		$zone_to_geo_zones = $this->get_zone_to_geo_zoneCountries();
		$zone_to_geo_zonecountry = array();
		foreach ($zone_to_geo_zones as $zone_to_geo_zone){
			$zone_to_geo_zonecountry[] = $zone_to_geo_zone['country_id'];
		}
		if (!in_array($this->config->get('config_country_id'), $zone_to_geo_zonecountry)) {
		$zone_to_geo_zonecountry[] = $this->config->get('config_country_id'); 
		}
		$result = count($this->database->getRows("SELECT country_status FROM country WHERE country_status = '1'"));
		return $result>count($zone_to_geo_zonecountry) ? TRUE : FALSE;
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
		$result = $this->database->getRow("SELECT count(*) AS total FROM address a LEFT JOIN customer c ON (c.address_id = a.address_id) WHERE a.country_id = '" . (int)$this->request->gethtml('country_id') . "' AND a.customer_id !='0' AND c.status ='1'");
		return $result;
	}

	function check_zone(){
		$result = $this->database->getRow("SELECT count(*) AS total FROM zone WHERE country_id = '" . (int)$this->request->gethtml('country_id') . "' AND zone_status='1'");
		return $result;
	}

	function update_zones(){
		$this->database->query("UPDATE zone SET zone_status = '" . (int)$this->request->gethtml('country_status', 'post') . "' WHERE country_id = '" . (int)$this->request->gethtml('country_id') ."'");
	}

	function check_zone_to_geo(){
		$result = $this->database->getRow("SELECT count(*) AS total FROM zone_to_geo_zone WHERE country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}

	function change_country_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update country set country_status = '?' where country_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
		$this->database->query("UPDATE zone SET zone_status = '" . (int)$new_status . "' WHERE country_id = '" . (int)$status_id ."'");
	}

	function get_countryToAddress(){
		$result = $this->database->getRows("SELECT a.customer_id, a.firstname, a.lastname FROM address a LEFT JOIN customer c ON (c.address_id = a.address_id) WHERE a.country_id = '" . (int)$this->request->gethtml('country_id') . "'  AND a.customer_id !='0' AND c.status='1'");
		return $result;
	}

	function get_countryToZone(){
		$result = $this->database->getRows("SELECT zone_id, name FROM zone WHERE country_id = '" . (int)$this->request->gethtml('country_id') . "' AND zone_status='1'");
		return $result;
	}

	function get_countryToZoneToGeoZone(){
		$result = $this->database->getRows("SELECT distinct z2g.geo_zone_id, gz.name FROM zone_to_geo_zone z2g LEFT JOIN geo_zone gz ON (z2g.geo_zone_id=gz.geo_zone_id) WHERE country_id = '" . (int)$this->request->gethtml('country_id') . "'");
		return $result;
	}

	function get_vendorCountries(){
		$result = $this->database->getRows("SELECT distinct country_id FROM vendor_address va LEFT JOIN vendor v ON (v.address_id = va.address_id) WHERE v.status='1'");
		return $result;
	}

	function get_customerCountries(){
		$result = $this->database->getRows("SELECT distinct country_id FROM address a LEFT JOIN customer c ON (c.address_id = a.address_id) WHERE a.customer_id !='0' AND c.status='1'");
		return $result;
	}

	function get_zone_to_geo_zoneCountries(){
		$result = $this->database->getRows("SELECT distinct country_id FROM zone_to_geo_zone");
		return $result;
	}

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
}
?>
