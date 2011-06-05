<?php //AlegroCart
class PaymentAuthNetAim extends Payment {
    var $errors = array();
    var $error;
	var $delimiter = ',';
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->ccvalidation =& $locator->get('ccvalidation');  //New
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
		$this->language->load('extension/payment/ccvalidation.php');
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
        $output .= '<div class="cc_form" style="border: 3px solid #0099FF; border-radius: 10px ; margin-bottom: 10px; padding: 2px; background-color: #EEEEEE;">'; 
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
                        $this->session->set('error', $this->language->get($this->errors[0]));
                    }
                    $this->response->redirect($this->url->rawssl('checkout_confirm',FALSE));
                }
            }
        
            // Process the Card
            $status = $this->paymentConfirmed();
            if ($status == 1) {
                $this->session->set('authnetaim_confirmed', $this->order->getReference());
				$order_status = $this->config->get('config_order_status_id');
			} else if ($status == 2) {	
				$this->session->set('checkout_failure', 'declined');
				$this->session->delete('authnetaim_confirmed');
				$this->session->delete('cc_number');
				$this->session->delete('cc_month');
				$this->session->delete('cc_year');
				$this->session->delete('cc_cvv');  
				return FALSE;
			}  else if ($status == 4){
				$this->session->set('authnetaim_confirmed', $this->order->getReference());
				$this->session->set('checkout_success', 'pending');
				$result = $this->modelPayment->get_orderstatus_id('processing', '1');
				$order_status = $result['order_status_id'];
            } else {
                $this->session->set('error', $status);
                $this->response->redirect($this->url->rawssl('checkout_confirm',FALSE));
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
				$this->order->process($order_status);
				return TRUE;
            } else {
				return FALSE;   
            }
        }
        return FALSE; // if not post, then checkout_process was accessed directly. Checkout Failed.
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
			//$urlInfo = parse_url('http://www.alegrocart.com/gateway/authorize_net_test.php');
        } else {
			//$urlInfo = parse_url('http://www.alegrocart.com/gateway/authorize_net_test.php');
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
				
				$file_mask = substr(md5(time()), 0, mt_rand(24,32))  . "\r\n\r\n";
                file_put_contents('logs/authnetaim_logs/aim_req' . $this->order->getReference() . '.txt', $this->mask_request($file_mask . $req));
				
                $reply = '';
                fputs($fp, $header . $req); 
                $counter = 0; 
				$headerdone = false;
				$res = '';
				
                while (!@feof($fp)) {
                    $counter++;
                    $retVal = @fgets($fp, 1024); 
                    $reply .= $retVal;
					if (strcmp($retVal, "\r\n") == 0) {
						// read the header
                        $headerdone = true;
					} else if ($headerdone) {
						// header has been read. now read the contents
						$res .= $retVal;
					}
                }
                fclose($fp);
                file_put_contents('logs/authnetaim_logs/aim_res' . $this->order->getReference() . '.txt', $this->mask_reply($file_mask . $res));

                if (strpos($res, $this->delimiter) !== FALSE){
					$status = '';
					$code = explode($this->delimiter, $res);
					if ($code[0] == 1){
						$status = 1;
					} else if ($code[0] == 4){
						$status = 4;
						$this->session->set('checkout_success', 'pending');
					} else if ($code[0] == 2){
						$status = 2;	
					} else if ($code[0] == 3){
						$status = $code[3];
					}
				} else if(strpos($reply, $this->delimiter) !== false) {
                    $status = '';
                    $resline = substr($reply, strpos($reply, $this->delimiter) - 1);
                    $code = explode($this->delimiter, $resline);
                    for ($i=0;$i<5;$i++) {
                    	$status .= $code[$i] . '|';
					}
				} else {
					$status = $reply;
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
	
	private function mask_request($req){
		$file_req = $this->ccvalidation->mask_cc(preg_replace('#[ -]#', '', $this->request->gethtml('cc_number', 'post')), $req);
		$file_req = $this->ccvalidation->mask_cvc(preg_replace('#[ -]#', '', $this->request->gethtml('cc_cvv', 'post')) ,$file_req);
		$file_req = $this->ccvalidation->mask_expiry($this->expiry(),$file_req);
		$file_req = $this->ccvalidation->enCrypt($file_req, trim($this->config->get('config_token')));
		
		return $file_req;
	}
	private function mask_reply($reply){
		$file_reply = explode(',', $reply);
		$mask = $this->ccvalidation->mask_approval($file_reply[4], $reply);
		$mask = $this->ccvalidation->enCrypt($mask, trim($this->config->get('config_token')));
		return $mask;
	}
	
	private function expiry(){
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
		return $expiryDate;
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
            
        $expiryDate =  $this->expiry();
        
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
          'x_delim' => $this->delimiter
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
	function callback() {
        //unused for this module since there is no callback needed
    }
   
    private function validateForm() {  // Pre-Validates user input
        // Save the fields to the session for repopulation
        $this->session->set('cc_number', $this->request->gethtml('cc_number', 'post'));
        $this->session->set('cc_month', $this->request->gethtml('cc_month', 'post'));
        $this->session->set('cc_year', $this->request->gethtml('cc_year', 'post'));
        $this->session->set('cc_cvv', $this->request->gethtml('cc_cvv', 'post'));
        
        $this->errors = array();
        
        $ccMonth = $this->request->gethtml('cc_month', 'post');
        $ccYear = $this->request->gethtml('cc_year', 'post');
        $ccCVV = $this->request->gethtml('cc_cvv', 'post'); 

        $card_type = $this->ccvalidation->get_cc_type($this->request->gethtml('cc_number', 'post'));

        if(!$card_type){
			$this->errors = $this->ccvalidation->errors;
        }
		if($this->ccvalidation->validate_date($this->request->gethtml('cc_month', 'post'), $this->request->gethtml('cc_year', 'post'))){
			$this->errors = $this->ccvalidation->errors;
		}
        if($this->ccvalidation->validate_cvv($this->request->gethtml('cc_cvv', 'post'), $card_type)){
			$this->errors = $this->ccvalidation->errors;
		}

        return (count($this->errors) == 0);
    }
}
?>