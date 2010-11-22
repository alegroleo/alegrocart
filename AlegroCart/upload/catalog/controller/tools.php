<?php   // Tools AlegroCart
class ControllerTools extends Controller {
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->currency  	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		
		$this->language->load('controller/tools.php');
	}

	function convert_currency(){
		$from = $this->request->sanitize('from');
		$to = $this->request->sanitize('to');
		$amount = $this->request->sanitize('amount') ? $this->request->sanitize('amount') : '1' ;

		$conversion = (strlen($from) == 3 && strlen($to) == 3) ?$this->currency->currency_converter($amount, $from, $to) : FALSE;
		
		if($conversion){
			$output = "$amount $from = $conversion $to <br>"; 
			$output .= $this->language->get('text_rate_source');
		} else {
			$output = $this->language->get('text_no_conversion');
		}
		$this->response->set($output);
	}
}
?>