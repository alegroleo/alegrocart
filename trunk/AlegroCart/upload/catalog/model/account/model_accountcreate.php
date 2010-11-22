<?php //ModelAccountCreate AlegroCart
class Model_AccountCreate extends Model{
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->config   =& $locator->get('config');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->url      =& $locator->get('url');
	}
	function get_information(){
		$result = $this->database->getRow("select * from information i left join information_description id on (i.information_id = id.information_id) where i.information_id = '" . (int)$this->config->get('config_account_id') . "'");
		return $result;
	}
	function set_default_address($customer_id){
		$this->database->query("update customer set address_id = '" . (int)$this->database->getLastId() . "' where customer_id = '" . (int)$customer_id . "'");
	}
	function insert_customer(){
		$sql = "insert into customer set firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', password = '?', newsletter = '?', status = '1', date_added = now()";
		$this->database->query($this->database->parse($sql, $this->request->sanitize('firstname', 'post'), $this->request->sanitize('lastname', 'post'), $this->request->sanitize('email', 'post'), $this->request->sanitize('telephone', 'post'), $this->request->sanitize('fax', 'post'), md5($this->request->sanitize('password', 'post')), $this->request->gethtml('newsletter', 'post')));	
	}
	function update_customer($customer_id){
		$sql = "update customer set firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', status = '1' where customer_id = '?'";
			$this->database->query($this->database->parse($sql, $this->request->sanitize('firstname', 'post'), $this->request->sanitize('lastname', 'post'), $this->request->sanitize('email', 'post'), $this->request->sanitize('telephone', 'post'), $this->request->sanitize('fax', 'post'), $customer_id));
	}
	function get_customer($customer_id){
		$result = $this->database->getRow("select * from customer where customer_id = '" . (int)$customer_id . "'");
		return $result;
	}
	function check_email($customer_id){
		return $this->database->getRow($this->database->parse("select * from customer where email = '?' and customer_id != '?'", $this->request->gethtml('email', 'post'), $customer_id))? TRUE : FALSE;
	
	}
	function check_customer($email){
		return $this->database->getRow("select * from customer where email = '" . $email . "'") ? TRUE : FALSE;
	}
	function update_password($customer_id){ 
		$sql = "update customer set password = '?' where customer_id = '?'";
      	$this->database->query($this->database->parse($sql, md5($this->request->sanitize('password', 'post')), $customer_id));
	}
	function reset_password($password){
		$this->database->query($this->database->parse("update customer set password = '?' where email = '?'", md5($password), $this->request->sanitize('email', 'post')));
	}
	function update_newsletter($customer_id){
		$this->database->query("update customer set newsletter = '" . (int)$this->request->gethtml('newsletter', 'post') . "' where customer_id = '" . (int)$customer_id . "'");
	}
}
?>