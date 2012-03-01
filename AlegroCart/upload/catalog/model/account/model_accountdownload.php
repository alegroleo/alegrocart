<?php //ModelAccountDownload AlegroCart
class Model_AccountDownload extends Model{
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_downloads($customer_id){
		$results = $this->database->getRows($this->database->splitQuery("select o.order_id, o.date_added, o.total ,od.order_download_id, od.name, od.filename, od.remaining from order_download od left join `order` o on od.order_id = o.order_id where o.customer_id = '" . (int)$customer_id . "' and (o.order_status_id = '" . (int)$this->config->get('config_download_status') . "' or o.total = '0')" , $this->session->get('account_download.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_download($customer_id,$download_id){
		$result = $this->database->getRow("select * from `order` o left join order_download od on o.order_id = od.order_id where o.customer_id = '" . (int)$customer_id . "' and (o.order_status_id = '" . (int)$this->config->get('config_download_status') . "' and od.order_download_id = '" . (int)$download_id . "' or o.total = '0') and od.remaining > 0");
		return $result;
	}
	function update_download($download_id){
		$this->database->query("update order_download set remaining = (remaining - 1) where order_download_id = '" . (int)$download_id . "'");
	}
	function get_pagination(){
		$page_data = array();
		for ($i = 1; $i <= $this->database->getPages(); $i++) {
			$query=array('path' => $this->request->gethtml('path'));
			if ($i >1) $query['page'] = $i;
        	$page_data[] = array(
          		'text'  => $this->language->get('text_pages', $i, $this->database->getPages()),
				'href'	=> $this->url->href('category', FALSE, $query),
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
}
?>