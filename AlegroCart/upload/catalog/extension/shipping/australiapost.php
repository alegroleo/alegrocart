<?php //AlegroCart
class ShippingAustraliaPost extends Shipping { 
	var $australiapost_form_complete = TRUE;
	var $max_weight = '20000';
	var $max_length = '1050';
	var $max_circumference = '1400';
	var $min_length = '150';
	var $min_width = '150';
	var $min_height = '5';
	var $error = FALSE;
	var $service_type = '';
	
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
		$model 			=& $locator->get('model');
		$this->modelShipping = $model->get('model_shippingaupost');
		
		$this->language->load('extension/shipping/australiapost.php');
  	}

	function quote() {

		if ($this->config->get('australiapost_status')) {
			if (!$this->config->get('australiapost_geo_zone_id')) {
        		$status = true;
			} elseif ($this->modelShipping->get_aupost_status()) {
        		$status = true;
      		} else {
        		$status = false;
      		}
		} else {
			$status = false;
		}

		$method_data = array();
		if ($status) {
			if($this->request->has('australiapost_service', 'post')){
				$this->service_type = $this->request->get('australiapost_service', 'post');
				if($this->service_type != $this->session->get('australiapost_service')){
					$this->session->delete('auspost_rate');
					$this->session->delete('auspost_packages');
					$this->australiapost_form_complete = FALSE;
				}
			} else if($this->session->get('australiapost_service')){
				$this->service_type = $this->session->get('australiapost_service');
			} else {
				$this->service_type = $this->config->get('australiapost_default_method');
			}
			$this->session->set('australiapost_service', $this->service_type);
			$total_rate = '';
			if($this->customer->isLogged()){
				if($this->session->get('auspost_items') != $this->cart->countProducts() || $this->session->get('auspost_weight') != $this->cart->getWeight() || $this->session->get('shipping_address_id') != $this->session->get('ap_shipping_address_id')){
					$this->session->delete('auspost_rate');
					$this->session->delete('auspost_packages');
				}
				
				if($this->session->get('auspost_rate') || $this->session->get('auspost_packages')){
					$total_rate = $this->session->get('auspost_rate');
					$package_rates = $this->session->get('auspost_packages');
				} else {
					//$this->australiapost_form_complete = FALSE;
					$products = $this->getProducts();

					if(!$this->error){
						$packages = $this->dimension->package($products, $this->max_weight, $this->max_length, FALSE, FALSE, $this->max_circumference);
						$rates = $this->getRate($packages);

						$package_rates = array();
						foreach ($rates as $key => $rate){
							$charge = explode('=',$rate[0]);
							$days = explode('=',$rate[1]);
							$err_msg = explode('=',$rate[2]);
							$total_rate += $charge[1];
							$package_rates[] = array(
								'charge'	=> $charge[1],
								'days'		=> $days[1],
								'err_msg'	=> $err_msg[1],
								'pieces'	=> $packages[$key+1]['pieces']
							);
						}
						$this->session->set('auspost_packages', $package_rates);
					}

					$this->session->set('auspost_rate', $total_rate);
					$this->session->set('auspost_items',$this->cart->countProducts());
					$this->session->set('auspost_weight',$this->cart->getWeight());
					
					$this->session->set('ap_shipping_address_id',$this->session->get('shipping_address_id'));
				}
			} else {
				$this->session->delete('auspost_rate');
				$this->session->delete('auspost_packages');
			}
		
			$quote_data = array();
      		$quote_data['australiapost'] = array(
        		'id'    => 'australiapost_australiapost',
        		'title' => $this->language->get('text_australiapost_description'). ' - ' .$this->service_type,
        		'cost'  => $total_rate,
				'shipping_form'=> $this->fields(isset($package_rates)?$package_rates:''),
        		'text'  =>  $total_rate ?$this->currency->format($this->tax->calculate($total_rate, $this->config->get('australiapost_tax_class_id'), $this->config->get('config_tax'))) : ''
      		);
      		$method_data = array(
        		'id'           => 'australiapost',
        		'title'        => $this->language->get('text_australiapost_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('australiapost_tax_class_id'),
				'sort_order'   => $this->config->get('australiapost_sort_order'),
        		'error'        => $this->error ? $this->error : false
      		);
			
		}
		if($this->session->get('shipping_method') == 'australiapost_australiapost' ){
			$this->session->set('australiapost_form_complete', $this->australiapost_form_complete);
		}
		return $method_data;
	}
	function getRate($packages){
		$rates = array();
		foreach($packages as $package){
			$qs = '';
			$qs .= 'Pickup_Postcode='.$this->config->get('australiapost_postcode').'&';
			$qs .= 'Destination_Postcode='.str_replace(" ", '',$this->address->getPostCode($this->session->get('shipping_address_id'))).'&';
			$qs .= 'Country='.$this->address->getIsoCode2($this->session->get('shipping_address_id')).'&';
			$qs .= 'Weight='.$package['weight'].'&';
			$qs .= 'Service_Type='.$this->service_type.'&';
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
					$this->error = $this->language->get('error_aus_length',
					$this->dimension->format($this->dimension->convert($this->max_length, $this->config->get('australiapost_dimension_class'), $this->config->get( 'config_dimension_1_id')),$this->config->get( 'config_dimension_1_id')));
					return FALSE;
				}
				$width = @$dimensions[1] ? round($dimensions[1]) : $this->min_width;
				$height = @$dimensions[2] ? round($dimensions[2]) : $this->min_height;
				if(($width + $height) *2 > $this->max_circumference){
					$this->error = $this->language->get('error_aus_circumference',
					$this->dimension->format($this->dimension->convert($this->max_circumference, $this->config->get('australiapost_dimension_class'), $this->config->get( 'config_dimension_1_id')),$this->config->get( 'config_dimension_1_id')));
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
	
	function fields($package_rates) {
		$services = explode(',',$this->language->get('ap_service_methods'));
		
		$output = '';
		if($package_rates){
			foreach($package_rates as $key => $rate){
				$output .= '<tr>';
				$output .= '<td class="g">';
				$output .= $this->language->get('ap_text_rate',$key+1, $rate['pieces'], $rate['err_msg'], $rate['days'], $this->currency->format($this->tax->calculate($rate['charge'], $this->config->get('australiapost'), $this->config->get('config_tax'))));
				$output .= '</td>';
				$output .= '</tr>';
			}
		}
		$output .= '<tr>';
		$output .= '<td class="g">';
		$output .= $this->language->get('ap_entry_service') . "\n";
		$output .= '<select name="australiapost_service">'  . "\n";
		foreach($services as $service){
		$output .= '<option value="' . $service . '"';
		if($this->service_type == $service){
			$output .= ' selected';
		}
		$output .= '>' . $service . '</option>' . "\n";	
		}
		$output .= '</td>';
		$output .= '</tr>';
		if($this->session->get('shipping_method') == 'australiapost_australiapost' ){
			$output .= '<input type="hidden" name="australiapost_form_complete" value="' . $this->australiapost_form_complete . '">' . "\n";
		}
		return $output; 
	}
}	
?>