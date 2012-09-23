<?php //AdminModelOptions AlegroCart
class Model_Admin_Options extends Model {
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
	function insert_option($insert_id, $key, $name){
		$this->database->query("insert into `option` set option_id = '" . $insert_id . "', language_id = '" . $key . "', name = '" . $name . "'");
	}
	function delete_option(){
		$this->database->query("delete from `option` where option_id = '" . (int)$this->request->gethtml('option_id') . "'");
	}
	function delete_option_value(){
		$this->database->query("delete from `option_value` where option_id = '" . (int)$this->request->gethtml('option_id') . "'");
	}
	function delete_product_to_option(){
		$this->database->query("delete from `product_to_option` where option_id = '" . (int)$this->request->gethtml('option_id') . "'");
	}
	function get_page_option(){
		if (!$this->session->get('option.search')) {
      		$sql = "select option_id, name from `option` where language_id = '" . (int)$this->language->getId() . "'";
    	} else {
      		$sql = "select option_id, name from `option` where language_id = '" . (int)$this->language->getId() . "' and name like '?'";
    	}
    	if (in_array($this->session->get('option.sort'), array('name'))) {
      		$sql .= " order by " . $this->session->get('option.sort') . " " . (($this->session->get('option.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by name asc";
    	}
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('option.search') . '%'), $this->session->get('option.page'), $this->config->get('config_max_rows')));
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
	function get_option_description($language_id){
		$result = $this->database->getRow("select name from `option` where option_id = '" . (int)$this->request->gethtml('option_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function check_product_to_option(){
		$result = $this->database->getRow("select count(distinct product_id) as total from product_to_option where option_id = '" . (int)$this->request->gethtml('option_id') . "'");
		return $result;
	}
	function check_children($path){
		$results =  $this->database->getRows("select option_value_id from option_value where option_id = '" . $path . "'");
		return $results;
	}
	function get_optionToProducts(){
		$result = $this->database->getRows("select distinct p2o.product_id, pd.name from product_to_option p2o left join product_description pd on (p2o.product_id=pd.product_id) where option_id = '" . (int)$this->request->gethtml('option_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
}
?>
