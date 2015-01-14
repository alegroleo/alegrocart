<?php //AlegroCart
class ShippingFlat extends Shipping { 
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

		$this->language->load('extension/shipping/flat.php');
	}

	function quote() {
		if ($this->config->get('flat_status')) {
		if(!isset($this->flatrate)){
				if (!$this->config->get('flat_geo_zone_id')) {
					$status = true;
				} elseif ($this->modelShipping->get_flatstatus()) {
					$status = true;
				} elseif ($this->request->has('Country_id', 'post') && $this->request->has('Zone_id', 'post') && $this->config->get('config_estimate') && $this->modelShipping->get_estimated_flatstatus($this->session->get('country_id'), $this->session->get('zone_id'))) {
					$status = true;
				} elseif ($this->session->has('shipping_method') && $this->config->get('config_estimate') && $this->modelShipping->get_estimated_flatstatus($this->session->get('country_id'), $this->session->get('zone_id'))) {
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
			if(!isset($this->flatrate)){
				$this->flatrate = $this->config->get('flat_cost');
			}
			if($this->config->get('flat_max') < $this->cart->getWeight() && $this->config->get('flat_max') > 0){
				$error_weight = $this->language->get('error_weight', $this->cart->formatWeight($this->config->get('flat_max')));
			}

			$quote_data = array();
			$quote_data['flat'] = array(
				'id'		=> 'flat_flat',
				'title'		=> $this->language->get('text_flat_description'),
				'cost'		=> $this->flatrate,
				'shipping_form'	=> '',
				'text'		=> $this->currency->format($this->tax->calculate($this->flatrate, $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')))
			);

			$method_data = array(
				'id'		=> 'flat',
				'title'		=> $this->language->get('text_flat_title'),
				'quote'		=> $quote_data,
				'tax_class_id'	=> $this->config->get('flat_tax_class_id'),
				'sort_order'	=> $this->config->get('flat_sort_order'),
				'error'		=> isset($error_weight) ? $error_weight : false
			);
		}

		return $method_data;
	}
}
?>
