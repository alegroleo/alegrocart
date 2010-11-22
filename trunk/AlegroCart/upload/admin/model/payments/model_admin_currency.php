<?php //AdminModelCurrency AlegroCart
class Model_Admin_Currency extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function insert_currency(){
		$sql = "insert into currency set title = '?', code = '?', status = '?', lock_rate = '?', symbol_left = '?', symbol_right = '?', decimal_place = '?', value = '?', date_modified = now()";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('title', 'post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('status', 'post'), $this->request->gethtml('lock_rate', 'post'), $this->request->gethtml('symbol_left', 'post'), $this->request->gethtml('symbol_right', 'post'), $this->request->gethtml('decimal_place', 'post'), number_format($this->request->gethtml('value', 'post'), 8)));
	}
	function update_currency(){
		$sql = "update currency set title = '?', code = '?', status = '?', lock_rate = '?', symbol_left = '?', symbol_right = '?', decimal_place = '?', value = '?', date_modified = now() where currency_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->gethtml('title', 'post'), $this->request->gethtml('code', 'post'), $this->request->gethtml('status', 'post'), $this->request->gethtml('lock_rate', 'post'), $this->request->gethtml('symbol_left', 'post'), $this->request->gethtml('symbol_right', 'post'), $this->request->gethtml('decimal_place', 'post'), number_format($this->request->gethtml('value', 'post'), 8), $this->request->gethtml('currency_id')));
	}
	function delete_currency(){
		$this->database->query("delete from currency where currency_id = '" . (int)$this->request->gethtml('currency_id') . "'");
	}
	function check_status(){
		$result = count($this->database->getRows("select status from currency where status = '1'"));
		return $result > 1 ? TRUE : FALSE;
	}
	function set_status($status){
		$this->database->query("update currency set status = '" . $status . "' where code != '" . $this->config->get('config_currency') . "'");
	}
	function get_codes(){
		$results = $this->database->getRows("select code, status, lock_rate from currency where code != '" . $this->config->get('config_currency')  . "'");
		return $results;
	}
	function update_rates($rate, $code){
		$this->database->query("update `currency` set value ='" . $rate . "', date_modified = now() where code = '" . $code . "'"); 
	}
	function get_currency(){
		$result = $this->database->getRow("select distinct * from currency where currency_id = '" . (int)$this->request->gethtml('currency_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('currency.search')) {
			$sql = "select currency_id, title, code, status, lock_rate, value, date_modified from currency";
		} else {
			$sql = "select currency_id, title, code, status, lock_rate, value, date_modified from currency where title like '?'";
		}
		$sort = array('title', 'code', 'value',	'status', 'date_modified');
		if (in_array($this->session->get('currency.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('currency.sort') . " " . (($this->session->get('currency.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by status desc, title asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('currency.search') . '%'), $this->session->get('currency.page'), $this->config->get('config_max_rows')));
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
	function check_default(){
		$result = $this->database->getRow("select * from currency where currency_id = '" . (int)$this->request->gethtml('currency_id') . "'");
		return $result;
	}
}
?>