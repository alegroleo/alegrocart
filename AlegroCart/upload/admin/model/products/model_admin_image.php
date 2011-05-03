<?php //AdminModelImage AlegroCart
class Model_Admin_Image extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function check_image($filename){
		$result = $this->database->getRow("select * from image where filename = '".$filename."'");
		return $result;
	}
	function get_image(){
		$result = $this->database->getRows("select * from image where image_id = '" . (int)$this->request->gethtml('image_id') . "'");
		return $result;
	}
	function insert_image($filename){
		$this->database->query("insert into image set filename = '" . $filename . "', date_added = now()");
	}
	function update_image($image){
		$this->database->query("update image set filename = '" . $image . "' where image_id = '" . $this->request->gethtml('image_id') . "'");
	}
	function delete_description(){
		$this->database->query("delete from image_description where image_id = '" . (int)$this->request->gethtml('image_id') . "'");
	}
	function delete_image(){
		$this->database->query("delete from image where image_id = '" . (int)$this->request->gethtml('image_id') . "'");
	}
	function check_filename($filename){
		$results = $this->database->getRows("select filename from image where filename = '" . $filename . "'");
		return $results;
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function insert_description($insert_id, $language_id, $title){
		$this->database->query("insert into image_description set image_id = '" . $insert_id . "', language_id = '" . $language_id . "', title = '" . $title . "'");
	}
	function get_page(){
    	if (!$this->session->get('image.search')) {
      		$sql = "select i.image_id, id.title, i.filename, i.date_added from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "'";
    	} else {
			$sql = "select i.image_id, id.title, i.filename, i.date_added from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' and id.title like '?'";
    	}
		$sort = array('id.title', 'i.filename',	'i.date_added');
		if (in_array($this->session->get('image.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('image.sort') . " " . (($this->session->get('image.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by id.title asc";
		}
    	$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('image.search') . '%'), $this->session->get('image.page'), $this->config->get('config_max_rows')));
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
	function get_description($language_id){
		$result = $this->database->getRow("select * from image_description where image_id = '" . (int)$this->request->gethtml('image_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function check_product(){
		$result = $this->database->getRow("select count(*) as total from product where image_id = '" . (int)$this->request->gethtml('image_id') . "'");
		return $result;
	}
	function check_category(){
		$result = $this->database->getRow("select count(*) as total from category where image_id = '" . (int)$this->request->gethtml('image_id') . "'");
		return $result;
	}
	function check_manufacturer(){
		$result = $this->database->getRow("select count(*) as total from manufacturer where image_id = '" . (int)$this->request->gethtml('image_id') . "'");
		return $result;
	}
	function get_image_data(){
		$result = $this->database->getRow("select * from image i left join image_description id on (i.image_id = id.image_id) where i.image_id = '" . (int)$this->request->gethtml('image_id') . "' and id.language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
}
?>