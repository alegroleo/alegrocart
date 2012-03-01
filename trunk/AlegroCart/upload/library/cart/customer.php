<?php //Library Customer
class Customer {
	var $data = array();

  	function __construct(&$locator) {
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		  		
		if ($this->session->has('customer_id')) { 
			$this->data = $this->database->getRow("select * from customer where customer_id = '" . (int)$this->session->get('customer_id') . "'");
		
			if ($this->data) {
      			$sql = "update customer set cart = '?', ip = '?' where customer_id = '?'";
      			$this->database->query($this->database->parse($sql, serialize($this->cart->getData('cart')), $_SERVER['REMOTE_ADDR'], $this->session->get('customer_id')));
			} else {
				$this->logout();
			}
  		}
	}
		
  	function login($email, $password) {	
    	if($this->session->get('guest_account')){
			$sql = "select * from customer where email = '?' and status = '0' and guest = '1'";
			$customer_info = $this->database->getRow($this->database->parse($sql, $email));
		} else {
			$sql = "select * from customer where email = '?' and password = '?' and status = '1'";
			$customer_info = $this->database->getRow($this->database->parse($sql, $email, md5($password)));
		}
		

    	if ($customer_info) {	
      		$this->session->set('customer_id', $customer_info['customer_id']);
      
	  		$this->data = $customer_info;

	  		if ($customer_info['cart']) {
        		$this->cart->restore(unserialize($customer_info['cart']));
      		}
      
	  		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
  
  	function logout() {
		$this->session->delete('customer_id');

    	$this->data = array();
  	}
  
  	function isLogged() {
    	return !empty($this->data);
  	}

  	function getId() {
    	return (isset($this->data['customer_id']) ? $this->data['customer_id'] : NULL);
  	}
      
  	function getFirstName() {
		return (isset($this->data['firstname']) ? $this->data['firstname'] : NULL);
  	}
  
  	function getLastName() {
		return (isset($this->data['lastname']) ? $this->data['lastname'] : NULL);
  	}
  
  	function getEmail() {
		return (isset($this->data['email']) ? $this->data['email'] : NULL);
  	}
  
  	function getTelephone() {
		return (isset($this->data['telephone']) ? $this->data['telephone'] : NULL);
  	}
  
  	function getFax() {
		return (isset($this->data['fax']) ? $this->data['fax'] : NULL);
  	}
  
  	function getAddressId() {
		return (isset($this->data['address_id']) ? $this->data['address_id'] : NULL);	
  	}
	
  	function getNewsLetter() {
		return (isset($this->data['newsletter']) ? $this->data['newsletter'] : NULL);	
  	}

	function get_address($address_id){
	        $result = $this->database->getRow("select country_id from address where address_id = '" . (int)$address_id . "'");
	        return $result;
	}

	function country_compare($address_id) {
	        $customer_country_id = $this->get_address($address_id);
		return ($customer_country_id['country_id'] == $this->config->get('config_country_id') ? TRUE : FALSE);
	}
}
?>