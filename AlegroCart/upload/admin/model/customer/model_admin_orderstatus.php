<?php //AdminModelOrderStatus AlegroCart
class Model_Admin_OrderStatus extends Model {

	function __construct(&$locator) {
		$this->config	=& $locator->get('config');
		$this->database	=& $locator->get('database');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session	=& $locator->get('session');
	}

	function insert_status(){
		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
			$sql = "insert into order_status set order_status_id = '?', language_id = '?', name = '?', date_added = now()";
			$this->database->query($this->database->parse($sql, @$insert_id, $key, $value['name']));
			$insert_id = $this->database->getLastId();
		}
	}

	function update_status(){
		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
			$sql = "insert into order_status set order_status_id = '?', language_id = '?', name = '?', date_added = now()";
			$this->database->query($this->database->parse($sql, (int)$this->request->gethtml('order_status_id'), $key, $value['name']));
		} 
	}

	function delete_status(){
		$this->database->query("delete from order_status where order_status_id = '" . (int)$this->request->gethtml('order_status_id') . "'");
	}

	function get_description($language_id){
		$result = $this->database->getRow("select name, date_modified from order_status where order_status_id = '" . (int)$this->request->gethtml('order_status_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}

	function get_modified_log($language_id, $date_modified){
		$result = $this->database->getRow("SELECT name, date_modified, CONCAT(firstname, ' ', lastname) AS modifier FROM order_status_log osl INNER JOIN user u ON (u.user_id = osl.trigger_modifier_id) WHERE order_status_id = '" . (int)$this->request->gethtml('order_status_id') . "' AND date_modified =  '" . $date_modified . "' AND language_id ='" . $language_id . "'");
		return $result;
	}

	function get_deleted_log(){
		$result = $this->database->getRow("SELECT CONCAT(firstname, ' ', lastname) AS modifier FROM order_status_log osl INNER JOIN user u ON (u.user_id = osl.trigger_modifier_id) WHERE order_status_id = '" . (int)$this->request->gethtml('order_status_id') . "' AND trigger_action = 'DELETE'");
		return $result;
	}

	function check_orders(){
		$results = $this->database->getRow("select count(*) as total from order_history where order_status_id = '" . (int)$this->request->gethtml('order_status_id') . "'");
		return $results;
	}

	function get_page(){
		if (!$this->session->get('order_status.search')) {
			$sql = "select order_status_id, name from order_status where language_id = '" . (int)$this->language->getId() . "'";
		} else {
			$sql = "select order_status_id, name from order_status where language_id = '" . (int)$this->language->getId() . "' and name like '?'";
		}
		if (in_array($this->session->get('order_status.sort'), array('name'))) {
			$sql .= " order by " . $this->session->get('order_status.sort') . " " . (($this->session->get('order_status.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by name asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('order_status.search') . '%'), $this->session->get('order_status.page'), $this->config->get('config_max_rows')));
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

	function get_orderstatusToOrders(){
		$result = $this->database->getRows("select order_id, invoice_number from `order` where order_status_id = '" . (int)$this->request->gethtml('order_status_id') . "'");
		return $result;
	}

	function get_last_id(){
		$result = $this->database->getLastId();
		return $result;
	}
}
?>
