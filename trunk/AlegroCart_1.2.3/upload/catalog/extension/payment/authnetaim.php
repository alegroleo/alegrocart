<?php //AlegroCart
class PaymentAuthNetAim extends Payment {
    var $errors = array();
    var $error;
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->customer =& $locator->get('customer');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->order    =& $locator->get('order');
        $this->request  =& $locator->get('request');
        $this->response =& $locator->get('response');
		$this->session  =& $locator->get('session');
		$this->shipping =& $locator->get('shipping');
		$this->tax      =& $locator->get('tax');
        $this->url      =& $locator->get('url');
        $this->session  =& $locator->get('session');
        $this->locator  =& $locator;	    
		$this->mail     = $locator->create('mail');
		$model 			=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
        
		$this->language->load('extension/payment/authnetaim.php');
  	}
  
	function get_Title() {
		return $this->language->get('text_authnetaim_title');
  	}
   
  	function getMethod() {
		if ($this->config->get('authnetaim_status')) {
      		if (!$this->config->get('authnetaim_geo_zone_id')) {
        		$status = true;
			} elseif ($this->modelPayment->get_authnetaimstatus()){
      		  	$status = true;
      		} else {
     	  		$status = false;
			}	
      	} else {
			$status = false;
		}
		$method_data = array();
		if ($status) {  
      		$method_data = array( 
        		'id'         => 'authnetaim',
        		'title'      => $this->language->get('text_authnetaim_title'),
				'sort_order' => $this->config->get('authnetaim_sort_order')
      		);
    	}
    	return $method_data;
  	}
    
  	function get_ActionUrl() {
        return $this->url->ssl('checkout_process', 'index', array('method' => 'validateForm', 'payment' => 'authnetaim'));  
    }
    
    // create the credit card entry form on the checkout_confirm page
    // Populate from session vars if exist, but then quickly delete the session vars.
  	function fields() {
        $output = '';
        if ($this->config->get('authnetaim_test')) { $output .= '<div class="warning">' . $this->language->get('text_testmode') . '</div>'; }
        $output .= '<div class="cc_form" style="border: 1px solid #EEEEEE; margin-bottom: 10px; padding: 2px; background-color: #eeeeee;">';
        $output .= '<font size="2pt"><strong>' . $this->language->get('text_creditcard') . '</strong></font>' . "\n";
        $output .= '<table>' . "\n";
        //$ouput .= '<tr><td>' . $this->language->get('entry_creditcard_type') . '</td><td><select name="creditcard_type"><option value="none"></option><option value="visa">'. $this->language->get('text_visa') . '</option><option value="mastercard">' . $this->language->get('text_mastercard') . '</option></select></td></tr>' . "\n";
        $output .= '<tr><td><span class="required">*</span>&nbsp;' . $this->language->get('entry_cc_number') . '</td><td><input type="text" name="cc_number" value="' . $this->session->get('cc_number') . '"></td></tr>' . "\n";
        $this->session->delete('cc_number');
        $output .= '<tr><td><span class="required">*</span>&nbsp;' . $this->language->get('entry_cc_expire') . '</td><td><input type="text" name="cc_month" value="' . $this->session->get('cc_month') . '" size="3" maxlength="2"> / <input type="text" name="cc_year" value="' . $this->session->get('cc_year') . '" size="3" maxlength="2"></td></tr>' . "\n";
        $this->session->delete('cc_month');
        $this->session->delete('cc_year');
        $output .= '<tr><td><span class="required">*</span>&nbsp;' . $this->language->get('entry_cc_cvv') . '</td><td><input type="text" name="cc_cvv" value="' . $this->session->get('cc_cvv') . '" size="3"></td></tr>' . "\n";
        $this->session->delete('cc_cvv');        
        $output .= '</table>';
        $output .= '</div>';
        return $output; 
  	}
    
    function process() {
        // Verify customer session didn't time out
        if (!$this->customer->isLogged()) {
              $this->session->set('redirect', $this->url->ssl('checkout_process'));
              $this->response->redirect($this->url->ssl('account_login'));
        }
        if ($this->request->isPost()) {  
            // Validate Client Form (populated and valid)
            if ($this->request->gethtml('method') == 'validateForm') {  
                if (!$this->validateForm()) {
                    if (!empty($this->errors)) {
                        //$this->session->set('message', $this->errors[0]);
                        $this->session->set('error', $this->errors[0]);
                    }
                    $this->response->redirect($this->url->rawssl('checkout_confirm',false));
                }
            }
        
            // Process the Card
            $status = $this->paymentConfirmed();
            if ($status === true) {
                $this->session->set('authnetaim_confirmed', $this->order->getReference());
            } else {
                $this->session->set('error', $status);
                //$this->response->redirect($this->url->rawssl('checkout_confirm',false, array('errcode' => $status)));
                $this->response->redirect($this->url->rawssl('checkout_confirm',false));
            }                    
        
            // Process the order if card successful
            if ($this->session->get('authnetaim_confirmed') == $this->order->getReference()) {
				// Arrived here via normal user interaction from checkout_process.
				$this->session->delete('authnetaim_confirmed');
				$this->session->delete('cc_number');
				$this->session->delete('cc_month');
				$this->session->delete('cc_year');
				$this->session->delete('cc_cvv');  
				$this->order->load($this->order->getReference());
				$this->order->process();
            } else {
				return false;   
            }
        }
        return false; // if not post, then checkout_process was accessed directly. Checkout Failed.
    }

    function callback() {
        //unused for this module since there is no callback needed
    }
   
    // Pre-Validates user input from the form before attempting to send to CC Processor.
    private function validateForm() {
        // Save the fields to the session for repopulation
        $this->session->set('cc_number', $this->request->gethtml('cc_number', 'post'));
        $this->session->set('cc_month', $this->request->gethtml('cc_month', 'post'));
        $this->session->set('cc_year', $this->request->gethtml('cc_year', 'post'));
        $this->session->set('cc_cvv', $this->request->gethtml('cc_cvv', 'post'));
        
        $this->errors = array();
        $ccNumber = preg_replace('#[^0-9 -]#', '', $this->request->gethtml('cc_number', 'post')); // strip non numerics from the text but leave spaces for the moment.
        $ccMonth = $this->request->gethtml('cc_month', 'post');
        $ccYear = $this->request->gethtml('cc_year', 'post');
        $ccCVV = $this->request->gethtml('cc_cvv', 'post'); 

        if (strlen($ccNumber) == 0) {
            $this->errors[] = $this->language->get('error_cc_number_missing');
        } else if (strlen($ccNumber) <> strlen($this->request->gethtml('cc_number', 'post'))) {
            $this->errors[] = $this->language->get('error_cc_number_not_numeric');
        }

        if (strlen($ccMonth) == 0) {
            $this->errors[] = $this->language->get('error_cc_month_missing');
        }
                            
        if (strlen($ccYear) == 0) {
            $this->errors[] = $this->language->get('error_cc_year_missing');
        }
                            
        if (strlen($ccCVV) == 0) {
            $this->errors[] = $this->language->get('error_cc_cvv_missing');
        }
        
        //  No point to continue validating if we already have an error.
        if (count($this->errors) > 0) {
            return false;
        }
                       
        //  now we remove harmless spaces or dashes from the credit card number so that we can test it as a number.
        $ccNumber = preg_replace('#[ -]#', '', $ccNumber);
        
        //  and remove all non numerics from the following and validate them.
        $ccMonth = preg_replace('#[^0-9]#', '', $ccMonth);
        $ccYear = preg_replace('#[^0-9]#', '', $ccYear);
 
        if (strlen($ccMonth) <> strlen($this->request->gethtml('cc_month', 'post'))) {
            $this->errors[] = $this->language->get('error_cc_month_not_numeric');       
        }
        if (strlen($ccYear) <> strlen($this->request->gethtml('cc_year', 'post'))) {
            $this->errors[] = $this->language->get('error_cc_year_not_numeric');       
        }

        //  No point to continue validating if we already have an error.
        if (count($this->errors) > 0) {
            return false;
        }
            
        //  Valid credit card number ?
        $cardNumber = strrev($ccNumber);
        $numSum = 0;
        for($i = 0; $i < strlen($cardNumber); $i++) {
            $currentNum = substr($cardNumber, $i, 1);

            // Double every second digit
            if ($i % 2 == 1) {
                $currentNum *= 2;       
            }

            // Add digits of 2-digit numbers together
            if ($currentNum > 9) {
                $firstNum = $currentNum % 10;
                $secondNum = ($currentNum - $firstNum) / 10;
                $currentNum = $firstNum + $secondNum;
            }
            $numSum += $currentNum;
        }
        if ($numSum % 10 != 0) {
            $this->errors[] = $this->language->get('error_cc_number_invalid');
            return false;            
        }
 
 /*       
        //  Valid number(s) for card type?    
        switch ($ccType) {
            case 'mastercard':
                if (!(ereg("^5[1-5][0-9]{14}$", $ccNumber)))
                    $this->errors[] = $this->language->get('error_cc_number_invalid_for_type'); 
                if (!(ereg("^[0-9][0-9][0-9]$", $ccCVV)))
                    $this->errors[] = $this->language->get('error_mastercard_cvv');   
                break;
            case 'visa':
                if (!(ereg("^4[0-9]{12}([0-9]{3})?$", $ccNumber)))
                    $this->errors[] = $this->language->get('error_cc_number_invalid_for_type'); 
                if (!(ereg("^[0-9][0-9][0-9]$", $ccCVV)))
                    $this->errors[] = $this->language->get('error_visa_cvv');   
                break;
             case 'amex':
                if (!(ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNumber)))
                    $this->errors[] = $this->language->get('error_cc_number_invalid_for_type'); 
                if (!(ereg("^[0-9][0-9][0-9]$", $ccCVV)))
                    $this->errors[] = $this->language->get('error_amex_cvv');   
                break;
             case 'disc':
                if (!(ereg("^6011[0-9]{12}$", $ccNumber)))
                    $this->errors[] = $this->language->get('error_cc_number_invalid_for_type'); 
                if (!(ereg("^[0-9][0-9][0-9]$", $ccCVV)))
                    $this->errors[] = $this->language->get('error_disc_cvv');   
                break;
             case 'diners':
                if (!(ereg("^3(0[0-5]|[68][0-9])[0-9]{11}$", $ccNumber)))
                    $this->errors[] = $this->language->get('error_cc_number_invalid_for_type'); 
                if (!(ereg("^[0-9][0-9][0-9]$", $ccCVV)))
                    $this->errors[] = $this->language->get('error_diners_cvv');   
                break;
             default:
                // got to do something here but it is an error by the implementer.
                $this->errors[] = "unsupported credit card type = " . $ccType; 
        }
   */     
        // Month must be in the range 1 to 12
        $ccMonth = (int)$ccMonth;
        if ($ccMonth < 1 || $ccMonth > 12) {
            $this->errors[] = $this->language->get('error_cc_month_invalid_range');
        }
        
        // Year cannot be more than 10 years into the future
        $ccYear = (int)$ccYear;
        $currentYear = (int)date('y');
        if ($ccYear  > $currentYear + 10) {
            $this->errors[] = $this->language->get('error_cc_year_invalid_range');
        }
        
        // Cannot expire within the next 3 days because the payment is processed on weekdays and it could be friday night
        $margin = 3;
        // $margin days before the 1st day of the month after ValidTo date on card
        $ccExpiresLimit = mktime(0,0,0, $ccMonth + 1,  1 - $margin, $ccYear);  
        $today = mktime(0,0,0);
        if ($today >= mktime(0,0,0, $ccMonth + 1,  1, $ccYear)) {
            $this->errors[] = $this->language->get('error_cc_expired');
        } else if ($today >= $ccExpiresLimit) {
            $this->errors[] = $this->language->get('error_cc_expires_too_soon');
        }
        
        return (count($this->errors) == 0);
    }
    
    
    private function paymentConfirmed() {
        $this->order->load($this->order->getReference());
        
        $form_data = $this->getFormArray();
        $output = '';
        foreach ($form_data as $key => $value) {
            $output .= '&' . $key . '=' . urlencode(stripslashes($value));
        }
        $output = trim($output, '&');
        
        if ($this->config->get('authnetaim_test')) {
            $urlInfo = parse_url('https://test.authorize.net/gateway/transact.dll');           
        } else {
            $urlInfo = parse_url('https://secure.authorize.net/gateway/transact.dll');
        }
       
  
        $status = false;
        try {   
            switch ($urlInfo['scheme']) {
                case 'https':
                    $scheme = 'ssl://';
                    $port = 443;
                    break;
                case 'http':
                default:
                    $scheme = '';
                    $port = 80;   
            }
            //  Use @ before the function name to suppress errors and warnings that would otherwise display on the page.
            $fp = @fsockopen($scheme . $urlInfo['host'], $port, $errno, $errstr, 5);
            if ($fp) {            
                //  Use HTTP/1.0 so that we do not have to deal with chunked data.
                $req = $output;
                $header  = 'POST ' . $urlInfo['path'] . ' HTTP/1.1' . "\r\n";
                $header .= 'Host:' . $urlInfo['host'] . "\r\n";
                $header .= 'Content-Type: text/xml' . "\r\n";
                $header .= 'Connection: close' . "\r\n";
                $header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
                file_put_contents('authnetaim_request.txt', $header . $req);
                $reply = '';
                fputs($fp, $header . $req); 
                $counter = 0; 
                while (!@feof($fp)) {
                    $counter++;
                    $retVal = @fgets($fp, 1024); 
                    $reply .= $retVal;
                }
                fclose($fp);
                file_put_contents('authnetaim_response.txt', $reply);
                if (strpos($reply, 'approved') === false) {
                    //$this->error = $this->language->get('error_authnetaim_invalid_message');
                    if (strpos($reply, "|") !== false) {
                    	$status = '';
                    	$resline = substr($reply, strpos($reply, "|") - 1);
                    	$code = explode("|", $resline);
                    	for ($i=0;$i<5;$i++) {
                    		$status .= $code[$i] . '|';
						}
					} else {
						$status = $reply;
					}
                    //$status = false;
                } else {
                    $status = true;
                }
            } else {
                $this->error = $this->language->get('error_authnetaim_down');
            }
        }
        catch(Exception $ex) {   
            $this->error = $this->language->get('error_authnetaim_down');
        }
         
        return ($status);
    }
    
    
    
     private function getFormArray() {
        
        $currency = 'USD'; // Authorize.net only supports USD     
        $xlogin = '';
        $xtxnkey = '';
        switch($this->config->get('authnetaim_test')) {
            case 0:
                $xlogin = trim($this->config->get('authnetaim_prod_login'));
                $xtxnkey = trim($this->config->get('authnetaim_prod_txnkey'));
                break;
            case 1:
                $xlogin = trim($this->config->get('authnetaim_test_login'));
                $xtxnkey = trim($this->config->get('authnetaim_test_txnkey'));
                break;
            default:
            break;
        }
        
        // Remove any spaces
        $cardNumber = preg_replace('#[ -]#', '', $this->request->gethtml('cc_number', 'post'));
        $cardCVV = preg_replace('#[ -]#', '', $this->request->gethtml('cc_cvv', 'post'));
        
        // Pad expiration date with leading zeroes if needed. 
        $Month = $this->request->gethtml('cc_month', 'post');
        if (strlen($Month) == 1) {
            $Month = '0' . $Month;
        }
            
        $Year = $this->request->gethtml('cc_year', 'post');
        if (strlen($Year) == 1) {
            $Year = '0' . $Year;
        }
            
        $expiryDate =  $Month . $Year;
        
        $form_merchant_array = array(
          'x_login' => $xlogin,
          'x_tran_key' => $xtxnkey
          );
        
        $form_ccinfo_array = array(
          'x_card_num'  => $cardNumber,
          'x_exp_date'  => $expiryDate,
          'x_card_code' => $cardCVV
          );
        
        $form_base_array = array(
          'x_amount' => str_replace(',', '', $this->currency->format($this->cart->getTotal(), $currency, FALSE, FALSE)),
          'x_currency_code' => 'USD', //AIM only supports USD
          'x_version' => '3.1', 
          'x_method' => 'CC',
          'x_type' => ($this->config->get('authnetaim_authtype') == "auth_only" ? 'auth_only' : 'auth_capture'),
          'x_cust_ID' => $this->order->get('customer_id'),
          'x_email_customer' => ($this->config->get('authnetaim_sendemail') == 'TRUE' ? 'TRUE': 'FALSE'),
          'x_company' => $this->order->get('payment_company'),
          'x_first_name' => $this->order->get('payment_firstname'),
          'x_last_name' => $this->order->get('payment_lastname'),
          'x_address' => $this->order->get('payment_address_1') . $this->order->get('payment_address_2'),
          'x_city' => $this->order->get('payment_city'),
          'x_state' => $this->order->get('payment_zone'),
          'x_zip' => $this->order->get('payment_postcode'),
          'x_country' => $this->order->get('payment_country'),
          'x_phone' => $this->order->get('telephone'),
          'x_fax' => $this->order->get('fax'),
          'x_email' => $this->order->get('email'),
          'x_ship_to_company' => $this->order->get('shipping_company'),
          'x_ship_to_first_name' => $this->order->get('shipping_firstname'),
          'x_ship_to_last_name' => $this->order->get('shipping_lastname'),
          'x_ship_to_address' => $this->order->get('shipping_address_1') . $this->order->get('shipping_address_2'),
          'x_ship_to_city' => $this->order->get('shipping_city'),
          'x_ship_to_state' => $this->order->get('shipping_zone'),
          'x_ship_to_zip' => $this->order->get('shipping_postcode'),
          'x_ship_to_country' => $this->order->get('shipping_country'),
          'x_Customer_IP' => $this->order->get('ip'),
          'x_invoice_num' => $this->order->getReference(),
          'x_description' => 'Online purchase from ' . str_replace('"',"'", $this->config->get('config_store')),
          'x_duplicate_window' => '120',
          'x_relay_response' => 'false',    
          'x_delim_data' => 'true',
          'x_delim' => ','
          );
          
        //$form_security_array = $this->InsertFP($xlogin, $xtxnkey, number_format(str_replace(',', '', $this->currency->format($this->order->get('total'), $currency, FALSE, FALSE)), 2,'.',''), $sequence, $currency);
        $form_test_array = array(
          'x_test_request' => 'TRUE'
          );
        
        if (!$this->config->get('authnetaim_test')) {
            $form_data = array_merge($form_merchant_array, $form_base_array, $form_ccinfo_array);
        } else {
            $form_data = array_merge($form_merchant_array, $form_base_array, $form_ccinfo_array, $form_test_array);
        }
        return $form_data;
    }  
}
?>