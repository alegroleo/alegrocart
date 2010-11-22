<?php //AdminModelInformation AlegroCart
class Model_Admin_Information extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_information(){
		$this->database->query("insert into information set sort_order = '" . (int)$this->request->gethtml('sort_order', 'post') . "'");
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function insert_description($insert_id){
		foreach ($this->request->get('language', 'post') as $key => $value) {
			$sql = "insert into information_description set information_id = '?', language_id = '?', title = '?', description = '?'";
			$this->database->query($this->database->parse($sql, $insert_id, $key, $value['title'], $value['description']));
		}
	}
	function update_information(){
		$sql = "update information set sort_order = '?' where information_id = '?'";
			$this->database->query($this->database->parse($sql, (int)$this->request->gethtml('sort_order', 'post'), (int)$this->request->gethtml('information_id')));
	}
	function delete_information(){
		$this->database->query("delete from information where information_id = '" . (int)$this->request->gethtml('information_id') . "'");
	}
	function delete_description(){
		$this->database->query("delete from information_description where information_id = '" . (int)$this->request->gethtml('information_id') . "'");
	}
	function get_page(){
		if (!$this->session->get('information.search')) {
			$sql = "select i.information_id, id.title, i.sort_order from information i left join information_description id on i.information_id = id.information_id where id.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select i.information_id, id.title, i.sort_order from information i left join information_description id on i.information_id = id.information_id where id.language_id = '" . (int)$this->language->getId() . "' and id.title like '?'";
		}
		$sort = array('id.title', 'i.sort_order');
		if (in_array($this->session->get('information.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('information.sort') . " " . (($this->session->get('information.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by id.title asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('information.search') . '%'), $this->session->get('information.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function get_information(){
		$result = $this->database->getRow("select distinct * from information where information_id = '" . (int)$this->request->gethtml('information_id') . "'");
		return $result;
	}
	function get_description($language_id){
		$result = $this->database->getRow("select title, description from information_description where information_id = '" . (int)$this->request->gethtml('information_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
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