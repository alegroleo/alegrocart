<?php //AdminModelGeoZone AlegroCart
class Model_Admin_GeoZone extends Model {

	function __construct(&$locator) {
		$this->config	=& $locator->get('config');
		$this->database	=& $locator->get('database');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session	=& $locator->get('session');
	}

	function insert_geozone(){
		$sql = "insert into geo_zone set name = '?', description = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('description', 'post')));
	}

	function update_geozone(){
		$sql = "update geo_zone set name = '?', description = '?' where geo_zone_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('description', 'post'), $this->request->gethtml('geo_zone_id')));
	}

	function delete_geozone(){
		$this->database->query("delete from geo_zone where geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
		$this->database->query("delete from zone_to_geo_zone where zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
	}

	function get_geozone(){
		$result = $this->database->getRow("select distinct * from geo_zone where geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
		return $result;
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM geo_zone_log gzl INNER JOIN user u ON (u.user_id = gzl.trigger_modifier_id) WHERE geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function get_modified_log($date_modified){
		$result = $this->database->getRow("SELECT gzl.*, CONCAT(firstname, ' ', lastname) AS modifier FROM geo_zone_log gzl INNER JOIN user u ON (u.user_id = gzl.trigger_modifier_id) WHERE geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "' AND date_modified =  '" . $date_modified . "'");
		return $result;
	}

	function get_page(){
		if (!$this->session->get('geo_zone.search')) {
			$sql = "select geo_zone_id, name, description from geo_zone";
		} else {
			$sql = "select geo_zone_id, name, description from geo_zone where name like '?'";
		}
		$sort = array('name', 'description');
		if (in_array($this->session->get('geo_zone.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('geo_zone.sort') . " " . (($this->session->get('geo_zone.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('geo_zone.search') . '%'), $this->session->get('geo_zone.page'), $this->config->get('config_max_rows')));
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

	function check_tax(){
		$result = $this->database->getRow("select count(*) as total from tax_rate where geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
		return $result;
	}

	function check_zoneToGeozone(){
		$result = $this->database->getRow("select count(*) as total from zone_to_geo_zone where geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
		return $result;
	}

	function check_children($path){
		$results =  $this->database->getRows("select zone_to_geo_zone_id from zone_to_geo_zone where geo_zone_id = '" . $path . "'");
		return $results;
	}

	function get_geozoneToZoneToGeoZone(){
		$result = $this->database->getRows("select c.name as countryname, z2g.zone_to_geo_zone_id, z.name as zonename from country c left join zone_to_geo_zone z2g on (c.country_id=z2g.country_id) left join zone z on (z2g.zone_id=z.zone_id) where geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
		return $result;
	}

	function get_geozoneToTaxRate(){
		$result = $this->database->getRows("select tr.tax_rate_id, tr.rate, tc.title, tr.tax_class_id from tax_rate tr left join tax_class tc on (tr.tax_class_id=tc.tax_class_id) where geo_zone_id = '" . (int)$this->request->gethtml('geo_zone_id') . "'");
		return $result;
	}

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
}
?>
