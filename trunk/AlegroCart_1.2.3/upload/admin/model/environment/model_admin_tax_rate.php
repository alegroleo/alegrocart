<?php //AdminModelTaxRate AlegroCart
class Model_Admin_Tax_Rate extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_taxrate(){
		$sql = "insert into tax_rate set geo_zone_id = '?', tax_class_id = '?', priority = '?', rate = '?', description = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('geo_zone_id', 'post'), $this->request->gethtml('tax_class_id'), $this->request->gethtml('priority', 'post'), $this->request->gethtml('rate', 'post'), $this->request->gethtml('description', 'post')));
	}
	function update_taxrate(){
		$sql = "update tax_rate set geo_zone_id = '?', tax_class_id = '?', priority = '?', rate = '?', description = '?', date_modified = now() where tax_rate_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('geo_zone_id', 'post'), $this->request->gethtml('tax_class_id'), $this->request->gethtml('priority', 'post'), $this->request->gethtml('rate', 'post'), $this->request->gethtml('description', 'post'), $this->request->gethtml('tax_rate_id')));
	}
	function delete_taxrate(){
		$this->database->query("delete from tax_rate where tax_rate_id = '" . (int)$this->request->gethtml('tax_rate_id') . "' and tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "'");
	}
	function get_taxrate(){
		$result = $this->database->getRow("select distinct * from tax_rate where tax_rate_id = '" . (int)$this->request->gethtml('tax_rate_id') . "'");
		return $result;
	}
	function get_geozones(){
		$results = $this->database->getRows("select geo_zone_id, name from geo_zone order by name");
		return $results;
	}
	function get_page(){
		if (!$this->session->get('tax_rate.search')) {
			$sql = "select tr.tax_rate_id, tr.priority, gz.name, tr.rate from tax_class tc, tax_rate tr left join geo_zone gz on (tr.geo_zone_id = gz.geo_zone_id) where tr.tax_class_id = tc.tax_class_id and tc.tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "'";
		} else {
			$sql = "select tr.tax_rate_id, tr.priority, gz.name, tr.rate from tax_class tc, tax_rate tr left join geo_zone gz on (tr.geo_zone_id = gz.geo_zone_id) where tr.tax_class_id = tc.tax_class_id and tc.tax_class_id = '" . (int)$this->request->gethtml('tax_class_id') . "' and gz.name like '?'";
		}
		$sort = array('tr.priority', 'gz.name',	'tr.rate');
		if (in_array($this->session->get('tax_rate.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('tax_rate.sort') . " " . (($this->session->get('tax_rate.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by tr.priority, tc.title asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('tax_rate.search') . '%'), $this->session->get('tax_rate.' . $this->request->gethtml('tax_class_id') . '.page'), $this->config->get('config_max_rows')));
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