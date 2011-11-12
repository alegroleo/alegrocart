<?php //AlegroCart
class PaymentccAvenue extends Payment {
    function __construct(&$locator) {
        $this->address   =& $locator->get('address');
        $this->cart      =& $locator->get('cart');
		$this->ccavenue  =& $locator->get('ccavenue');
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
		$model 			=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
        
        $this->language->load('extension/payment/ccavenue.php');
    }
	
	    function get_Title() {
        return $this->language->get('text_ccavenue_title');
    }

	    function getMethod() {
        if ($this->config->get('ccavenue_status')) {
            if (!$this->config->get('ccavenue_geo_zone_id')) {
                $status = true;
			} elseif ($this->modelPayment->get_ccavenuestatus()){
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
                'id'         => 'ccavenue',
                'title'      => $this->language->get('text_ccavenue_title'),
                'sort_order' => $this->config->get('ccavenue_sort_order')
            );
        }
   
        return $method_data;
    }
	function form_Type(){
		return 'application/x-www-form-urlencode';
	}
	
	function get_ActionUrl() {
			//return 'http://www.alegrocart.com/gateway/ccavenue_test.php';
            return 'https://www.ccavenue.com/shopzone/cc_details.jsp';
    }
	
	function fields(){
		$currency_data = explode(',', $this->config->get('ccavenue_currency'));
        if (in_array($this->currency->getCode(), $currency_data)) {
            $currency = $this->currency->getCode();
        } else {
            $currency = $this->config->get('config_currency');
        }
		$merchant_id = $this->config->get('ccavenue_merchant_id');
		$amount = $this->currency->format($this->order->get('total'), $currency, FALSE, FALSE);
		$order_id = $this->order->getReference();
		$redirect_url = $this->url->rawssl('checkout_process', 'index', array('method' => 'return', 'ref' => $this->order->getReference()));
		$working_key = $this->config->get('ccavenue_working_key');
		$checksum = $this->ccavenue->getCheckSum($merchant_id, $amount, $order_id, $redirect_url, $working_key);
		
		$fields=array();
		$fields['Merchant_Id'] = $merchant_id;
		$fields['Amount'] = $amount;
		$fields['Order_Id'] = $order_id;
		$fields['Redirect_Url'] = $redirect_url;
		$fields['Checksum'] = $checksum;
		$fields['billing_cust_name'] = $this->order->get('payment_firstname') . ' ' . $this->order->get('payment_lastname');
		$fields['billing_cust_address'] = $this->order->get('payment_address_1') . ($this->order->get('payment_address_2') ? ' ' . $this->order->get('payment_address_2') : NULL);
		$fields['billing_cust_country'] = $this->order->get('payment_country');
		$fields['billing_cust_state'] = $this->order->get('payment_zone');
		$fields['billing_cust_city'] = $this->order->get('payment_city');
		$fields['billing_zip_code'] = $this->order->get('payment_postcode');
		$fields['billing_cust_tel'] = $this->order->get('telephone');
		$fields['billing_cust_email'] = $this->order->get('email');
		if($this->order->get('shipping_firstname') & $this->order->get('shipping_lastname')){
			$fields['delivery_cust_name'] = $this->order->get('shipping_firstname') . ' ' . $this->order->get('shipping_lastname');
			$fields['delivery_cust_address'] = $this->order->get('shipping_address_1') . ($this->order->get('shipping_address_2') ? ' ' . $this->order->get('shipping_address_2') : NULL);
			$fields['delivery_cust_country'] = $this->order->get('shipping_country');
			$fields['delivery_cust_state'] = $this->order->get('shipping_zone');
			$fields['delivery_cust_city'] = $this->order->get('shipping_city');
			$fields['delivery_zip_code'] = $this->order->get('shipping_postcode');		
			$fields['delivery_cust_tel'] = $this->order->get('telephone');
		}
		
		$output=array();
        foreach ($fields as $key => $value) {
            $output[]='<input type="hidden" name="'.$key.'" value="'.$value.'">';
        }

        $output=implode("\n",$output);
        
        return $output;
	}
	function process(){
		if ($this->request->gethtml('method') == 'return') { 
			$WorkingKey = $this->config->get('ccavenue_working_key');
			$Merchant_Id= isset($_REQUEST['Merchant_Id']) ? $this->request->clean($_REQUEST['Merchant_Id']) : '';
			$Amount= isset($_REQUEST['Amount']) ? $this->request->clean($_REQUEST['Amount']) : '';
			$Order_Id= isset($_REQUEST['Order_Id']) ? $this->request->clean($_REQUEST['Order_Id']) : '';
			$Merchant_Param= isset($_REQUEST['Merchant_Param']) ? $this->request->clean($_REQUEST['Merchant_Param']) : '';
			$Checksum= isset($_REQUEST['Checksum']) ? $this->request->clean($_REQUEST['Checksum']) : '';
			$AuthDesc= isset($_REQUEST['AuthDesc']) ? $this->request->clean($_REQUEST['AuthDesc']) : '';
			
			$Checksum = $this->ccavenue->verifyChecksum($Merchant_Id, $Order_Id , $Amount, $AuthDesc, $Checksum, $WorkingKey);

			if($Checksum=="true" && $AuthDesc=="Y"){
				//Your credit card has been charged and your transaction is successful
				$this->order->load($this->request->gethtml('ref'));
				$order_status = $this->config->get('config_order_status_id');
				$this->order->process($order_status);
				return true; // return to checkout_process page
			}else if($Checksum=="true" && $AuthDesc=="B"){
				//pending awaiting American Express authorization
				$this->order->load($this->request->gethtml('ref'));
				$result = $this->modelPayment->get_orderstatus_id('processing', '1');
				$order_status = $result['order_status_id'];
				$this->order->process($order_status);
				$this->session->set('checkout_success', 'pending');
				return true;
			}else if($Checksum=="true" && $AuthDesc=="N"){
				$this->session->set('checkout_failure', 'declined');
				return false;
			} else {
				return false;
				//Security Error. Illegal access detected
				//die('Unknown process method error: No return method was specified. Please contact store owner.');
			}
		}
	}
}
?>