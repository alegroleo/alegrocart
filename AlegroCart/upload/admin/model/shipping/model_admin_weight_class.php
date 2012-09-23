<?php //AdminModelWeightClass AlegroCart
class Model_Admin_Weight_Class extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_weight_class($insert_id, $key, $title, $unit){
		$sql = "insert into weight_class set weight_class_id = '?', language_id = '?', title = '?', unit = '?'";
		$this->database->query($this->database->parse($sql, (int)$insert_id, $key, $title, $unit));
	}
	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
	function insert_weight_rule($insert_id, $key, $value){
		$sql = "insert into weight_rule set from_id = '?', to_id = '?', rule = '?'";
		$this->database->query($this->database->parse($sql, (int)$insert_id, $key, $value));
	}
	function delete_weight_class(){
		$this->database->query("delete from weight_class where weight_class_id = '" . (int)$this->request->gethtml('weight_class_id') . "'");
	}
	function delete_weight_rule(){
		$this->database->query("delete from weight_rule where from_id = '" . (int)$this->request->gethtml('weight_class_id') . "'");
	}
	function get_weight_class($language_id){
		$result = $this->database->getRow("select title, unit from weight_class where weight_class_id = '" . (int)$this->request->gethtml('weight_class_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_weight_classes(){
		$results = $this->database->getRows("select * from weight_class where language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_weight_rule($weight_class_id){
		$result = $this->database->getRow("select * from weight_rule where from_id = '" . (int)$this->request->gethtml('weight_class_id') . "' and to_id = '" . (int)$weight_class_id . "'");
		return $result;
	}
	function check_products(){
		$result = $this->database->getRow("select count(*) as total from product where weight_class_id = '" . (int)$this->request->gethtml('weight_class_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('weight_class.search')) {
			$sql = "select weight_class_id, title, unit from weight_class where language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select weight_class_id, title, unit from weight_class where language_id = '" . (int)$this->language->getId() . "' and title like '?'";
		}
		$sort = array('title', 'unit');
		if (in_array($this->session->get('weight_class.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('weight_class.sort') . " " . (($this->session->get('weight_class.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by title asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('weight_class.search') . '%'), $this->session->get('weight_class.page'), $this->config->get('config_max_rows')));
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
	function get_weightclassToProducts(){
		$result = $this->database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id=pd.product_id) where weight_class_id = '" . (int)$this->request->gethtml('weight_class_id') . "' and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
}
?>
