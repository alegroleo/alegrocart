<?php //AlegroCart
class PaymentPayPal extends Payment {
    function __construct(&$locator) {
        $this->address   =& $locator->get('address');
        $this->cart      =& $locator->get('cart');
        $this->config    =& $locator->get('config');
        $this->coupon    =& $locator->get('coupon');
        $this->currency  =& $locator->get('currency');
        $this->customer  =& $locator->get('customer');
        $this->database  =& $locator->get('database');
        $this->language  =& $locator->get('language');
        $this->mail      = $locator->create('mail');
        $this->order     =& $locator->get('order');
        $this->request   =& $locator->get('request');
        $this->response  =& $locator->get('response');
        $this->session   =& $locator->get('session');
        $this->shipping  =& $locator->get('shipping');
        $this->tax       =& $locator->get('tax');
        $this->url       =& $locator->get('url'); 
		$this->weight    =& $locator->get('weight');
		$model 			=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
        
        $this->language->load('extension/payment/paypal.php');
    }
  
    function get_Title() {
        return $this->language->get('text_paypal_title');
    }
   
    function getMethod() {
        if ($this->config->get('paypal_status')) {
            if (!$this->config->get('paypal_geo_zone_id')) {
                $status = true;
			} elseif ($this->modelPayment->get_paypalstatus()){
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
                'id'         => 'paypal',
                'title'      => $this->language->get('text_paypal_title'),
                'sort_order' => $this->config->get('paypal_sort_order')
            );
        }
   
        return $method_data;
    }

      
    function get_ActionUrl() {
        if (!$this->config->get('paypal_test')) {
            return 'https://www.paypal.com/cgi-bin/webscr';
			//return 'http://www.alegrocart.com/gateway/paypal_test.php';
        } else {
            return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
    }
      
    function fields() {    
        // New feature in checkout_process to prove that the checkout button was actually clicked by setting the confirm_id = session_id() and verifying it after post.
        //$this->session->set('confirm_id', session_id());
       
        // Validate the current currency is available for paypal, else use default currency. Weak design.
        $currency_data = explode(',', $this->config->get('paypal_currency'));
        if (in_array($this->currency->getCode(), $currency_data)) {
            $currency = $this->currency->getCode();
        } else {
            $currency = $this->config->get('config_currency');
        }

        $fields=array();
        $i=1;
        if ($this->config->get('paypal_itemized') && empty($this->coupon->data) && !$this->order->get('discount_total')) {
            $fields['cmd']='_cart';
            $fields['upload']=1;     
            $fields['shipping_1'] = $this->shipping->getCost($this->session->get('shipping_method')) ? $this->currency->format($this->shipping->getCost($this->session->get('shipping_method')), $currency, FALSE, FALSE) : '0';           
            $taxtotal = 0;
			foreach ($this->cart->getTaxes() as $key => $value) {
				$taxtotal += $this->currency->format($value, $currency, FALSE, FALSE);
			}
			$fields['tax']=$this->currency->format($taxtotal, $currency, FALSE, FALSE);
			$fields['tax_cart']=$this->currency->format($taxtotal, $currency, FALSE, FALSE);

            foreach ($this->cart->getProducts() as $result) {
				$special_price = $result['special_price'] ?$result['special_price'] - $result['discount'] : 0;
				$regular_price = $result['price'] - $result['discount'];
				$price = $special_price > 0 ? $special_price : $regular_price;
                $fields['item_number_' . $i . '']=$result['model_number'];
                $fields['item_name_' . $i . '']=$result['name'];
                $fields['amount_' . $i . '']=$this->currency->format($price, $currency, FALSE, FALSE);
                $fields['quantity_' . $i . '']=$result['quantity'];
                $fields['weight_' . $i . '']=number_format($this->weight->convert($result['weight'],$result['weight_class_id'],$this->config->get('config_weight_class_id')),2,'.', '');
                if (!empty($result['option'])) {
                    $x=0;
                    foreach ($result['option'] as $res) {
                        $fields['on' . $x . '_' . $i . '']=$res['name'];
                        $fields['os' . $x . '_' . $i . '']=$res['value'];
                        $x++;
                    }
                }
                $i++;
            }
        } else {
            $fields['cmd']='_xclick';
            $fields['item_name']=$this->config->get('config_store');
            $fields['amount']=$this->currency->format($this->order->get('total'), $currency, FALSE, FALSE);       
        }
        
        $fields['business']=$this->config->get('paypal_email');
        $fields['currency_code']=$currency;
        $fields['first_name']=$this->order->get('firstname');
        $fields['last_name']=$this->order->get('lastname');
        $fields['address1']=$this->order->get('payment_address_1');
		if($this->order->get('payment_address_2')){
			$fields['address2']=$this->order->get('payment_address_2');
		}
        $fields['city']=$this->order->get('payment_city');
        $fields['zip']=$this->order->get('payment_postcode');
        $fields['country']=$this->order->get('payment_country');
        $fields['address_override']=0;
        $fields['notify_url']=$this->url->rawssl('checkout_process', 'callback', array('payment' => 'paypal', 'method' => 'ipn', 'ref' => $this->order->getReference()));
        $fields['email']=$this->order->get('email');
        $fields['invoice']=$this->order->getReference();
        $fields['lc']=$this->language->getCode();
        $fields['return']=$this->url->rawssl('checkout_process', 'index', array('method' => 'return', 'ref' => $this->order->getReference()));
        $fields['rm']=2; //NOT USED IF AUTORETURN IS ENABLED
        $fields['no_note']=1;
        $fields['cancel_return']=$this->url->rawssl('checkout_process', 'index', array('method' => 'cancel'));
        $fields['paymentaction']=$this->config->get('paypal_auth_type');
        $fields['custom']=session_id();
      
        $output=array();
        foreach ($fields as $key => $value) {
            $output[]='<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }

        $output=implode("\n",$output);
        
        return $output;
        
    }
    
    function process() {
       
        // if customer canceled then return to checkout_confirm page for retry.
        if ($this->request->gethtml('method') == 'cancel') {  
            $this->response->redirect($this->url->rawssl('checkout_confirm',false,array('error'=> $this->language->get('error_canceled'))));
        
        // If customer clicks on Return to Merchant or Auto-returns.
        // Remember: At this point, the customer paid for "something" 
        // The only way to hit the return path is to successfully pay
        // So he should only see success message, even if there is a problem.
        // Problems can be handled later by the store owner.
        } elseif ($this->request->gethtml('method') == 'return') {
            
            $this->order->load($this->order->getReference());
            $this->order->process($this->getOrderStatusId('order_status_paid_unconfirmed'));

            /////////////////////////////////////////////
            // Use PDT if available and PDT Token is set
            /////////////////////////////////////////////
            if ($this->request->get('tx') != null && $this->config->get('paypal_pdt_token') != '') {
                
                // Paypal possible values for payment_status
                $success_status = array('Completed', 'Pending', 'In-Progress', 'Processed');
                $failed_status = array('Denied', 'Expired', 'Failed');
                
                // read the post from PayPal system and add 'cmd'
                $req = 'cmd=_notify-synch';

                $tx_token = $this->request->get('tx');
                $auth_token = $this->config->get('paypal_pdt_token');
                $req .= "&tx=$tx_token&at=$auth_token";

                // post back to PayPal system to validate
                $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
                $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
                $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
                
                $url=$this->config->get('paypal_test')?'ssl://www.sandbox.paypal.com':'ssl://www.paypal.com';
                // NON-SSL
                //$fp = @fsockopen ($url, 80, $errno, $errstr, 30);
                // SSL
                $fp = fsockopen ($url, 443, $errno, $errstr, 30);

                if ($fp) {
                    fputs ($fp, $header . $req);
                    // read the body data
                    $res = '';
                    $headerdone = false;
                    while (!feof($fp)) {
                        $line = fgets ($fp, 1024);
                        if (strcmp($line, "\r\n") == 0) {
                            // read the header
                            $headerdone = true;
                        } else if ($headerdone) {
                            // header has been read. now read the contents
                            $res .= $line;
                        }
                    }
                    fclose ($fp);
                    
                    // parse the data
                    $lines = explode("\n", $res);
                    $keyarray = array();
                    $error = '';
                    if (strcmp ($lines[0], "SUCCESS") == 0) {
                        for ($i=1; $i<(count($lines)-1);$i++){
                            list($key,$val) = explode("=", $lines[$i]);
                            $keyarray[urldecode($key)] = urldecode($val);
                        }
                        
                        // Verify the payment_status isn't failed
                        if (in_array($keyarray['payment_status'], $failed_status)) {
                            $error = $this->language->get('error_failed_payment');
                        }
                        
                        // Verify the payment_gross matches the order total
                        $currency_data = explode(',', $this->config->get('paypal_currency'));
                        if (in_array($this->currency->getCode(), $currency_data)) {
                            $currency = $this->currency->getCode();
                        } else {
                            $currency = $this->config->get('config_currency');
                        }
                        if ($this->currency->format(($this->cart->getTotal() + $this->shipping->getCost($this->session->get('shipping_method'))) , $currency, FALSE, FALSE) != $keyarray['payment_gross']) {
                            $error = $this->language->get('error_mismatched_amount'); 
                        }

                        // Verify the receiver_email matches your paypal email
                        if ($this->config->get('paypal_email') != $keyarray['receiver_email']) {
                            $error = $this->language->get('error_wrong_receiver'); 
                        }
                        
                        // If there are no errors, update payment status.
                        // If there are errors, we still want to show success since the customer did pay
                        // It will just leave the order in "Paid Unconfirmed" state.
                        // Need a way to convey the real error to the store owner aside from not updating the order
                        if (!$error) { 
                            $this->orderUpdate();
                        }

                    } else {
                        // PDT Failed, but payment was still made, so customer should see success
                        // The order will just be in "Paid Unconfirmed" state for store owner to review.
                        // Need a way to convey the real error to the store owner aside from not updating the order
                        // This could happen for invalid PDT Token
                        // $error = $this->language->get('error_fail_header'); 
                    }
                } //eof if $fp
            } //eof if pdt
            return true; // return to checkout_process page
        } else {
            die('Unknown process method error: No return method was specified. Please contact store owner.');
        }
        return false; // return to checkout_process page
    }
    
    /**
    * The modified callback handler for paypal. If paypal does call back with a verification
    * then that is nice but we no longer rely on it as a work flow step in order processing.
    * 
    * Note:
    *       The value for "order_status_paid_unconfirmed" and "order_status_pending" 
    *       in the language file for the paypal payment extension for each
    *       supported language MUST exactly match the 'name' field of the order_status
    *       table for the same language. 
    */
    function callback() {
        // if IPN callback is called
        if ($this->request->gethtml('method') == 'ipn'){ 
            $this->order->load($this->request->get('ref'));
            $this->order->process($this->getOrderStatusId('order_status_paid_unconfirmed'));
            // read the post from PayPal system and add 'cmd'
            $req = 'cmd=_notify-validate';
            foreach ($_POST as $key => $value) {
                $req .= '&' . $key . '=' . urlencode(stripslashes($value));
            }
            //Debug
            if ($this->config->get('paypal_ipn_debug')) { $this->DoDebug($req); }
            // post back to PayPal system to validate
            $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
            
            $url=$this->config->get('paypal_test')?'ssl://www.sandbox.paypal.com':'ssl://www.paypal.com';
            //SSL version
            $fp = fsockopen($url, 443, $errno, $errstr, 30);
            //NON-SSL version
            //$fp = fsockopen ($url, 80, $errno, $errstr, 30);
            
            //Debug
            if ($this->config->get('paypal_ipn_debug')) { $this->DoDebug($header . $req); }
            
            if ($fp) {
                fputs($fp, $header . $req);            
                while (!feof($fp)) {
                    $res = fgets($fp, 1024);
                }
                fclose($fp);
                
                //Debug
                if ($this->config->get('paypal_ipn_debug')) { $this->DoDebug($res); }
                
                if (strcmp ($res, "VERIFIED") == 0) {
                    $this->orderUpdate(); // Update order to pending or specify new value in language file
                }
            }
        }
    }

    function getOrderStatusId ($status='order_status_paid_unconfirmed') {
        // Lookup order_status_paid_unconfirmed status id specified by the exact name in the language file.
        $results = $this->modelPayment->get_orderstatus_id($this->language->get($status),$this->language->getId());
 
        // if no status exists for the current language, fallback to english status id
        if (empty($results)) { 
			$results = $this->modelPayment->get_orderstatus_id($this->language->get($status),'1');

            if (empty($results)) { // if no status exists for english either, then die
                // Perhaps the check for status should be before checkout so that there is no chance of the customer seeing this after payment is made.
                die('Configuration error: Store owner needs to create an order status for "Paid Unconfirmed" for every installed language.' );
            }
        }
        return $results['order_status_id'];
    }

    // Debug logging
    function DoDebug($msg='') {
        $f=fopen("paypal_ipn_debug_" . date("Ymd") . ".txt","a");             
        fwrite($f, $msg . "\r\n\r\n");
        fclose($f);
    }

    // Update order to "Pending" (default) or specify variable name of the status you'd like as listed in the language file.
    function orderUpdate($status = 'final_order_status', $override = 0) {
        //Find the paid_unconfirmed status id
        $results = $this->getOrderStatusId('order_status_paid_unconfirmed');
        $paidUnconfirmedStatusId = $results?$results:0;
        //Find the final order status id
        $results = $this->getOrderStatusId($status);
        $finalStatusId = $results?$results:0;
        $reference = $this->request->get('ref');
        //Get Order Id
		$res = $this->modelPayment->get_order_id($reference);
        $order_id = $res['order_id'];
        //Update order only if state in paid unconfirmed OR override is set
        if ($order_id) {
            if ($override) {
                // Update order status
                $result = $this->modelPayment->update_order_status_override($finalStatusId,$reference);
                // Update order_history
                if ($result) {
					$this->modelPayment->update_order_history($order_id, $finalStatusId, 'override');
                }
            } else {
                // Update order status only if status is currently paid_unconfirmed
				$result = $this->modelPayment->update_order_status_paidunconfirmed($finalStatusId, $reference, $paidUnconfirmedStatusId);
                // Update order_history
                if ($result)  {
					$this->modelPayment->update_order_history($order_id, $finalStatusId, 'PDT/IPN');
                }
            }
        }
    }
}
?>