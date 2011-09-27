<?php //AlegroCart
class ShippingCanadaPost extends Shipping{
	var $form_required = FALSE;
	var $max_weight = '30';
	var $max_length = '200';
	var $max_circumference = '0';
	var $min_length = '10';
	var $min_width = '7';
	var $min_height = '.1';
	var $error = FALSE;
	var $weight_class = '0';
	var $dimension_class = '0';
	var $readytoship = FALSE;
	var $canadapost_package = FAlSE;
	var $cp_info = array();
	var $cp_quotes = array();
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
		$this->modelShipping = $model->get('model_shippingcapost');
		
		$this->language->load('extension/shipping/canadapost.php');
  	}
	function quote(){
		if ($this->config->get('canadapost_status')) {
			if (!$this->config->get('canadapost_geo_zone_id')) {
				$status = true;
			} elseif ($this->modelShipping->get_canpost_status()) {
        		$status = true;
      		} else {
        		$status = false;
      		}	
		} else {
			$status = false;
		}
		
		$method_data = array();
		if ($status) {
			$has_rates = '';
			
			if($this->customer->isLogged()){
				if($this->session->get('canpost_items') != $this->cart->countProducts() || $this->session->get('canpost_weight') != $this->cart->getWeight() || $this->session->get('shipping_address_id') != $this->session->get('cp_shipping_address_id') ){

					$this->session->delete('cp_info');
					$this->session->delete('cp_quotes');
					$this->form_required = TRUE;
				}
				if($this->session->get('cp_info')){
					$this->cp_info = $this->session->get('cp_info');
					$this->cp_quotes = $this->session->get('cp_quotes');
					$has_rates = TRUE;
				} else {
					$this->weight_class = $this->modelShipping->get_canpost_weight();
					$this->dimension_class =$this->modelShipping->get_canpost_dimension();
					
					$this->readytoship = $this->config->get('canadapost_readytoship');
					$this->canadapost_package = $this->config->get('canadapost_package');
					
					$products = $this->getProducts();
					if(!$this->error){
						if($this->readytoship && $this->canadapost_package){
							$minimum_dim = $this->min_length . ':' . $this->min_width . ':' . $this->min_height;
							$this->max_circumference = 300 - (($products[0]['length']*2) + ($products[0]['width']*2));
							$packages = $this->dimension->package($products, $this->max_weight, $this->max_length, FALSE, FALSE, $this->max_circumference, $minimum_dim);
							$item_data = array();
							foreach($packages as $key => $package){
								$item_data[] = array(
									'item'		=> $key,
									'quantity'	=> '1',
									'weight'	=> $package['weight'],
									'length'	=> $package['length'],
									'width'		=> $package['width'],
									'height'	=> $package['height']
								);
							}
						} else {
							$item_data = array();
							foreach($products as $product){
								$item_data[] = array(
									'item'		=> $product['product_id'],
									'quantity'	=> $product['quantity'],
									'weight'	=> $product['weight'],
									'length'	=> $product['length'],
									'width'		=> $product['width'],
									'height'	=> $product['height']
								);
							}
						}
						$has_rates = $this->getRate($item_data);
					}
					
					if($has_rates){
						$this->session->set('cp_info', $this->cp_info);
						$this->session->set('cp_quotes', $this->cp_quotes);
					}

					$this->session->set('canpost_items',$this->cart->countProducts());
					$this->session->set('canpost_weight',$this->cart->getWeight());
					$this->session->set('cp_shipping_address_id',$this->session->get('shipping_address_id')); 				}
			} else {
				$this->session->delete('cp_shipping_address_id');
				$this->session->delete('cp_info');
				$this->session->delete('cp_quotes');
			}
			if($this->error){
				$quote_error = $this->error;
			} else if(isset($this->cp_info['statusMessage']) && $this->cp_info['statusMessage'] != 'OK'){
				$quote_error = $this->cp_info['statusMessage'];
			} else {
				$quote_error = FALSE;
			}
				
			$quote_data = array();
			if(!$quote_error && @$this->cp_info['statusMessage'] == 'OK'){
				foreach($this->cp_quotes as $key => $qoute){
					$quote_data[$key] = array(
						'id'	=> 'canadapost_' . $key,
						'title'	=> $qoute['name'],
						'cost'	=> $qoute['rate'],
						'shipping_form'=> $this->fields($qoute),
						'text'  =>  $has_rates ?$this->currency->format($this->tax->calculate($qoute['rate'], $this->config->get('canadapost_tax_class_id'), $this->config->get('config_tax'))) : ''
					);
				}
				
			} else {
				$quote_data[] = array(
					'id'	=> 'canadapost',
					'title'	=> $quote_error,
					'cost'	=> '',
					'text'  => ''
				);
			}
			$method_data = array();
			if(isset($quote_data)){
				$method_data = array(
					'id'           => 'canadapost',
					'title'        => $this->language->get('text_canadapost_title'),
					'quote'        => $quote_data,
					'tax_class_id' => $this->config->get('canadapost_tax_class_id'),
					'sort_order'   => $this->config->get('canadapost_sort_order'),
					'error'        => $quote_error
				);
			}
			
			return $method_data;
		}
		
	}
	
	function fields($quote){
		$output = '';
		$output .= '<tr>';
		$output .= '<td class="g">';
		$output .= $this->language->get('text_shipping_date');
		$output .= $quote['shippingDate'];
		$output .= $this->language->get('text_delivery_date');
		$output .= $quote['deliveryDate'];
		if($quote['nextDayAM'] == 'true'){
			$output .= $this->language->get('test_nexdayam');
		}
		$output .= '</td>';
		$output .= '</tr>';
		return $output; 
	}
	
	function getRate($products){
		$this->urlInfo = parse_url($this->config->get('canadapost_ip'));
		$body = $this->requestBody($products);
		$header = $this->requestHeader(strlen($body));
		$cpResponse = '';
		$fp = @fsockopen($this->config->get('canadapost_ip'), $this->config->get('canadapost_port'), $errno, $errstr, 2);
		if ($fp){
			fputs($fp, $header . $body); 
			$res = '';
			$headerdone = false;
			while (!feof($fp)){
				$retVal = @fgets($fp,128);		
				if(strstr($retVal,'<eparcel>') && !$headerdone){
					$res .= $retVal . ';';
					$headerdone = true;
				} else if ($headerdone) {
					$retVal = trim($retVal);
					if (strstr($retVal, "<")) {
						$res .= $retVal . ';';
					}
				}
			}
			fclose($fp);
			$cpResponse = explode(";", $res);
		} else {
			$this->error = $this->language->get('error_cp_connect');
		}	
		if($cpResponse){
			$this->parseResponse($cpResponse);
		}
		if($this->cp_info){
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function parseResponse($cpResponse){
		$sequence = FALSE;
		foreach($cpResponse as $line){
			$result = array();
			$field = '';
			$value = '';
			preg_match_all('/\<(.*)\>(.*)\<(.*)\>/Uis',$line, $result ,PREG_PATTERN_ORDER);
			if (isset($result[0][0])){
				$field = $result[1][0];
				$value = $result[2][0];
			} else {
				preg_match_all('/\<(.*)\>/Uis',$line, $result ,PREG_PATTERN_ORDER);
				if (isset($result[0][0])){
					$field = $result[1][0];
				}
			}
			if(strstr($field, 'product id')){
				$product_info = explode(' ',strstr($field, 'product '));
				$id = (int)str_replace(array('id="','"'),' ',$product_info[1]);
				$sequence = (int)str_replace(array('sequence="','"'),' ',$product_info[2]);
				
			} else if($field == '/product'){
				$sequence = FALSE;
				$id = FALSE;
			}
			if($sequence && $value){
				$this->cp_quotes[$sequence][$field] = $value;
			} else if($value){
				$this->cp_info[$field] = $value;
			}
		}
	}
	
	function requestHeader($length){
		$header = "POST HTTP/1.1\n";
		$header .= 'Host: ' . $this->config->get('canadapost_ip') . "\n";
		$header .= "Content-type: application/x-www-form-urlencoded\n";
		$header .= "Content-length: " . $length . "\n";
		$header .= "Connection: close\r\n\r\n";
		
		
		return $header;
	}
	function requestBody($products){
		$language = ($this->language->getCode() == 'fr') ? 'fr' : 'en';
		$shipping_address_id = $this->session->get('shipping_address_id');
		$output = "<?xml version=\"1.0\" ?>\n";
		$output .= "<eparcel>\n";
		$output .= "<language>" . $language . "</language>\n";
		$output .= "<ratesAndServicesRequest>\n";
		$output .= "<merchantCPCID>" . $this->config->get('canadapost_merchant_id') . "</merchantCPCID>\n";
		$output .= "<fromPostalCode>" . $this->config->get('canadapost_postcode') . "</fromPostalCode>\n";
		if($this->config->get('canadapost_turnaround')){
			$output .= "<turnAroundTime>" . $this->config->get('canadapost_turnaround') . "</turnAroundTime>\n";
		}
		$output .= "<itemsPrice>" . str_replace(',', '', $this->currency->format($this->cart->getTotal(), 'CAD', FALSE, FALSE)) . "</itemsPrice>\n";
		$output .= "<lineItems>\n";
		foreach($products as $product){
			$output .= "<item>\n";
			$output .= "<quantity>" . $product['quantity'] . "</quantity>\n";
			$output .= "<weight>" . $product['weight'] . "</weight>\n";
			$output .= "<length>" . $product['length'] . "</length>\n";
			$output .= "<width>" . $product['width'] . "</width>\n";
			$output .= "<height>" . $product['height'] . "</height>\n";
			$output .= "<description>" . $product['item']  . "</description>\n";
			$output .= "</item>\n";
		}
		$output .= "</lineItems>\n";
		$output .= "<city>" . htmlspecialchars_deep($this->address->getCity($shipping_address_id)) . "</city>\n";
		$output .= "<provOrState>" . htmlspecialchars_deep($this->address->getZone($shipping_address_id)) . "</provOrState>\n";
		$output .= "<country>" . htmlspecialchars_deep($this->address->getCountry($shipping_address_id)) . "</country>\n";
		$output .= "<postalCode>" . str_replace(" ", '',$this->address->getPostCode($shipping_address_id)) . "</postalCode>\n";
		$output .= "</ratesAndServicesRequest>\n";
		$output .= "</eparcel>\n";
		return $output;
	}
	
	function getProducts(){
		$product_data = array();
		
		foreach ($this->cart->getProducts() as $product) {
			if($product['shipping']){
				if($product['dimension_id']){

					$dimensions = $this->dimension->convert_raw($product['dimension_value'], $product['dimension_id'], $this->dimension_class);
				}

				$length = @$dimensions[0] ? round($dimensions[0]) : $this->min_length;
				if($length > $this->max_length){
					$this->error = $this->language->get('error_cp_length',
					$this->dimension->format($this->dimension->convert($this->max_length, $this->dimension_class, $this->config->get( 'config_dimension_1_id')),$this->config->get( 'config_dimension_1_id')));
					return FALSE;
				}
				$width = @$dimensions[1] ? round($dimensions[1]) : $this->min_width;
				$height = @$dimensions[2] ? round($dimensions[2]) : $this->min_height;
				
				if($length +(($width + $height) * 2) > 274){
					$this->error = $this->language->get('error_cp_circumference',
					$this->dimension->format($this->dimension->convert(274, $this->dimension_class, $this->config->get( 'config_dimension_1_id')),$this->config->get( 'config_dimension_1_id')));
					return FALSE;
				}
				$weight = $this->weight->convert($product['weight'], $product['weight_class_id'], $this->weight_class);
				if($weight > $this->max_weight){
					$this->error = $this->language->get('error_cp_weight', $this->cart->formatWeight($this->max_weight));
					return FALSE;
				}

				if($this->readytoship && $this->canadapost_package){
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
				} else if($this->readytoship && !$this->canadapost_package){
					$product_ready = $this->create_readytoship($weight,$length,$width,$height,$product['quantity']);
					$product_data[] = array(
						'product_id' => $product['product_id'],
						'weight'	 => $product_ready['weight'],
						'length'	 => $length,
						'width'		 => $product_ready['width'],
						'height'	 => $product_ready['height'],
						'quantity'	 => 1
					); 
				} else {
					$product_data[] = array(
						'product_id' => $product['product_id'],
						'weight'	 => $weight,
						'length'	 => $length,
						'width'		 => $width,
						'height'	 => $height,
						'quantity'	 => $product['quantity']
					); 
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
	function create_readytoship($weight,$length,$width,$height,$quantity){
		$product_ready = array();
		
		if ($quantity > 3){
			$q_width = ceil($quantity / 3);
		} else{
			$q_width = $quantity;
		}
		$package_width = $width;
		$package_height = $height;
		$count = $quantity;
		$i = 1;
		while ($count > 0 && ($length + ($package_width*2) + ($package_height*2)) < 300){
			if ($i <= $q_width){
				$package_width += $width;
				if($i == $q_width && $count>0){
					$package_height += $height;
					$i = 1;
				}
			}
			$i ++;
			$count --;
		}
		$package_weight = $weight * $quantity;
		
		$product_ready['weight'] = ($weight * $quantity);
		$product_ready['width'] = $package_width;
		$product_ready['height'] = $package_height;

		return $product_ready;
	}
}
?>