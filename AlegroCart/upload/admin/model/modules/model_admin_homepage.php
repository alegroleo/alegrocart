<?php //AdminModelHomepage AlegroCart
class Model_Admin_Homepage extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function delete_status(){
		$results = $this->database->getRows("select home_id, status from home_page");
		if($results){
			foreach($results as $result){
				$this->database->query("update `home_page` set status='0' where home_id='" . $result['home_id'] . "'");
			}
		}
	}
	function insert_status(){
		$sql = "insert into home_page set name = '?', status = '?'";
		$this->database->query($this->database->parse($sql, $this->request->get('name','post'), $this->request->gethtml('status','post')));
	}
	function update_status(){
		$sql = "update home_page set name = '?', status = '?' where home_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->get('name','post'), $this->request->gethtml('status','post'),(int)$this->request->gethtml('home_id')));
	}
	function delete_homepage($home_id){
		$this->database->query("delete from home_page where home_id='" . (int)$home_id. "'");
	}
	function delete_description($home_id){
		$this->database->query("delete from home_description where home_id ='". (int)$home_id . "'");
	}
	function insert_description(){
		$insert_id = $this->database->getLastId();
		$this->write_description($insert_id);
	}
	function update_description(){
		$insert_id = (int)$this->request->gethtml('home_id');
		$this->write_description($insert_id);
	}
	function write_description($insert_id){
		$title = $this->request->get('title', 'post');
		$description = $this->request->get('description', 'post');
		$welcome = $this->request->get('welcome', 'post');
		$meta_title = $this->request->get('meta_title', 'post');
		$meta_description = $this->request->get('meta_description', 'post');
		$meta_keywords = $this->request->get('meta_keywords', 'post');
		$flash = $this->request->get('flash', 'post');
		$flash_width = $this->request->gethtml('flash_width', 'post');
		$flash_height = $this->request->gethtml('flash_height','post');
		$flash_loop = $this->request->gethtml('flash_loop','post');
		$image_id = $this->request->gethtml('image_id', 'post');
		$run_times = $this->request->gethtml('run_times', 'post');
		$no_image_id = $this->request->gethtml('no_image_id', 'post');

		foreach($this->request->get('title', 'post', array()) as $key => $value){
			$sql = "insert into home_description set home_id = '?', language_id = '?', title = '?', description = '?', welcome = '?', meta_title ='?', meta_description = '?', 	meta_keywords = '?', flash = '?', flash_width = '?', flash_height = '?', flash_loop = '?', image_id = '?', run_times = '?'";
			$this->database->query($this->database->parse($sql, $insert_id, $key, @htmlspecialchars($title[$key]), $description[$key], $welcome[$key], strip_tags($meta_title[$key]), strip_tags($meta_description[$key]), strip_tags($meta_keywords[$key]), $flash[$key], $flash_width[$key], $flash_height[$key], $flash_loop[$key], $image_id[$key] != $no_image_id ? $image_id[$key]: '0', $run_times[$key]));
		}
	}
	function get_descriptions($home_id, $language_id){
		$result = $this->database->getRow("select title, description, welcome, meta_title, meta_description, meta_keywords, flash, flash_width, flash_height, flash_loop, image_id, run_times from home_description where home_id = '" . (int)$home_id . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function getRow_homepage_info($home_id){ 
		$result = $this->database->getRow("select distinct * from home_page where home_id = '" . (int)$home_id . "'");
		return $result;
	}
	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' order by id.title");
		return $results;
	}
	function get_no_image(){
		$result = $this->database->getRow("select id.image_id, i.filename from image_description id inner join image i on (i.image_id=id.image_id) where id.language_id = '1' and id.title = 'no image'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function get_page(){
		if (!$this->session->get('homepage.search')){
			$sql = "select h.home_id, h.name, h.status, hd.title, i.filename from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on (hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select h.home_id, h.name, h.status, hd.title, i.filename from home_page h left join home_description hd on(h.home_id = hd.home_id) left join image i on (hd.image_id = i.image_id) where hd.language_id = '" . (int)$this->language->getId() . "' and h.name like '?'";
		}
		$sort = array('h.name',	'h.status',	'hd.title',	'i.filename');
		if(in_array($this->session->get('homepage.sort'), $sort)){
			$sql .= " order by " . $this->session->get('homepage.sort'). " " . (($this->session->get('homepage.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by h.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('homepage.search') . '%'), $this->session->get('homepage.page'), $this->config->get('config_max_rows')));
		return $results;
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
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function change_homepage_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update home_page set status = '?' where home_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}
}
?>
