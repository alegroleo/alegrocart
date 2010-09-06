<?php //AdminModelSEO AlegroCart
class Model_Admin_UrlAlias extends Model {
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session  =& $locator->get('session');
	}
	function insert_url(){
		$this->database->query("insert into url_alias set query = '" . $this->request->get('query', 'post') . "' , alias = '" . $this->request->get('alias', 'post') . "'");
	}
	function update_url(){
		$this->database->query("update url_alias set query = '" . $this->request->get('query', 'post') . "' , alias = '" . $this->request->get('alias', 'post') . "' where url_alias_id = '" . (int)$this->request->get('url_alias_id') . "'");
	}
	function delete_url(){
		$this->database->query("delete from url_alias where url_alias_id = '" . (int)$this->request->get('url_alias_id') . "'");
	}
	function get_url_alias(){
		$result = $this->database->getRow("select distinct query, alias from url_alias where url_alias_id = '" . (int)$this->request->get('url_alias_id') . "'");
		return $result;
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
	function get_page(){
		if (!$this->session->get('url_alias.search')) {
			$sql = "select url_alias_id, query, alias from url_alias";
		} else {
			$sql = "select url_alias_id, query, alias from url_alias where query like '?' or alias like '?'";
		}
		$sort = array(
			'query',
			'alias'
		);
		if (in_array($this->session->get('url_alias.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('url_alias.sort') . " " . (($this->session->get('url_alias.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by query asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('url_alias.search') . '%','%' . $this->session->get('url_alias.search') . '%'), $this->session->get('url_alias.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_text_results(){ 
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
}
?>