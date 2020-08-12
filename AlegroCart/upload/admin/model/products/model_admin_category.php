<?php //AdminModelCategory AlegroCart
class Model_Admin_Category extends Model {

	function __construct(&$locator) {
		$this->config	=& $locator->get('config');
		$this->database	=& $locator->get('database');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session	=& $locator->get('session');
	}

	function insert_category(){
		$cat_path = explode('_', $this->request->gethtml('path'));
		$sql = "insert into category set image_id = '?', sort_order = '?', category_hide = '?', parent_id = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('image_id', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('category_hide', 'post'), end($cat_path)));
	}

	function get_description_post(){
		$this->description = $this->request->get('description', 'post');
		$this->meta_title = $this->request->get('meta_title', 'post');
		$this->meta_description = $this->request->get('meta_description', 'post');
		$this->meta_keywords = $this->request->get('meta_keywords', 'post');
	}

	function write_description($insert_id,$key,$name){
		$sql = "insert into category_description set category_id = '?', language_id = '?', name = '?', description = '?', meta_title = '?', meta_description = '?', meta_keywords = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $insert_id, $key, $name, $this->description[$key], strip_tags($this->meta_title[$key]), strip_tags($this->meta_description[$key]), strip_tags($this->meta_keywords[$key])));
	}

	function update_category(){
		$sql = "update category set image_id = '?', sort_order = '?', category_hide = '?', date_modified = now() where category_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('image_id', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('category_hide', 'post'), $this->request->gethtml('category_id')));
	}

	function delete_subcats($path){
		$sql = "delete category, category_description from category left join category_description on category.category_id = category_description.category_id where category.path like '?'";
		$this->database->query($this->database->parse($sql, $path . '\_%'));
	}

	function check_products(){
		if ($this->request->gethtml('path')) {
				$path = $this->request->gethtml('path') . '_' . $this->request->gethtml('category_id');
			} else {
				$path = $this->request->gethtml('category_id');
			}
		if($path){
			$subcats = $this->database->getRows("select category_id from category where category.path ='" . $path . "' or category.path like '" . $path . '\_'. "%'");
			if($subcats){
				$results = array();
				foreach($subcats as $subcat){
				$results[] = $this->database->getRows("select p2c.product_id, pd.name from product_to_category p2c left join product_description pd on p2c.product_id=pd.product_id where p2c.category_id = '" . $subcat['category_id'] . "' and pd.language_id = '" . (int)$this->language->getId() . "'");	
				}
				$result = call_user_func_array('array_merge', $results);
				$result = array_map("unserialize", array_unique(array_map("serialize", $result)));
				if($result) {return $result;}
			}
		}
	}

	function delete_category(){
		$this->database->query("delete from category where category_id = '" . (int)$this->request->gethtml('category_id') . "'");
	}

	function delete_description(){
		$this->database->query("delete from category_description where category_id = '" . (int)$this->request->gethtml('category_id') . "'");
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM category_log cl INNER JOIN user u ON (u.user_id = cl.trigger_modifier_id) WHERE category_id = '" . (int)$this->request->gethtml('category_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function get_modified_log($date_modified){
		$result = $this->database->getRow("SELECT id.title, cl.image_id, category_hide, sort_order, date_modified, CONCAT(firstname, ' ', lastname) AS modifier FROM category_log cl INNER JOIN user u ON (u.user_id = cl.trigger_modifier_id) INNER JOIN image_description id ON (id.image_id = cl.image_id) WHERE category_id = '" . (int)$this->request->gethtml('category_id') . "' AND id.language_id = '" . (int)$this->language->getId() . "' AND date_modified =  '" . $date_modified . "'");
		return $result;
	}

	function get_description_modified_log($language_id, $date_modified){
		$result = $this->database->getRow("SELECT name, description, meta_description, meta_keywords, meta_title, CONCAT(firstname, ' ', lastname) AS modifier FROM category_description_log cdl  INNER JOIN user u ON (u.user_id = cdl.trigger_modifier_id) WHERE category_id = '" . (int)$this->request->gethtml('category_id') . "' AND date_modified =  '" . $date_modified . "' AND language_id ='" . $language_id . "'");
		return $result;
	}

	function get_assigned_log($date_modified){
		$results = $this->database->getRows("SELECT p2cl.product_id, pd.name FROM product_to_category_log p2cl INNER JOIN product_description pd ON (p2cl.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "' AND p2cl.category_id = '" . (int)$this->request->gethtml('category_id') . "' AND p2cl.date_modified =  '" . $date_modified . "'");
		return $results;
	}

	function get_category(){
		$result = $this->database->getRow("select distinct * from category where category_id = '" . (int)$this->request->gethtml('category_id') . "'");
		return $result;
	}

	function get_category_description($language_id){
		$result = $this->database->getRow("select name, description, meta_title, meta_description, meta_keywords, date_modified from category_description where category_id = '" . (int)$this->request->gethtml('category_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}

	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on i.image_id = id.image_id where id.language_id = '" . (int)$this->language->getId() . "'order by id.title");
		return $results;
	}

	function get_path($cat_path){
		$result = $this->database->getRow("select path from category where category_id ='" . (int)$cat_path . "'");
		return $result;
	}

	function update_path($path, $insert_id){
		$this->database->query("update category set path = '" . $path . "' where category_id = '" . $insert_id . "'");
	}

	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}

	function check_children($path){
		$results =  $this->database->getRows("select category_id from category where parent_id = '" . $path . "'");
		return $results;
	}

	function check_parent_status($category_id){
		$parent_id = $this->database->getRow("select parent_id from category where category_id = '" . $category_id . "'");
		$result =  $this->database->getRow("select category_hide from category where category_id = '" . $parent_id['parent_id'] . "'");
		return $result['category_hide'];
	}

	function get_page(){
		if ((!$this->session->has('category.search')) || ($this->request->gethtml('path'))) {
			$cat_path = explode('_', $this->request->gethtml('path'));
			$sql = "select c.category_id, cd.name, i.filename, c.category_hide, c.sort_order from category c left join category_description cd on (c.category_id = cd.category_id) left join image i on (c.image_id = i.image_id) where c.parent_id = '" . (int)end($cat_path) . "' and language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select c.category_id, cd.name, i.filename, c.category_hide, c.sort_order from category c left join category_description cd on (c.category_id = cd.category_id) left join image i on (c.image_id = i.image_id) where language_id = '" . (int)$this->language->getId() . "' and cd.name like '?'";
		}
		$sort = array('cd.name', 'c.category_hide', 'c.sort_order', 'i.filename');
		if (in_array($this->session->get('category.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('category.sort') . " " . (($this->session->get('category.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by cd.name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('category.search') . '%'), ($this->request->has('path') ? $this->session->get('category.' . $this->request->gethtml('path') . '.page') : $this->session->get('category.page')), $this->config->get('config_max_rows')));
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

	function get_category_name($category_id,$language_id){
		$result = $this->database->getRow("select name from category_description where category_id = '" . (int)$category_id . "' and language_id = '" . $language_id . "'");
		return $result;
	}

	function delete_SEO($query_path){
		$this->database->query("delete from url_alias where query = '".$query_path."'");
	}

	function get_products(){
		$results = $this->database->getRows("select p.product_id, pd.name, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$this->language->getId() . "' and p.status = 1 order by pd.name asc");
		return $results;
	}

	function get_categoryToProduct($product_id){
		$result = $this->database->getRow("select * from product_to_category where category_id = '" . (int)$this->request->gethtml('category_id') . "' and product_id = '" . (int)$product_id . "'");
		return $result;
	}

	function write_product($product_id, $insert_id){
		$this->database->query("insert into product_to_category set category_id = '" . (int)$insert_id . "', product_id = '" . (int)$product_id . "'");
	}

	function delete_categoryToProduct(){
		$this->database->query("delete from product_to_category where category_id = '" . (int)$this->request->gethtml('category_id') . "'");
	}

	function update_product($product_id){
		$this->database->query("insert into product_to_category set category_id = '" . (int)$this->request->gethtml('category_id') . "' , product_id = '" . (int)$product_id . "'");
	}

	function change_category_visibility($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update category set category_hide = '?' where path = '?' or path like '?' or path like '?' or path like '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id, (int)$status_id. '\_%', '%\_'.(int)$status_id, '%\_' . (int)$status_id . '\_%'));
	}
}
?>
