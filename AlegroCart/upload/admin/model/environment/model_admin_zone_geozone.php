<?php //AdminModelZonetoGeoZone AlegroCart
class Model_Admin_Zone_GeoZone extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_zoneToGeozone(){
		$sql = "insert into zone_to_geo_zone set country_id = '?', zone_id = '?', geo_zone_id = '?', date_added = now()";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('country_id', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('geo_zone_id')));
	}
	function update_zoneToGeozone(){
		$sql = "update zone_to_geo_zone set country_id = '?', zone_id = '?', date_modified = now() where zone_to_geo_zone_id = '?' and geo_zone_id = '?'";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('country_id', 'post'), $this->request->gethtml('zone_id', 'post'), $this->request->gethtml('zone_to_geo_zone_id'), $this->request->gethtml('geo_zone_id')));
	}
	function delete_zoneToGeozone(){
		$this->database->query("delete from zone_to_geo_zone where zone_to_geo_zone_id = '" . (int)$this->request->gethtml('zone_to_geo_zone_id') . "'");
	}
	function get_zoneToGeoID(){
		$result = $this->database->getRow("select distinct * from zone_to_geo_zone where zone_to_geo_zone_id = '" . (int)$this->request->gethtml('zone_to_geo_zone_id') . "'");
		return $result;
	}
	function get_geo_zone_id(){
		$result = $this->database->getRow("select distinct * from zone_to_geo_zone where geo_zone_id ='" . (int)$this->request->gethtml('geo_zone_id') . "'");
		return $result;
	}
	function deleteAllZones($geo_zone_id){
		$this->database->query("delete from zone_to_geo_zone where geo_zone_id ='" . $geo_zone_id . "'");
	}
	function insertCountry($geo_zone_id, $country_id){
		$sql = "insert into zone_to_geo_zone set country_id = '?', zone_id = '?', geo_zone_id = '?', date_added = now()";
      	$this->database->query($this->database->parse($sql, $country_id, 
		'0', $this->request->gethtml('geo_zone_id')));
	
	}
	function get_countries(){
		$results = $this->database->cache('country', "select country_id, name, country_status from country where country_status = '1' order by name");
		return $results;
	}
	function checkCountries($geo_zone_id){
		$result = count($this->database->getRows("select * from zone_to_geo_zone where geo_zone_id = '" . $geo_zone_id . "'"));
		return $result;
	}
	function get_zones(){
		$results = $this->database->cache('zone', "select * from zone where zone_status = '1' order by country_id, name");
		return $results;
	}
	function get_country_zones(){
		$results = $this->database->cache('zone-' . (int)$this->request->gethtml('country_id'), "select zone_id, name, zone_status from zone where country_id = '" . (int)$this->request->gethtml('country_id') . "' AND zone_status = '1' order by name");
		return $results;
	}
	function get_page(){
    	if (!$this->session->get('zone_to_geo_zone.search')) {
              $sql = "select a.zone_to_geo_zone_id, c.name, z.name as zone from zone_to_geo_zone a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'";
        } else {
              $sql = "select a.zone_to_geo_zone_id, c.name, z.name as zone from zone_to_geo_zone a left join country c on a.country_id = c.country_id left join zone z on a.zone_id = z.zone_id where a.geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "' and (c.name like '?' or z.name like '?')";
        }
        $sort = array('c.name',  'z.name');
        if (in_array($this->session->get('zone_to_geo_zone.sort'), $sort)) {
              $sql .= " order by " . $this->session->get('zone_to_geo_zone.sort') . " " . (($this->session->get('zone_to_geo_zone.order') == 'desc') ? 'desc' : 'asc');
        } else {
              $sql .= " order by c.name, z.name asc";
        }
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('zone_to_geo_zone.search') . '%', '%' . $this->session->get('zone_to_geo_zone.search') . '%'), $this->session->get('zone_to_geo_zone.' . $this->request->gethtml('geo_zone_id') . '.page'), $this->config->get('config_max_rows')));
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
	function get_geozone_name($geo_zone_id){
		$result = $this->database->getRow("select name from geo_zone where geo_zone_id = '" . $geo_zone_id . "'");
		return $result['name'];
	}
}
?>
