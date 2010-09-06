<?php //AlegroCart
class PaymentGoogle extends Payment {
	function __construct(&$locator) {
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->customer =& $locator->get('customer');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->mail     = $locator->create('mail');
		$this->order    =& $locator->get('order');
		$this->request  =& $locator->get('request');
		$this->response =& $locator->get('response');
		$this->session  =& $locator->get('session');
		$this->shipping =& $locator->get('shipping');
		$this->tax      =& $locator->get('tax');
		$this->url      =& $locator->get('url');
	    $model 			=& $locator->get('model');
		$this->modelPayment = $model->get('model_payment');
		
		$this->language->load('extension/payment/google.php');
		$locator->get('session')->set('payment_form_enctype','application/x-www-form-urlencoded');
  	}
  
	function get_Title() {
		return $this->language->get('text_google_title');
  	}
   
  	function getMethod() {
		if ($this->config->get('google_status')) {
      		if (!$this->config->get('google_geo_zone_id')) {
        		$status = true;
      		} elseif ($this->modelPayment->get_googlestatus()){
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
        		'id'         => 'google',
        		'title'      => $this->language->get('text_google_title'),
				'sort_order' => $this->config->get('google_sort_order')
      		);
    	}
    	return $method_data;
  	}
  /* Process the order but set the status to "Paid Unconfirmed"
    * Note:
    *       The value for "order_status_paid_unconfirmed" and "order_status_pending" 
    *       in the language file for the google payment extension for each
    *       supported language MUST exactly match the 'name' field of the order_status
    *       table for the same language */
    function process() 
    {
        $this->order->load($this->order->getReference());
		$results = $this->modelPayment->get_orderstatus_id( $this->language->get('order_status_paid_unconfirmed'), $this->language->getId()); 
        //$sql = "select `order_status_id` from `order_status` where `name` = '?' and `language_id` = '?'";
        //$parsed = $this->database->parse($sql, $this->language->get('order_status_paid_unconfirmed'), $this->language->getId()); 
        //$results = $this->database->getRow($parsed);
        if ($results)
        {
			// copy cart and order-total to a new GoogleCart object
			$merchantId = $this->config->get('google_merchantid');
			$merchantKey = $this->config->get('google_merchantkey');
			chdir('library/google');
			require_once('library/googlecart.php');
			require_once('library/googleitem.php');
			require_once('library/googleshipping.php');
			require_once('library/googletax.php');
			$serverType = ($this->config->get('google_test')) ? 'sandbox' : 'production';
			$currencyGoogle = $this->config->get('google_currency');
			$currencyCart = $this->currency->getCode();
			$totalCart = $this->order->get('total');
			if ($currencyCart!=$currencyGoogle) {
				$result = $this->modelPayment->get_currency($currencyCart);
				$baseValCart = (isset($result['value'])) ? $result['value'] : NULL;
				$result = $this->modelPayment->get_currency($currencyGoogle);
				$baseValGoogle = (isset($result['value'])) ? $result['value'] : NULL;
				if (($baseValCart==NULL) || ($baseValGoogle==NULL)) {
					// this should never happen, we use cart's currency let GoogleCheckout return an error
					$totalGoogle = $totalCart;
					$currencyGoogle = $currencyCart;
				}
				else {
					// convert cart's total into the currency used by Google Checkout
					$totalGoogle = round(($totalCart * $baseValCart) / $baseValGoogle);
				}
			}
			else {
				$totalGoogle = $totalCart;
			}
			$cart = new GoogleCart($merchantId, $merchantKey, $serverType, $currencyGoogle);
			$item_1 = new GoogleItem($this->config->get('config_store'),'order# '.$this->order->getReference(),1,$totalGoogle);
			$item_1->SetTaxTableSelector("including all taxes");
			$cart->AddItem($item_1);
			$tax_table = new GoogleAlternateTaxTable("including all taxes");
			$tax_rule_1 = new GoogleAlternateTaxRule(0.00);
			$tax_rule_1->SetWorldArea(true);
			$tax_table->AddAlternateTaxRules($tax_rule_1);
			$cart->AddAlternateTaxTables($tax_table);
//			$tax_rule_1 = new GoogleDefaultTaxRule(0.175);
//			$tax_rule_1->SetWorldArea(true);
//			$cart->AddDefaultTaxRules($tax_rule_1);
			
			// Have AlegroCart process the order and remove its cart from the session.
			// AlegroCart will store it in the database with a 'Paid Unconfirmed' order status.
			$this->order->process($results['order_status_id']);
			$this->cart->clear();

			// This will do a server-to-server Google cart post and send an HTTP 302 redirect status
			// More info http://code.google.com/apis/checkout/developer/index.html#alternate_technique
			list($status, $error) = $cart->CheckoutServer2Server();

			// If it reaches this point then something went wrong
			echo "An error had ocurred: <br />HTTP Status: " . $status. ":";
			echo "<br />Error message:<br />";
			echo $error;
			echo "<br />";
			exit;

        }
        else
        {
            // I think it may be better to die here with a message as it is
            // a major configuration problem that should be found by even
            // the most basic testing and hence not impact upon a customer.
            die('Configuration error: You MUST have created an order status for "Paid Unconfirmed" for every installed language.');
            //  The following is a reasonable alternative but there is no way without making
            //  changes to checkout_failure, to get a user defined message to the that page.
            //  The message, as above, is a big help in tracking any teething problems with this code.
            //$this->response->redirect($this->url->ssl('checkout_failure'));   
        }
    }
	function get_ActionUrl() {
		return $this->url->ssl('checkout_process');
	}
	function fields() {	
		return '';
	}
  /* The callback handler for Google Checkout. If Google does call back with a verification
    * then that is nice but we don't rely on it as a work flow step in order processing.
    * Note:
    *       The value for "order_status_paid_unconfirmed" and "order_status_pending" 
    *       in the language file for the Google payment extension for each
    *       supported language MUST exactly match the 'name' field of the order_status
    *       table for the same language*/
	function callback() {
		// Log the message from Google
		chdir('library/google');
		require_once('library/googlelog.php');
		$log = new GoogleLog( '../../logs/googleerror.log', '../../logs/googlemessage.log', L_ALL );
		foreach ($_POST as $key => $val) {
			$log->LogRequest( "$key = $val");
		}
		$type = '';
		if (isset($_POST['_type'])) {
			$type = $_POST['_type'];
		}
		if ($type == 'new-order-notification') {
			// Google has received a newly submitted order
			// Store Google's order number and total into database
			if (isset($_POST['google-order-number'])) {
				$orderNumber = $_POST['google-order-number'];
				if (isset($_POST['shopping-cart_items_item-1_item-description'])) {
					$orderReference = $_POST['shopping-cart_items_item-1_item-description'];
					$i = strpos( $orderReference, 'order# ', 0 );
					if (!(i===FALSE)) {
						$orderReference = substr( $orderReference, $i+strlen('order# ') );
						if (isset($_POST['order-total'])) {
							$orderTotal = $_POST['order-total'];
							$this->modelPayment->delete_order_google($orderReference);
							$this->modelPayment->insert_order_google($orderReference, $orderNumber, $orderTotal);
						}
					}
				}
			}
		}
		else if ($type == 'charge-amount-notification') {
			// Google has successfully processed the payment
			// Change AlegroCart order status to 'pending' and remove Google order entry from database
			if (isset($_POST['google-order-number'])) {
				$orderNumber = $_POST['google-order-number'];
				$result = $this->modelPayment->get_google_order($orderNumber);
				if ($result) {
					$orderReference = $result['order_reference'];
					$total = $result['total'];
					$result = $this->modelPayment->get_orderstatus_id($this->language->get('order_status_pending'), $this->language->getId());
					if ($result) {
						$orderStatusId = $result['order_status_id'];
						if (isset($_POST['total-charge-amount'])) {
							$amountCharged = $_POST['total-charge-amount'];
							if ($amountCharged == $total) {
								$this->modelPayment->update_order_status($orderStatusId, $orderReference);
								$this->modelPayment->delete_order_google($orderReference);
							}
						}
					}
				}
			}
		}
		else if ($type == 'order-state-change-notification') {
			// Google has changed the order state.
			if (isset($_POST['new-financial-order-state'])) {
				$orderState = $_POST['new-financial-order-state'];
				if ($orderState == 'CANCELLED') {
					// Google has cancelled the order.
					// We have to remove the order from the AlegroCart database.
					if (isset($_POST['google-order-number'])) {
						$orderNumber = $_POST['google-order-number'];
						$result = $this->modelPayment->get_google_order($orderNumber);
						if ($result) {
							$orderReference = $result['order_reference'];
							$result = $this->modelPayment->get_orderstatus_id($this->language->get('order_status_canceled'), $this->language->getId());
							if ($result) {
								$orderStatusId = $result['order_status_id'];
								$this->modelPayment->update_order_status($orderStatusId, $orderReference );
								$this->modelPayment->delete_order_google($orderReference);
							}
						}
					}
				}
			}
		}
		exit;
	}
}
?>