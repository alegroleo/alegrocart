<?php //AdminModelLanguage AlegroCart
class Model_Admin_Language extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_language(){
		$sql = "insert into language set name = '?', code = '?', directory = '?', filename= '?', image = '?', sort_order = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('directory', 'post'), $this->request->gethtml('filename', 'post'), $this->request->gethtml('image', 'post'), $this->request->gethtml('sort_order', 'post')));
	}
	function update_language(){
		$sql = "update language set name = '?', code = '?', directory = '?', filename= '?', image = '?', sort_order = '?' where language_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('directory', 'post'), $this->request->gethtml('filename', 'post'), $this->request->gethtml('image', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('language_id')));
	}
	function delete_language(){
		$this->database->query("delete from language where language_id = '" . (int)$this->request->gethtml('language_id') . "'");
	}
	function get_language(){
		$result = $this->database->getRow("select distinct * from language where language_id = '" . (int)$this->request->gethtml('language_id') . "'");
		return $result;
	}
	function check_language_code(){
		$result = $this->database->getRow("select distinct code from language where language_id = '" . (int)$this->request->gethtml('language_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('language.search')) {
			$sql = "select language_id, name, code, sort_order from language";
		} else {
			$sql = "select language_id, name, code, sort_order from language where name like '?'";
		}
		$sort = array('name', 'code', 'sort_order');
		if (in_array($this->session->get('language.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('language.sort') . " " . (($this->session->get('language.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('language.search') . '%'), $this->session->get('language.page'), $this->config->get('config_max_rows')));
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