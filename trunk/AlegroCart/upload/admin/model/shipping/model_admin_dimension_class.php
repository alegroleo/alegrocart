<?php //AdminModelDimensionClass AlegroCart
class Model_Admin_Dimension_Class extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_dimension($insert_id, $key, $title, $unit){
		$sql = "insert into dimension set dimension_id = '?', language_id = '?', title = '?', unit = '?', type_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$insert_id, $key, $title, $unit, (int)$this->request->gethtml('type_id','post')));
	}
	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
	function insert_dimension_rule($insert_id, $key, $value){
		$sql = "insert into dimension_rule set from_id = '?', to_id = '?', rule = '?', type_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$insert_id, $key, $value, (int)$this->request->gethtml('type_id','post')));
	}
	function delete_dimension_class(){
		$this->database->query("delete from dimension where dimension_id = '" . (int)$this->request->gethtml('dimension_id') . "'");
	}
	function check_products(){
		$result = $this->database->getRow("select count(*) as total from product where dimension_id = '" . (int)$this->request->gethtml('dimension_id') . "'");
		return $result;
	}
	function delete_dimension_rule(){
		$this->database->query("delete from dimension_rule where from_id = '" . (int)$this->request->gethtml('dimension_id') . "'");
	}
	function get_dimension_rule($dimension_id){
		$results = $this->database->getRow("select * from dimension_rule where from_id = '" . (int)$this->request->gethtml('dimension_id') . "' and to_id = '" . (int)$dimension_id . "'");
		return $results;
	}
	function get_types(){
		$results = $this->database->getRows("select * from dimension_type");
		return $results;
	}
	function get_type($type_id){
		$result = $this->database->getRow("select * from dimension_type where type_id = '" . $type_id . "'");
		return $result;
	}
	function get_dimension_classes($type_id){
		$results = $this->database->getRows("select * from dimension where type_id = '" . $type_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_dimension_class($language_id){
		$result = $this->database->getRow("select title, unit, type_id from dimension where dimension_id = '" . (int)$this->request->gethtml('dimension_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('dimension.search')) {
			$sql = "select d.dimension_id, d.unit, d.title, d.type_id, dt.type_name from dimension d left join dimension_type dt on (d.type_id = dt.type_id) where d.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select d.dimension_id, d.unit, d.title, d.type_id, dt.type_name from dimension d left join dimension_type dt on (d.type_id = dt.type_id) where d.language_id = '" . (int)$this->language->getId() . "' and title like '?'";
		}
		$sort = array('d.title', 'd.unit', 'dt.type_name');
		if (in_array($this->session->get('dimension.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('dimension.sort') . " " . (($this->session->get('dimension.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by dt.type_name, d.title asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('dimension.search') . '%'), $this->session->get('dimension.page'), $this->config->get('config_max_rows')));
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
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
}
?>