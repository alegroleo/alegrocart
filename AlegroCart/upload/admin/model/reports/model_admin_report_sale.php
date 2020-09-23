<?php //AdminModelReportSale AlegroCart
class Model_Admin_Report_Sale extends Model {

	public function __construct(&$locator) {
		$this->config		=& $locator->get('config');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->session		=& $locator->get('session');
	}

	public function sql_parse_date($from, $to){
		$result = $this->database->parse(" where date_added between '?' and '?'", $from, $to);
		return $result;
	}

	public function sql_parse_start_date($date_from){
		$result = $this->database->parse(" where date_added between '?' and now()", date('Y-m-d', strtotime(implode('-', $date_from))));
		return $result;
	}

	public function get_order_status(){
		$results = $this->database->cache('order_status-' . (int)$this->language->getId(), "select order_status_id, name from order_status where language_id = '" . (int)$this->language->getId() . "' order by name");
		return $results;
	}

	public function get_page($sql){
		$results = $this->database->getRows($this->database->splitQuery($sql, $this->session->get('report_sale.page'), $this->config->get('config_max_rows')));
		return $results;
	}

	public function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}

	public function get_pagination(){
		$page_data = array();
		for ($i = 1; $i <= $this->get_pages(); $i++) {
			$page_data[] = array(
				'text'  => $this->language->get('text_pages', $i, $this->get_pages()),
				'value' => $i
			);
		}
		return $page_data;
	}

	private function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}

}
?>
