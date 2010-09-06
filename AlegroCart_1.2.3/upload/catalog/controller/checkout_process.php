<?php 
class ControllerCheckoutProcess extends Controller {
	function index() { 	
		$cart     =& $this->locator->get('cart');		
		$payment  =& $this->locator->get('payment');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		
		if ($payment->runProcess($session->get('payment_method'))) {
			$cart->clear();
			$response->redirect($url->ssl('checkout_success'));
		} else {
			$response->redirect($url->ssl('checkout_failure'));
		}
  	}
	
	function callback() {
		$request =& $this->locator->get('request');
		$payment =& $this->locator->get('payment');
			
    	$payment->runCallback($request->gethtml('payment'));
	}
}
?>