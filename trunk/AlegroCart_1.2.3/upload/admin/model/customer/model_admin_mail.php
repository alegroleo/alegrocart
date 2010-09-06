<?php //AdminModelMail AlegroCart
class Model_Admin_Mail extends Model {
	function __construct(&$locator) {	
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request 	=& $locator->get('request');
		$this->session 	=& $locator->get('session');
	}
	function get_email_newsletter(){
		$results = $this->database->getRows("select email from customer where newsletter = '1'");
		return $results;
	}
	function get_email_customers(){
		$results = $this->database->getRows("select email from customer");
		return $results;
	}
	function get_customer_email(){
		$result = $this->database->getRow("select email from customer where customer_id = '" . (int)$this->request->gethtml('to', 'post') . "'");
		return $result;
	}
	function get_customers(){
		$results = $this->database->getRows("select * from customer order by firstname, lastname, email");
		return $results;
	}
}
?>