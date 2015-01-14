<?php //AlegroCart
class ShippingItem extends Shipping { 
	function __construct(&$locator) { 
		$this->address	=& $locator->get('address');
		$this->cart	=& $locator->get('cart');
		$this->config	=& $locator->get('config');
		$this->currency	=& $locator->get('currency');
		$this->language	=& $locator->get('language');
		$this->request	=& $locator->get('request');
		$this->session	=& $locator->get('session');
		$this->tax	=& $locator->get('tax');
		$model		=& $locator->get('model');
		$this->modelShipping = $model->get('model_shipping');

		$this->language->load('extension/shipping/item.php');
	}

	function quote() {
		if ($this->config->get('item_status')) {
		if(!isset($this->itemrate)){
				if (!$this->config->get('item_geo_zone_id')) {
					$status = true;
				} elseif ($this->modelShipping->get_itemstatus()) {
					$status = true;
				} elseif ($this->request->has('Country_id', 'post') && $this->request->has('Zone_id', 'post') && $this->config->get('config_estimate') && $this->modelShipping->get_estimated_itemstatus($this->session->get('country_id'), $this->session->get('zone_id'))) {
					$status = true;
				} elseif ($this->session->has('shipping_method') && $this->config->get('config_estimate') && $this->modelShipping->get_estimated_itemstatus($this->session->get('country_id'), $this->session->get('zone_id'))) {
					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = true;
			}
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {

			if($this->config->get('item_max') < $this->cart->getWeight() && $this->config->get('item_max') > 0){
				$error_weight = $this->language->get('error_weight', $this->cart->formatWeight($this->config->get('item_max')));
			}
			if(!isset($this->itemrate)){
				$this->itemrate = $this->config->get('item_cost') * $this->cart->countShippableProducts();
			}
			$quote_data = array();
			$quote_data['item'] = array(
				'id'		=> 'item_item',
				'title'		=> $this->language->get('text_item_description'),
				'cost'		=> $this->itemrate,
				'shipping_form'	=> '',
				'text'		=> $this->currency->format($this->tax->calculate($this->itemrate, $this->config->get('item_tax_class_id'), $this->config->get('config_tax')))
			);

			$method_data = array(
			'id'		=> 'item',
			'title'		=> $this->language->get('text_item_title'),
			'quote'		=> $quote_data,
			'tax_class_id'	=> $this->config->get('item_tax_class_id'),
			'sort_order'	=> $this->config->get('item_sort_order'),
			'error'		=> isset($error_weight) ? $error_weight : false
		);
		}

		return $method_data;
	}
}
?>
