<?php //AlegroCart
class ShippingAustraliaPost extends Shipping { 
	var $form_required = FALSE;
	var $max_weight = '20000';
	var $max_length = '1050';
	var $max_circumference = '1400';
	var $min_length = '150';
	var $min_width = '150';
	var $min_height = '5';
	var $error = FALSE;
	var $ap_quotes = array();

	function __construct(&$locator) { 
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->customer =& $locator->get('customer');
		$this->dimension=& $locator->get('dimension');
		$this->language =& $locator->get('language');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
		$this->tax      =& $locator->get('tax');
		$this->weight   =& $locator->get('weight');
		$model 		=& $locator->get('model');
		$this->modelShipping = $model->get('model_shippingaupost');

		$this->language->load('extension/shipping/australiapost.php');
	}

	function quote() {

		if ($this->config->get('australiapost_status')) {
			if (!$this->config->get('australiapost_geo_zone_id')) {
				$status = true;
			} elseif ($this->modelShipping->get_aupost_status()) {
				$status = true;
			} elseif ($this->request->has('Country_id', 'post') && $this->request->has('Zone_id', 'post') && $this->config->get('config_estimate') && $this->modelShipping->get_estimated_aupost_status($this->session->get('country_id'), $this->session->get('zone_id'))) {
				$status = true;
			} elseif ($this->session->has('shipping_method') && $this->config->get('config_estimate') && $this->modelShipping->get_estimated_aupost_status($this->session->get('country_id'), $this->session->get('zone_id'))) {
				$status = true;
			} else {
				$status = false;
			}
		} else {
			$status = false;
		}

		$method_data = array();
		if ($status) {
			if($this->customer->isLogged() || (!$this->customer->isLogged() && $this->config->get('config_estimate'))){
				if($this->session->get('auspost_items') != $this->cart->countProducts() || $this->session->get('auspost_weight') != $this->cart->getWeight() || $this->session->get('shipping_address_id') != $this->session->get('ap_shipping_address_id') || (!$this->customer->isLogged() && $this->config->get('config_estimate'))){
					$this->session->delete('ap_quotes');
					$this->form_required = TRUE;
				}
				
				if($this->session->get('ap_quotes')){
					$this->ap_quotes = $this->session->get('ap_quotes');
				} else {
					$products = $this->getProducts();

					if(!$this->error){
						$packages = $this->dimension->package($products, $this->max_weight, $this->max_length, FALSE, FALSE, $this->max_circumference);
						$services = explode(',',$this->language->get('ap_service_methods'));

						foreach ($services as $service) {
							$rates = $this->getRate($packages, $service);
							$package_rates = array();
							foreach ($rates as $key => $rate){
								$charge = explode('=',$rate[0]);
								$days = explode('=',$rate[1]);
								$err_msg = explode('=',$rate[2]);
								$package_rates[] = array(
									'name'		=> $service,
									'charge'	=> $charge[1],
									'days'		=> $days[1],
									'err_msg'	=> $err_msg[1],
									'pieces'	=> $packages[$key+1]['pieces'],
								);
							}
						$this->ap_quotes[] =$package_rates;
						}
						$this->session->set('ap_quotes', $this->ap_quotes);
					}
					$this->session->set('auspost_items',$this->cart->countProducts());
					$this->session->set('auspost_weight',$this->cart->getWeight());
					$this->session->set('ap_shipping_address_id', $this->session->get('shipping_address_id'));
				}
			} else {
				$this->session->delete('ap_quotes');
				$this->session->delete('ap_shipping_address_id');
			}

			if($this->error){
				$quote_error = $this->error;
			} else {
				$quote_error = FALSE;
			}

			$quote_data = array();
			if(!$quote_error){
				foreach($this->ap_quotes as $key => $ap_qoute){
					$total_rate = 0;
					foreach ($ap_qoute as $ap_key => $quote) {
						$total_rate += $quote['charge'];
					}
					if ($total_rate != 0) {
						$quote_data[] = array(
							'id'    => 'australiapost_'. $key,
							'title' => $quote['name'],
							'cost'  => $total_rate,
							'shipping_form'=> $this->fields($ap_qoute),
							'text'  => $total_rate ?$this->currency->format($this->tax->calculate($total_rate, $this->config->get('australiapost_tax_class_id'), $this->config->get('config_tax'))) : ''
						);
					}
				}
			} else {
				$quote_data[] = array(
					'id'	=> 'australiapost',
					'title'	=> $quote_error,
					'cost'	=> '',
					'text'  => ''
				);
			}
			if (isset($quote_data)){
				$method_data = array(
					'id'           => 'australiapost',
					'title'        => $this->language->get('text_australiapost_title'),
					'quote'        => $quote_data,
					'tax_class_id' => $this->config->get('australiapost_tax_class_id'),
					'sort_order'   => $this->config->get('australiapost_sort_order'),
					'error'        => $quote_error
				);
			}
		}
		return $method_data;
	}

	function getRate($packages, $service){
		$rates = array();
		foreach($packages as $package){
			$qs = '';
			$qs .= 'Pickup_Postcode='.$this->config->get('australiapost_postcode').'&';
			if ($this->customer->isLogged()) {
				$qs .= 'Destination_Postcode='.str_replace(" ", '',$this->address->getPostCode($this->session->get('shipping_address_id'))).'&';
				$qs .= 'Country='.$this->address->getIsoCode2($this->session->get('shipping_address_id')).'&';
			} else {
				$qs .= 'Destination_Postcode='.str_replace(" ", '',$this->session->get('postcode')).'&';
				$qs .= 'Country='.$this->modelShipping->getIsoCode2($this->session->get('country_id')).'&';
			}
			$qs .= 'Weight='.$package['weight'].'&';
			$qs .= 'Service_Type='.$service.'&';
			$qs .= 'Length='.$package['length'].'&';
			$qs .= 'Width='.$package['width'].'&';
			$qs .= 'Height='.$package['height'].'&';
			$qs .= 'Quantity='.'1';
			$rates[] = file('http://drc.edeliver.com.au/ratecalc.asp?'.$qs);
		}
		return $rates;
	}

	function getProducts(){
		$product_data = array();

		foreach ($this->cart->getProducts() as $product) {
			if($product['shipping']){
				if($product['dimension_id']){
					$dimensions = $this->dimension->convert_raw($product['dimension_value'], $product['dimension_id'], $this->config->get('australiapost_dimension_class'));
				}

				$length = @$dimensions[0] ? round($dimensions[0]) : $this->min_length;
				if($length > $this->max_length){
					$this->error = $this->language->get('error_aus_length', $this->dimension->format($this->dimension->convert($this->max_length, $this->config->get('australiapost_dimension_class'), $this->config->get( 'config_dimension_1_id')),$this->config->get( 'config_dimension_1_id')));
					return FALSE;
				}
				$width = @$dimensions[1] ? round($dimensions[1]) : $this->min_width;
				$height = @$dimensions[2] ? round($dimensions[2]) : $this->min_height;
				if(($width + $height) *2 > $this->max_circumference){
					$this->error = $this->language->get('error_aus_circumference', $this->dimension->format($this->dimension->convert($this->max_circumference, $this->config->get('australiapost_dimension_class'), $this->config->get( 'config_dimension_1_id')),$this->config->get( 'config_dimension_1_id')));
					return FALSE;
				}
				$weight = round($this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('australiapost_weight_class')));
				if($weight > $this->max_weight){
					$this->error = $this->language->get('error_aus_weight', $this->cart->formatWeight($this->weight->convert($this->max_weight,$this->config->get('australiapost_weight_class'), $this->config->get('config_weight_class_id'))));
					return FALSE;
				}
				$i=1;
				while($i <= $product['quantity']){
					$product_data[] = array(
						'product_id' => $product['product_id'],
						'weight'	 => $weight,
						'length'	 => $length,
						'width'		 => $width,
						'height'	 => $height,
						'quantity'	 => 1
					); 
					$i ++;
				}
			}
		}
		if($product_data){
			foreach($product_data as $key => $value){
				$sort[$key] = $value['length'];
			}
			array_multisort($sort, SORT_DESC, $product_data);
		}
		return $product_data;
	}

	function fields($ap_qoute) {
		$output = '';
		if($ap_qoute){
			foreach($ap_qoute as $key => $rate){
				$output .= '<tr>';
				$output .= '<td class="x">';
				$output .= $this->language->get('ap_text_rate',$key+1, $rate['pieces'], $rate['err_msg'], $rate['days'], $this->currency->format($this->tax->calculate($rate['charge'], $this->config->get('australiapost'), $this->config->get('config_tax'))));
				$output .= '</td>';
				$output .= '</tr>';
			}
		}
		return $output; 
	}
}
?>
