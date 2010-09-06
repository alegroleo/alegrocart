<?php //AdminModelDownload AlegroCart
class Model_Admin_Download extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
		$this->upload   	=& $locator->get('upload');
	}
	function insert_download(){
		$sql = "insert into download set filename = '?', mask = '?', remaining = '?', date_added = now()";
      	$this->database->query($this->database->parse($sql, $this->upload->getName('download'), $this->request->gethtml('mask', 'post') ? $this->request->gethtml('mask', 'post') : $this->upload->getName('download'), $this->request->gethtml('remaining', 'post')));
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function insert_description($insert_id, $key, $value){
		$this->database->query("insert into download_description set download_id = '" . $insert_id . "', language_id = '" . $key . "', name = '" . $value . "'");
	}
	function update_download(){
		$sql = "update download set mask = '?', remaining = '?' where download_id = '?'";
        		$this->database->query($this->database->parse($sql, $this->request->gethtml('mask', 'post'), $this->request->gethtml('remaining', 'post'), $this->request->gethtml('download_id')));
		if ($this->upload->has('download')) {
			$this->database->query("update download set filename = '" . $this->upload->getName('download') . "'");
		}
	}
	function delete_description(){
		$this->database->query("delete from download_description where download_id = '" . (int)$this->request->gethtml('download_id') . "'");
	}
	function delete_download(){
		$this->database->query("delete from download where download_id = '" . (int)$this->request->gethtml('download_id') . "'");
	}
	function check_products(){
		$results = $this->database->getRow("select count(*) as total from product_to_download where download_id = '" . (int)$this->request->gethtml('download_id') . "'");
		return $results;
	}
	function get_page(){
		if (!$this->session->get('download.search')) {
      		$sql = "select d.download_id, dd.name, d.filename, d.mask, d.remaining from download d left join download_description dd on d.download_id = dd.download_id where dd.language_id = '" . (int)$this->language->getId() . "'";
    	} else {
      		$sql = "select d.download_id, dd.name, d.filename, d.mask, d.remaining from download d left join download_description dd on d.download_id = dd.download_id where dd.language_id = '" . (int)$this->language->getId() . "' and dd.name like '?'";
    	}
		$sort = array('dd.name', 'd.filename', 'd.mask', 'd.max_days', 'd.remaining');
    	if (in_array($this->session->get('download.sort'), $sort)) {
      		$sql .= " order by " . $this->session->get('download.sort') . " " . (($this->session->get('download.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by dd.name asc";
    	}
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('download.search') . '%'), $this->session->get('download.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function getRow_download_info(){
		$result = $this->database->getRow("select distinct * from download where download_id = '" . (int)$this->request->gethtml('download_id') . "'");
		return $result;
	}
	function get_descriptions($download_id, $language_id){
		$result = $this->database->getRow("select name from download_description where download_id = '" . (int)$download_id . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
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