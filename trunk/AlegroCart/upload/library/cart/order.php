<?php // AlegroCart
class Order {
	var $reference = NULL;
	var $data      = array();
	var $expire    = 3600;
 	
	function __construct(&$locator) {		
		$this->config   =& $locator->get('config');
		$this->coupon   =& $locator->get('coupon');
		$this->database =& $locator->get('database');
		$this->mail     =& $locator->get('mail');
		$this->session  =& $locator->get('session');
				
		$sql = "delete from order_data where expire < '?'";
		$this->database->query($this->database->parse($sql, time()));
		
		$random    = strtoupper(uniqid());
		$reference = substr($random, 0, 5) . '-' . substr($random, 5, 5) . '-' . substr($random . rand(10, 99), 10, 5);
		
		if ($this->session->has('reference')) {
			$sql        = "select distinct * from order_data where reference = '?'";
			$order_info = $this->database->getRow($this->database->parse($sql, $this->session->get('reference')));
			
			if ($order_info) {
				$this->reference = $this->session->get('reference');
			} else {
				$this->reference = $reference;
				
				$this->session->set('reference', $reference);
			}
		} else {
			$this->reference = $reference;
			
			$this->session->set('reference', $reference);
		}	
	}
		
	function getReference() {
		return $this->reference;
	}
	
	function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : NULL);
	}
		
	function load($reference) {			
		$sql        = "select distinct * from order_data where reference = '?'";
		$order_info = $this->database->getRow($this->database->parse($sql, $reference));
		
		if ($order_info) {
			$this->reference = $reference;
			$this->data      = unserialize($order_info['data']);
			
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function save($reference) {
		$sql   = "select * from order_data where reference = '?'";
		$order = $this->database->getRow($this->database->parse($sql, $reference));
		
		if (!$order) {
			$sql = "insert into order_data set reference = '?', data = '?', expire = '?'";
			$this->database->query($this->database->parse($sql, $reference, serialize($this->data), time() + $this->expire));
		} else {
			$sql = "update order_data set data = '?', expire = '?' where reference = '?'";
			$this->database->query($this->database->parse($sql, serialize($this->data), time() + $this->expire, $reference));
		}
	}
	
	function get_invoice_number(){
		$result = $this->database->getRow("select value from setting where `key` = 'invoice_number'");
		return $result['value'];
	}
	
	private function update_invoice_number($invoice_number){
		$next_invoice = increment_number($invoice_number);
		$this->database->query("update `setting` set value='" . $next_invoice . "' where `key` = 'invoice_number'");
	}

	function process($order_status_id = NULL) {
		if ($this->data) {
			$invoice_number = $this->get_invoice_number();
			$this->update_invoice_number($invoice_number);
			$sql = "insert into `order` set customer_id = '?', reference = '?', invoice_number = '?', firstname = '?', lastname = '?', email = '?', telephone = '?', fax = '?', taxed = '?', coupon_sort_order = '?', discount_sort_order = '?', order_status_id = '?', total = '?', currency = '?', value = '?', ip = '?', shipping_firstname = '?', shipping_lastname = '?', shipping_company = '?', shipping_address_1 = '?', shipping_address_2 = '?', shipping_city = '?', shipping_postcode = '?', shipping_zone = '?', shipping_country = '?', shipping_address_format = '?', shipping_method = '?', shipping_net = '?', shipping_tax_rate = '?', freeshipping_net = '?', payment_firstname = '?', payment_lastname = '?', payment_company = '?', payment_address_1 = '?', payment_address_2 = '?', payment_city = '?', payment_postcode = '?', payment_zone = '?', payment_country = '?', payment_address_format = '?', payment_method = '?', date_modified = now(), date_added = now()";
			$this->database->query($this->database->parse($sql, $this->data['customer_id'], $this->reference, $invoice_number, $this->data['firstname'], $this->data['lastname'], $this->data['email'], $this->data['telephone'], $this->data['fax'], $this->data['taxed'], $this->data['coupon_sort_order'], $this->data['discount_sort_order'], ($order_status_id ? $order_status_id : $this->data['order_status_id']), $this->data['total'], $this->data['currency'], $this->data['value'], $this->data['ip'], $this->data['shipping_firstname'], $this->data['shipping_lastname'], $this->data['shipping_company'], $this->data['shipping_address_1'], $this->data['shipping_address_2'], $this->data['shipping_city'], $this->data['shipping_postcode'], $this->data['shipping_zone'], $this->data['shipping_country'], $this->data['shipping_address_format'], $this->data['shipping_method'], $this->data['shipping_net'], $this->data['shipping_tax_rate'], $this->data['freeshipping_net'], $this->data['payment_firstname'], $this->data['payment_lastname'], $this->data['payment_company'], $this->data['payment_address_1'], $this->data['payment_address_2'], $this->data['payment_city'], $this->data['payment_postcode'], $this->data['payment_zone'], $this->data['payment_country'], $this->data['payment_address_format'], $this->data['payment_method']));

			$order_id = $this->database->getLastId();

			foreach ($this->data['products'] as $product) {
				$sql = "insert into order_product set order_id = '?', product_id = '?', name = '?', model_number = '?', price = '?', discount = '?', special_price = '?', coupon = '?', general_discount = '?', total = '?', tax = '?', quantity = '?', barcode = '?', shipping = '?'";
				$this->database->query($this->database->parse($sql, $order_id, $product['product_id'], $product['name'], $product['model_number'], $product['price'], $product['discount'], $product['special_price'], $product['coupon'], $product['general_discount'], $product['total'], $product['tax'], $product['quantity'], $product['barcode'], $product['shipping']));
 
				$order_product_id = $this->database->getLastId();

				foreach ($product['option'] as $option) {
					$sql = "insert into order_option set order_id = '?', order_product_id = '?', name = '?', `value` = '?', price = '?', prefix = '?'";
					$this->database->query($this->database->parse($sql, $order_id, $order_product_id, $option['name'], $option['value'], $product['price'], $option['prefix']));
				}
				
				foreach ($product['download'] as $download) {
					$sql = "insert into order_download set order_id = '?', order_product_id = '?', name = '?', filename = '?', mask = '?', remaining = '?'";
					$this->database->query($this->database->parse($sql, $order_id, $order_product_id, $download['name'], $download['filename'], $download['mask'], $download['remaining'] * $product['quantity']));
				}	
				
				if ($this->config->get('config_stock_subtract')) {
					if($product['option']){
						$this->database->query("update product_options set quantity = (quantity - " . (int)$product['quantity'] . ") where product_option = '" . $product['product_key'] . "'");
					} else {
						$this->database->query("update product set quantity = (quantity - " . (int)$product['quantity'] . ") where product_id = '" . (int)$product['product_id'] . "'");
					}
				}
			}
		
			$sql = "insert into order_history set order_id = '?', order_status_id = '?', date_added = now(), notify = '1', comment = '?'";
			$this->database->query($this->database->parse($sql, $order_id, ($order_status_id ? $order_status_id : $this->data['order_status_id']), strip_tags($this->data['comment'])));

			foreach ($this->data['totals'] as $total) {
				$sql = "insert into order_total set order_id = '?', title = '?', text = '?', `value` = '?', sort_order = '?'";
				$this->database->query($this->database->parse($sql, $order_id, $total['title'], $total['text'], $total['value'], $total['sort_order']));
			}
			
			if ($this->data['coupon_id']) {
				$this->coupon->redeem($this->data['coupon_id'], $this->data['customer_id'], $order_id);
			}
            
            $this->database->query("update customer set cart = '' where customer_id = '" . (int)$this->data['customer_id'] . "'");
			
            if ($this->session->get('skipmail') == true) { 
                $this->session->set('order_data', $this->data);
            } else {
			    if ($this->config->get('config_email_send') ) {
				    $this->mail->setTo($this->data['email']);
				    $this->mail->setFrom($this->config->get('config_email_orders') ? $this->config->get('config_email_orders') : $this->config->get('config_email'));
				    $this->mail->setSender($this->config->get('config_store'));
				    $this->mail->setSubject($this->data['email_subject']);
					$this->mail->setText(str_replace('#XXXZZZ#', $invoice_number, $this->data['email_text']));
				    $this->mail->setHtml(html_entity_decode(str_replace('#XXXZZZ#', $invoice_number, $this->data['email_html'])));
				    $this->mail->send();
					$this->mail->setTo($this->config->get('config_email_orders') ? $this->config->get('config_email_orders') : $this->config->get('config_email'));
					$this->mail->send();
			    }
			}

            $this->data = array();
            
			$sql = "delete from order_data where reference = '?'";
			$this->database->query($this->database->parse($sql, $this->reference));
		}
	}
}
?>