<?php //AdminModelImageDisplay AlegroCart
class Model_Admin_Image_Display extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_image_display(){
		$sql = "insert into image_display set name = '?', status = '?', sort_order = '?', location_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->get('name','post'), (int)$this->request->gethtml('status','post'), (int)$this->request->gethtml('sort_order','post'), $this->request->gethtml('location_id', 'post')));
	}
	function update_image_display(){
		$sql = "update image_display set name = '?', status = '?',  sort_order = '?', location_id = '?' where image_display_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->get('name','post'), $this->request->gethtml('status','post'), (int)$this->request->gethtml('sort_order','post'), $this->request->gethtml('location_id', 'post'), (int)$this->request->gethtml('image_display_id')));
	}
	function delete_image_display($image_display_id){
		$this->database->query("delete from image_display where image_display_id = '" . (int)$image_display_id. "'");
	
	}
	function delete_description($image_display_id){
		$this->database->query("delete from image_display_description where image_display_id ='". (int)$image_display_id . "'");
	}
	function insert_description(){
		$insert_id = $this->database->getLastId();
		$this->write_description($insert_id);
	}
	function update_description(){
		$insert_id = (int)$this->request->gethtml('image_display_id');
		$this->write_description($insert_id);
	}
	function write_description($insert_id){
		$flash = $this->request->get('flash', 'post');
		$flash_width = $this->request->gethtml('flash_width', 'post');
		$flash_height = $this->request->gethtml('flash_height','post');
		$flash_loop = $this->request->gethtml('flash_loop','post');
		$image_id = $this->request->gethtml('image_id', 'post');
		$image_width = $this->request->gethtml('image_width', 'post');
		$image_height = $this->request->gethtml('image_height','post');
		$no_image_id = $this->request->gethtml('no_image_id', 'post');
		foreach($this->request->get('flash', 'post', array()) as $key => $value){
			$sql = "insert into image_display_description set image_display_id = '?', language_id = '?', flash = '?', flash_width = '?', flash_height = '?', flash_loop = '?', image_id = '?', image_width = '?', image_height = '?'";
			$this->database->query($this->database->parse($sql, $insert_id, $key, $flash[$key], $flash_width[$key], $flash_height[$key], $flash_loop[$key], $image_id[$key] != $no_image_id ? $image_id[$key]: '0', $image_width[$key], $image_height[$key]));
		}
	}
	function getRow_image_display_info($image_display_id){
		$result = $this->database->getRow("select distinct * from image_display where image_display_id = '" . (int)$image_display_id . "'");
		return $result;
	}
	function get_descriptions($image_display_id, $language_id){
		$result = $this->database->getRow("select flash, flash_width, flash_height, flash_loop, image_id, image_width, image_height from image_display_description where image_display_id = '" . (int)$image_display_id . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('imagedisplay.search')){
			$sql = "select id.image_display_id, id.name, id.status, id.location_id, id.sort_order, tpl.location from image_display id left join tpl_location tpl on (id.location_id = tpl.location_id)";
		} else {
			$sql = "select id.image_display_id, id.name, id.status, id.location_id, id.sort_order, tpl.location from image_display id left join tpl_location tpl on (id.location_id = tpl.location_id) where id.name like '?'";
		}
		$sort = array('id.name', 'id.location_id', 'id.sort_order', 'id.status');
		if (in_array($this->session->get('imagedisplay.sort'), $sort)){
			$sql .= " order by " . $this->session->get('imagedisplay.sort') . " " . (($this->session->get('imagedisplay.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by id.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql,  '%' . $this->session->get('imagedisplay.search') . '%'),  $this->session->get('imagedisplay.page'), $this->config->get('config_max_rows')));
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
	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' order by id.title");
		return $results;
	}
	function get_no_image(){
		$result = $this->database->getRow("select id.image_id, i.filename from image_description id inner join image i on (i.image_id=id.image_id) where id.language_id = '1' and id.title = 'no image'");
		return $result;
	}
	function get_locations(){
		$results = $this->database->getRows("select location_id, location from tpl_location");
		return $results;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function change_imagedisplay_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update image_display set status = '?' where image_display_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}
}	
?>
