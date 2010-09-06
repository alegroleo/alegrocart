<?php
class Coupon {
	var $data    = array();
	var $product = array();
	var $status  = false;
	
	function Coupon(&$locator) {
		$this->cart     =& $locator->get('cart');
		$this->customer =& $locator->get('customer');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		
		if ($this->session->has('coupon_id')) {
			$coupon_info = $this->database->getRow("select * from coupon c left join coupon_description cd on (c.coupon_id = cd.coupon_id) where cd.language_id = '" . (int)$this->language->getId() . "' and c.coupon_id = '" . (int)$this->session->get('coupon_id') . "' and c.date_start < now() and c.date_end > now() and c.status = '1'");
			
			if ($coupon_info) {
				$coupon_redeem = $this->database->getRow("select count(*) as total from coupon_redeem where coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");

				$this->status = ($coupon_info['uses_total'] > $coupon_redeem['total']);
				
				if ($this->status) {
					$coupon_redeem = $this->database->getRow("select count(*) as total from coupon_redeem where coupon_id = '" . (int)$coupon_info['coupon_id'] . "' and customer_id = '" . (int)$this->customer->getId() . "'");
				
					$this->status = ($coupon_info['uses_customer'] > $coupon_redeem['total']);
				}
				
				if ($this->status) {
					$this->product = $this->database->getRows("select product_id from coupon_product where coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");
					
					$this->status = $this->hasProduct();
				}
			}
			
			if ($this->status) {
				$this->data = $coupon_info;
			} else {
				$this->session->delete('coupon');
			}
		}
	}
	
	function set($code) {
		$sql         = "select * from coupon c left join coupon_description cd on (c.coupon_id = cd.coupon_id) where cd.language_id = '?' and c.code = '?' and c.date_start < now() and c.date_end > now() and c.status = '1'";
		$coupon_info = $this->database->getRow($this->database->parse($sql, $this->language->getId(), $code));

		if ($coupon_info) {
			$coupon_redeem = $this->database->getRow("select count(*) as total from coupon_redeem where coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");

			$this->status = ($coupon_info['uses_total'] > $coupon_redeem['total']);
				
			if ($this->status) {
				$coupon_redeem = $this->database->getRow("select count(*) as total from coupon_redeem where coupon_id = '" . (int)$coupon_info['coupon_id'] . "' and customer_id = '" . (int)$this->customer->getId() . "'");
			
				$this->status = ($coupon_info['uses_customer'] > $coupon_redeem['total']);
			}		

			if ($this->status) {
				$this->product = $this->database->getRows("select product_id from coupon_product where coupon_id = '" . (int)$coupon_info['coupon_id'] . "'");
									
				$this->status = $this->hasProduct();
			}
		}
		
		if ($this->status) {
			$this->session->set('coupon_id', $coupon_info['coupon_id']);
				
			$this->data = $coupon_info;
			
			return TRUE;
		} else {
			return FALSE;
		}		
	}
			
			
	function redeem($coupon_id, $customer_id, $order_id) {
		$this->database->query("insert coupon_redeem set coupon_id = '" . (int)$coupon_id . "', customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', date_added = now()");
	}

	function get_minimum() {
		return(isset($this->data['minimum_order']) ? $this->data['minimum_order'] : NULL);
	}
	
	function getId() {
		return (isset($this->data['coupon_id']) ? $this->data['coupon_id'] : NULL);
	}
		
	function getName() {
		return (isset($this->data['name']) ? $this->data['name'] : NULL);
	}
	
	function getDescription() {
		return (isset($this->data['description']) ? $this->data['description'] : NULL);
	}
		
	function getCode() {
		return (isset($this->data['code']) ? $this->data['code'] : NULL);
	}

	function getDiscount($value) {
		if ($this->data) {
			if ($this->data['prefix'] == '%') {
				return ($value * $this->data['discount'] / 100);
			} elseif ($this->data['prefix'] == '-') {
				return $this->data['discount'];
			}
		}
	}
		
	function getShipping() {
		return (isset($this->data['shipping']) ? $this->data['shipping'] : NULL);
	}
		
	function hasProduct() {
		if ($this->product) {
			$data = array();

			foreach ($this->product as $result) {
				$data[] = $result['product_id'];
			}

			foreach ($this->cart->getProducts() as $result) {
				if (in_array($result['product_id'], $data)) {
					return TRUE;
				}
			}
		} else {
			return TRUE;
		}
	}
}
?>