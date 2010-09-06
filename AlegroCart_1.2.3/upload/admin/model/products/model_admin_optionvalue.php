<?php //AdminModelOptionValue AlegroCart
class Model_Admin_Optionvalue extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}	
	function insert_option_value($insert_id, $key, $name){
		$this->database->query("insert into option_value set option_value_id = '" . $insert_id . "', option_id = '" . $this->request->gethtml('option_id') . "', language_id = '" . $key . "', name = '" . $name . "'");
	}
	function updatedelete_option_value(){
		$this->database->query("delete from `option_value` where option_value_id = '" . (int)$this->request->gethtml('option_value_id') . "'");
	}
	function delete_option_value(){
		$this->database->query("delete from `option_value` where option_value_id = '" . (int)$this->request->gethtml('option_value_id') . "' and option_id = '" . (int)$this->request->gethtml('option_id') . "'");
	  		$this->database->query("delete from `product_to_option` where option_id = '" . (int)$this->request->gethtml('option_id') . "' and option_value_id = '" . (int)$this->request->gethtml('option_value_id') . "'");
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function get_option_value_description($language_id){
		$result = $this->database->getRow("select name from option_value where option_value_id = '" . (int)$this->request->gethtml('option_value_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function check_product_to_option(){
		$result = $this->database->getRow("select count(*) as total from product_to_option where option_value_id = '" . (int)$this->request->gethtml('option_value_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('option_value.search')) {
      		$sql = "select option_value_id, name from option_value where language_id = '" . (int)$this->language->getId() . "' and option_id = '" . (int)$this->request->gethtml('option_id') . "'";
    	} else {
      		$sql = "select option_value_id, name from option_value where language_id = '" . (int)$this->language->getId() . "' and option_id = '" . (int)$this->request->gethtml('option_id') . "' and name like '?'";
    	}
    	if (in_array($this->session->get('option_value.sort'), array('name'))) {
      		$sql .= " order by " . $this->session->get('option_value.sort') . " " . (($this->session->get('option_value.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by name asc";
    	}
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('option_value.search') . '%'), $this->session->get('option_value.' . $this->request->gethtml('option_id') . '.page'), $this->config->get('config_max_rows')));
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