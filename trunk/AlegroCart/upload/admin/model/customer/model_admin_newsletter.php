<?php //AdminModelNewsletter AlegroCart
class Model_Admin_Newsletter extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function insert_newsletter(){
		$sql = "insert into newsletter set subject = '?', content = '?', date_added = now(),  date_sent = '2000-01-01 00:00:00'";
		$this->database->query($this->database->parse($sql, $this->request->get('subject', 'post'), $this->request->get('content',  'post')));
	}
	function update_send($newsletter_id){
		$this->database->query("update newsletter set date_sent = now() where newsletter_id = '" . (int)$newsletter_id . "'");
	}
	function get_customers(){
		$results = $this->database->getRows("select email from customer where newsletter = '1'");
		return $results;
	}
	function update_newsletter(){
		$sql = "update newsletter set subject = '?', content = '?' where newsletter_id = '?'";
		$this->database->query($this->database->parse($sql, $this->request->get('subject', 'post'), $this->request->get('content', 'post'), (int)$this->request->gethtml('newsletter_id')));
	}
	function delete_newsletter(){
		$this->database->query("delete from newsletter where newsletter_id = '" . (int)$this->request->gethtml('newsletter_id') . "'");
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function get_newsletter(){
		$result = $this->database->getRow("select distinct * from newsletter where newsletter_id = '" . (int)$this->request->gethtml('newsletter_id') . "'");
		return $result;
	}
	function get_page(){
		if (!$this->session->get('newsletter.search')) {
			$sql = "select newsletter_id, subject, date_added, date_sent from newsletter";
		} else {
			$sql = "select newsletter_id, subject, date_added, date_sent from newsletter where subject like '?'";
		}
		$sort = array('subject', 'date_added', 'date_sent');
		if (in_array($this->session->get('newsletter.sort'), $sort)) {
			$sql .= " order by " . $this->session->get('newsletter.sort') . " " . (($this->session->get('newsletter.order') == 'desc') ? 'desc' : 'asc');
		} else {
			$sql .= " order by date_added asc";
		}
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('newsletter.search') . '%'), $this->session->get('newsletter.page'), $this->config->get('config_max_rows')));
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