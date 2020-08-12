<?php //AdminModelLanguage AlegroCart
class Model_Admin_Language extends Model {

	var $last_inserted = NULL;

	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}

	function insert_language(){
		$sql = "insert into language set name = '?', language_status = '?', code = '?', directory = '?', filename= '?', image = '?', sort_order = '?', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('language_status', 'post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('directory', 'post'), $this->request->gethtml('filename', 'post'), $this->request->gethtml('image', 'post'), $this->request->gethtml('sort_order', 'post')));

		$this->last_inserted = $this->database->getLastId();
	}

	function update_language(){
		$sql = "update language set name = '?', language_status = '?', code = '?', directory = '?', filename= '?', image = '?', sort_order = '?' where language_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('name', 'post'), $this->request->gethtml('language_status', 'post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('directory', 'post'), $this->request->gethtml('filename', 'post'), $this->request->gethtml('image', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('language_id')));
	}

	function delete_language(){
		$this->database->query("delete from language where language_id = '" . (int)$this->request->gethtml('language_id') . "'");
	}

	function get_language(){
		$result = $this->database->getRow("select distinct * from language where language_id = '" . (int)$this->request->gethtml('language_id') . "'");
		return $result;
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM language_log ll INNER JOIN user u ON (u.user_id = ll.trigger_modifier_id) WHERE language_id = '" . (int)$this->request->gethtml('language_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function get_modified_log($date_modified){
		$result = $this->database->getRow("SELECT ll.*, CONCAT(firstname, ' ', lastname) AS modifier FROM language_log ll INNER JOIN user u ON (u.user_id = ll.trigger_modifier_id) WHERE language_id = '" . (int)$this->request->gethtml('language_id') . "' AND date_modified =  '" . $date_modified . "'");
		return $result;
	}

	function get_catalog_language(){
		$result = $this->database->getRow("select value from setting where `key` = 'config_language' and `type`='catalog'");
		return $result['value'];
	}

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}

	function check_language_code(){
		$result = $this->database->getRow("select distinct code from language where language_id = '" . (int)$this->request->gethtml('language_id') . "'");
		return $result;
	}

	function get_page(){
		if (!$this->session->get('language.search')) {
			$sql = "select language_id, language_status, name, code, sort_order from language";
		} else {
			$sql = "select language_id, language_status, name, code, sort_order from language where name like '?'";
		}
		$sort = array('language_status', 'name', 'code', 'sort_order');
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

	function duplicate_category_description() {
		$results = $this->database->getRows("select * from category_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into category_description set category_id = '?', language_id = '?', name = '?', description= '?', meta_keywords= '?', meta_description= '?', meta_title= '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['category_id'], $this->last_inserted, $result['name'], $result['description'], $result['meta_keywords'], $result['meta_description'], $result['meta_title']));
			      }
			} 
	}

	function duplicate_coupon_description() {
		$results = $this->database->getRows("select * from coupon_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into coupon_description set coupon_id = '?', language_id = '?', name = '?', description= '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['coupon_id'], $this->last_inserted, $result['name'], $result['description']));
			      }
			} 
	}

	function duplicate_dimension() {
		$results = $this->database->getRows("select * from dimension where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into dimension set dimension_id = '?', unit = '?', type_id = '?', language_id= '?', title = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['dimension_id'], $result['unit'], $result['type_id'], $this->last_inserted, $result['title']));
			      }
			} 
	}

	function duplicate_download_description() {
		$results = $this->database->getRows("select * from download_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into download_description set download_id = '?', language_id = '?', name = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['download_id'], $this->last_inserted, $result['name']));
			      }
			} 
	}

	function duplicate_extension_description() {
		$results = $this->database->getRows("select * from extension_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into extension_description set extension_id = '?', language_id = '?', name = '?', description= '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['extension_id'], $this->last_inserted, $result['name'], $result['description']));
			      }
			} 
	}

	function duplicate_home_description() {
		$results = $this->database->getRows("select * from home_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into home_description set home_id = '?', language_id = '?', title = '?', description= '?', welcome = '?', meta_title= '?', meta_description= '?', meta_keywords= '?', flash = '?', flash_height = '?', flash_loop = '?', flash_width = '?', image_id = '?', run_times = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['home_id'], $this->last_inserted, $result['title'], $result['description'], $result['welcome'], $result['meta_title'], $result['meta_description'], $result['meta_keywords'], $result['flash'], $result['flash_height'], $result['flash_loop'], $result['flash_width'], $result['image_id'], $result['run_times']));
			      }
			} 
	}

	function duplicate_home_slides() {
		$results = $this->database->getRows("select * from home_slides where language_id = '1'");
			if ($results) {
				foreach ($results as $result) {
					$sql= "insert into home_slides set home_slide_id = '?', home_id = '?', language_id = '?', image_id= '?', sort_order = '?', date_added = now()";
					$this->database->query($this->database->parse($sql, $result['home_slide_id'], $result['home_id'], $this->last_inserted, $result['image_id'], $result['sort_order']));
				}
			} 
	}

	function duplicate_image_description() {
		$results = $this->database->getRows("select * from image_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into image_description set image_id = '?', language_id = '?', title = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['image_id'], $this->last_inserted, $result['title']));
			      }
			} 
	}

	function duplicate_image_display_description() {
		$results = $this->database->getRows("select * from image_display_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into image_display_description set image_display_id = '?', language_id = '?', flash = '?', flash_width = '?', flash_height = '?', flash_loop = '?', image_id = '?', image_width = '?', image_height = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['image_display_id'], $this->last_inserted, $result['flash'], $result['flash_width'], $result['flash_height'], $result['flash_loop'], $result['image_id'], $result['image_width'], $result['image_height']));
			      }
			} 
	}

	function duplicate_image_display_slides() {
		$results = $this->database->getRows("select * from image_display_slides where language_id = '1'");
			if ($results) {
				foreach ($results as $result) {
					$sql= "insert into image_display_slides set image_display_slide_id = '?', image_display_id = '?', language_id = '?', image_id = '?', sort_order = '?', date_added = now()";
					$this->database->query($this->database->parse($sql, $result['image_display_slide_id'], $result['image_display_id'], $this->last_inserted, $result['image_id'], $result['sort_order']));
				}
			} 
	}

	function duplicate_information_description() {
		$results = $this->database->getRows("select * from information_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into information_description set information_id = '?', language_id = '?', title = '?', description = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['information_id'], $this->last_inserted, $result['title'], $result['description']));
			      }
			} 
	}

	function duplicate_option() {
		$results = $this->database->getRows("select * from `option` where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into `option` set option_id = '?', language_id = '?', name = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['option_id'], $this->last_inserted, $result['name']));
			      }
			} 
	}

	function duplicate_option_value() {
		$results = $this->database->getRows("select * from option_value where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into option_value set option_value_id = '?', language_id = '?', option_id = '?', name= '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['option_value_id'], $this->last_inserted, $result['option_id'], $result['name']));
			      }
			} 
	}

	function duplicate_order_status() {
		$results = $this->database->getRows("select * from order_status where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into order_status set order_status_id = '?', language_id = '?', name = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['order_status_id'], $this->last_inserted, $result['name']));
			      }
			} 
	}

	function duplicate_product_description() {
		$results = $this->database->getRows("select * from product_description where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into product_description set product_id = '?', language_id = '?', name = '?', description= '?', technical = '?', technical_name= '?', model= '?', model_number = '?', meta_keywords = '?', meta_description = '?', meta_title = '?', alt_description = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['product_id'], $this->last_inserted, $result['name'], $result['description'], $result['technical'], $result['technical_name'], $result['model'], $result['model_number'], $result['meta_keywords'], $result['meta_description'], $result['meta_title'], $result['alt_description']));
			      }
			} 
	}

	function change_language_status($status, $status_id){
		$new_status = $status ? 0 : 1;
		$sql = "update language set language_status = '?' where language_id = '?'";
		$this->database->query($this->database->parse($sql, (int)$new_status, (int)$status_id));
	}

	function duplicate_weight_class() {
		$results = $this->database->getRows("select * from weight_class where language_id = '1'");
			if ($results) {
			      foreach ($results as $result) {
			      $sql= "insert into weight_class set weight_class_id = '?', unit = '?', language_id= '?', title = '?', date_added = now()";
			      $this->database->query($this->database->parse($sql, $result['weight_class_id'], $result['unit'], $this->last_inserted, $result['title']));
			      }
			} 
	}

	function duplicate_maintenance_description() {
		$results = $this->database->getRows("select * from maintenance_description where language_id = '1'");
			if ($results) {
				foreach ($results as $result) {
					$sql= "insert into maintenance_description set maintenance_id = '?', language_id = '?', header = '?', description = '?', date_added = now()";
					$this->database->query($this->database->parse($sql, $result['maintenance_id'], $this->last_inserted, $result['header'], $result['description']));
				}
			} 
	}
}
?>
