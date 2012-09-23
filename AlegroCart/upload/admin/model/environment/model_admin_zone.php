<?php //AdminModelZone AlegroCart
class Model_Admin_Zone extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_zone(){
		$sql = "insert into zone set name = '?', zone_status = '?',code = '?', country_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('zone_status','post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('country_id', 'post')));
	}
	function update_zone(){
		$sql = "update zone set name = '?', zone_status = '?', code = '?', country_id = '?' where zone_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('zone_status','post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('country_id', 'post'), $this->request->gethtml('zone_id')));
	}
	function delete_zone(){
		$this->database->query("delete from zone where zone_id = '" . (int)$this->request->gethtml('zone_id') . "'");
	}
	function get_zone(){
		$result = $this->database->getRow("select distinct * from zone where zone_id = '" . (int)$this->request->gethtml('zone_id') . "'");
		return $result;
	}
	function get_countries(){
		$results = $this->database->getRows("select country_id, name, country_status from country order by name");
		return $results;
	}
	function get_page(){
		if (!$this->session->get('zone.search')) {
			$sql = "select z.zone_id, z.name, z.zone_status, z.code, c.name as country from zone z left join country c on (z.country_id = c.country_id)";
		} else {
			$sql = "select z.zone_id, z.name, z.zone_status, z.code, c.name as country from zone z left join country c on (z.country_id = c.country_id) where c.name like '?' or z.name like '?'";
		}
		$sort = array('z.name',	'z.zone_status', 'z.code', 'c.name');
		if (in_array($this->session->get('zone.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('zone.sort') . " " . (($this->session->get('zone.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by c.name asc, z.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('zone.search') . '%','%' . $this->session->get('zone.search') . '%') , $this->session->get('zone.page'), $this->config->get('config_max_rows')));
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
		$result = $this->database->getRow("select count(*) as total from address where zone_id = '" . (int)$this->request->gethtml('zone_id') . "'");
		return $result;
	}
	function check_zone_to_geo(){
		$result = $this->database->getRow("select count(*) as total from zone_to_geo_zone where (zone_id = '" . (int)$this->request->gethtml('zone_id') . "') or (country_id = '" .  $this->request->gethtml('country_id', 'post') . "' and  zone_id = '0')");
		return $result;
	}
	function change_zone_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update zone set zone_status = '?' where zone_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}
	function get_zoneToAddress(){
		$result = $this->database->getRows("select customer_id, firstname, lastname from address where zone_id = '" . (int)$this->request->gethtml('zone_id') . "'");
		return $result;
	}
	function get_zoneToZoneToGeoZone(){
		$result = $this->database->getRows("select distinct z2g.geo_zone_id, gz.name from zone_to_geo_zone z2g left join geo_zone gz on (z2g.geo_zone_id=gz.geo_zone_id) where zone_id = '" . (int)$this->request->gethtml('zone_id') . "'");
		return $result;
	}
}
?>
