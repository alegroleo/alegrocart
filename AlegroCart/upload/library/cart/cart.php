<?php // AlegroCart
class Cart {
	var $data	= array();
	var $products	= array();
	var $subtotal	= 0;
	var $taxes	= array();
	var $total	= 0;
	var $stock	= TRUE;
	var $shipping	= FALSE;
	var $noshipping	= FALSE;
	var $downloads	= FALSE;
	var $minov	= TRUE;
	var $maxqty	= FALSE;

	function __construct(&$locator){
		$this->locator		=& $locator;
		$this->config		=& $locator->get('config');
		$this->controller	=& $locator->get('controller');
		$this->session		=& $locator->get('session');
		$this->database		=& $locator->get('database');
		$this->language		=& $locator->get('language');
		$this->tax		=& $locator->get('tax');
		$this->weight		=& $locator->get('weight');
		$this->currency		=& $locator->get('currency');

		if ($this->session->has('cart')) {
			$this->data = $this->session->get('cart');
		}
		$this->decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];
		$this->data_refresh();
	}
	function data_refresh(){

	if ($this->controller->controller == 'cart') {
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
	}

	foreach ($this->data as $key => $value)
		{
		$array      = explode(':', $key);
		$product_id = $array[0];
		$quantity   = $value;

		if (isset($array[1]))
			{
			$options = explode('.', $array[1]);
		} else {
			$options = array();
		} 

		$product = $this->database->getRow("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");

			if ($product) {
			$option_price = 0;
				$option_weight = 0;
			$option_data = array();

			foreach ($options as $product_to_option_id) {
			$option = $this->database->getRow("select o.name as name, o.option_id, ov.name as `value`, ov.option_value_id, p2o.price, p2o.prefix, p2o.option_weight, p2o.option_weightclass_id from product_to_option p2o left join `option` o on p2o.option_id = o.option_id left join option_value ov on p2o.option_value_id = ov.option_value_id where p2o.product_to_option_id = '" . (int)$product_to_option_id . "' and product_id = '" . (int)$product_id . "' and o.language_id = '" . (int)$this->language->getId() . "' and ov.language_id = '" . (int)$this->language->getId() . "'");

				if ($option['prefix'] == '+') {
					$option_price = $option_price + $option['price'];
				} elseif ($option['prefix'] == '-') {
					$option_price = $option_price - $option['price'];
				}
					$temp_option_weight = $this->weight->convert($option['option_weight'],$option['option_weightclass_id'],$product['weight_class_id']);
					$option_weight = $option_weight + $temp_option_weight;
				$option_data[] = array(
					'product_to_option_id' => $product_to_option_id,
					'option_id'      => $option['option_id'],
					'name'           => $option['name'],
					'option_value_id'=> $option['option_value_id'],
					'value'          => $option['value'],
					'prefix'         => $option['prefix'],
					'price'          => roundDigits($option['price'], $this->decimal_place),
					'option_weight'	 => $temp_option_weight,
					'option_weightclass_id'=> $option['option_weightclass_id']
				);
				}

				if($options){
					$product_option = $this->database->getRow("select * from product_options po left join image i on (po.image_id = i.image_id) where product_option = '" . $key . "'");
				} else {
					$product_option = array();
				}

				if($product['vendor_id']!='0' && $this->config->get('config_unregistered')){
					$vendor_name = $this->database->getRow("SELECT name FROM vendor WHERE vendor_id = '" . (int)$product['vendor_id'] . "' AND status = 1");
				} else {
					$vendor_name = NULL;
				}

				$product_discount = $this->database->getRow("select * from product_discount where product_id = '" . (int)$product['product_id'] . "' and quantity <= '" . (int)$quantity . "' order by quantity desc limit 1");

				if (!$product_discount) {   // changed to percent
				$discount_percent = 0;
				} else {
					$discount_percent = $product_discount['discount'];
				}

				$downloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 0 ' and dd.language_id = '" . (int)$this->language->getId() . "'");

				$download_data = array();

				foreach ($downloads as $download) {
				$download_data[] = array(
					'download_id' => $download['download_id'],
					'name'        => $download['name'],
					'filename'    => $download['filename'],
					'mask'        => $download['mask'],
					'remaining'   => $download['remaining']
				);
				}
				$price = roundDigits($product['price'], $this->decimal_place);
				$special_price = roundDigits($product['special_price'],$this->decimal_place);
				if ($special_price > 0 && date('Y-m-d H:i:s') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) {
				  $extended_price = roundDigits($special_price, $this->decimal_place);
				  $discount = $discount_percent > 0 ? roundDigits(($special_price + ($this->config->get('config_discount_options') ? $option_price : 0)) * ($discount_percent / 100), $this->decimal_place) : '0';
				  $special_price = roundDigits(($product['special_price'] + $option_price), $this->decimal_place);
				} else{
				  $extended_price = $price;
				  $special_price = '0';
				  $discount = $discount_percent > 0 ? roundDigits(($price + ($this->config->get('config_discount_options') ? $option_price : 0)) * ($discount_percent / 100),$this->decimal_place) : '0';
				}

			$this->products[$key] = array(
				'key'			=> $key,
				'product_id'		=> $product['product_id'],
				'name'			=> $product['name'],
				'vendor_id'		=> $product['vendor_id'],
				'vendor_name'		=> $vendor_name['name'],
				'model_number'		=> (isset($product_option['model_number']) ? @$product_option['model_number'] : @$product['model_number']),
				'shipping'		=> (isset($product_option['shipping']) ? @$product_option['shipping'] : @$product['shipping']),
				'image'			=> ((isset($product_option['filename']) && @$product_option['filename']) ? @$product_option['filename'] : $product['filename']),
				'option'		=> $option_data,
				'download'		=> $download_data,
				'quantity'		=> $quantity,
				'barcode'		=> isset($product_option['barcode']) ? @$product_option['barcode'] : @$product['barcode'],
				'min_qty'		=> $product['min_qty'],
				'max_qty'		=> $product['max_qty'],
				'multiple'		=> $product['multiple'],
				'stock'			=> ($quantity <=(isset($product_option['quantity']) ? @$product_option['quantity'] : @$product['quantity'])),
				'price'			=> roundDigits(($product['price'] + $option_price), $this->decimal_place),
				'special_price'		=> $special_price,
				'discount'		=> $discount,
				'coupon'		=> 0,
				'general_discount'	=> 0,
				'discount_percent'	=> $discount_percent,
				'total'			=> roundDigits((($extended_price + $option_price) - $discount) * $quantity, $this->decimal_place),
				'total_discounted'	=> roundDigits((($extended_price + $option_price) - $discount) * $quantity, $this->decimal_place),
				'tax_class_id'		=> $product['tax_class_id'],
				'product_tax'		=> roundDigits(((($extended_price + $option_price) - $discount) * $quantity) / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place),
				'weight'		=> $product['weight'] + $option_weight,
				'weight_class_id'	=> $product['weight_class_id'],
				'dimension_value'	=> ((isset($product_option['dimension_value']) && @$product_option['dimension_value']) ? @$product_option['dimension_value'] : $product['dimension_value']),
				'dimension_id'		=> ((isset($product_option['dimension_id']) && @$product_option['dimension_id']) ? @$product_option['dimension_id'] : $product['dimension_id'])
			);

				$this->subtotal += $this->tax->calculate((($extended_price + $option_price) - $discount) * $quantity, $product['tax_class_id'], $this->config->get('config_tax'));

				if (!isset($this->taxes[$product['tax_class_id']])) {
					$this->taxes[$product['tax_class_id']] = roundDigits(((($extended_price + $option_price) - $discount) * $quantity) / 100 * $this->tax->getRate($product['tax_class_id']),$this->decimal_place);
				} else {
					$this->taxes[$product['tax_class_id']] += roundDigits(((($extended_price + $option_price) - $discount) * $quantity) / 100 * $this->tax->getRate($product['tax_class_id']), $this->decimal_place);
				}

				$this->total += $this->tax->calculate((($extended_price + $option_price) - $discount) * $quantity, $product['tax_class_id']);
			
				if ($quantity > (isset($product_option['quantity']) ? @$product_option['quantity'] : $product['quantity'])) {
				$this->stock = FALSE;
				}

				if ($product['max_qty'] != 0) {
				$this->maxqty = TRUE;
				}
				if ($product['shipping']) {
				$this->shipping = TRUE;
				} else {
					if (!$download_data){
						$this->noshipping = TRUE;
					}
				}
				if ($download_data){
					$this->downloads = TRUE;
				}
			} else {
				$this->remove($key);
			}
	}
	}

	function add($product_id, $qty = '1', $options = array()) {
	if (!$options) {
		$key = $product_id;
	} else {
		$key = $product_id . ':' . implode('.', $options);
	}

	if (!isset($this->data[$key])) {
		$this->data[$key] = $qty;
	} else {
		$this->data[$key] += $qty;
	}
	if(!$this->session->has('customer_id') && $this->session->has('shipping_method')){ 
		$this->session->set('modified_message', TRUE);
		$this->session->delete('shipping_method');
		$this->session->set('had_shipping_method', TRUE);
	}
		$this->session->set('cart', $this->data);
	}

	function update($key, $qty) {
		if ($qty) {
			$this->data[$key] = $qty;
			$this->session->set('modified_message', TRUE);
		} else {
			$this->remove($key);
		}
		$this->session->set('cart', $this->data);
	}

	function remove($key) {
		if (isset($this->data[$key])) {
		unset($this->data[$key]);
		}
		$this->session->set('modified_message', TRUE);
		$this->session->set('cart', $this->data);
	}

	function restore($product) {
		$had = FALSE;
		foreach ($product as $key => $value) {
			if (!isset($this->data[$key])) {
				$this->data[$key] = $value;
			} else {
				$this->data[$key] += $value;
				$had = TRUE;
			}
			if (isset($this->products[$key])) {
				if ($this->products[$key]['max_qty'] != 0 && $this->data[$key] > $this->products[$key]['max_qty']) {
					$this->data[$key] = $this->products[$key]['max_qty'];
				}
				if ($this->products[$key]['multiple'] != 0) {
					if ($this->data[$key] < $this->products[$key]['multiple']) {
						$this->data[$key] = $this->products[$key]['multiple'];
					} else {
						if ($this->data[$key] % $this->products[$key]['multiple'] == 0){
							$this->data[$key] =$this->data[$key];
						} else {
							$rest = $this->data[$key] % $this->products[$key]['multiple'];
							$this->data[$key] += $this->products[$key]['multiple'] - $rest;
							if ($this->products[$key]['max_qty'] != 0 && $this->data[$key] > $this->products[$key]['max_qty']) {
								$this->data[$key] -= $this->products[$key]['multiple'];
							}
						}
					}
				}
			}
		}
		if ($had) {
			$this->session->set('merged_message', TRUE);
			$this->session->delete('shipping_method');
		}
		$this->session->set('cart', $this->data);
		}

	function clear() {
		$this->data = array();

		$this->session->set('cart', $this->data);
	}

	function getData() {
	return $this->data;
	}

	function getProducts() {
	return $this->products;
	}

	function getWeight() {
		$total = 0;

	foreach ($this->products as $product) {
			if ($product['shipping']){
				$total += $this->weight->convert($product['weight'] * $product['quantity'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
	}

		return $total;
	}

	function formatWeight($total) {
		$total = $this->weight->format($total, $this->config->get('config_weight_class_id'));
		return $total;
	}

	function getSubtotal() {  
		return $this->subtotal;   
	}

	function getNetTotal(){
		$net_total = 0;
		foreach($this->products as $product) {
			$net_total += $product['total_discounted'];
		}
		return $net_total;
	}

	function getTaxes() {
		return $this->taxes;
	}

	function decreaseTaxes($tax_class_id, $value = 0){
		$this->taxes[$tax_class_id] -= $value;
	}

	function decreaseProductTax($key, $value = 0){
		$this->products[$key]['product_tax'] -= $value;
	}

	function getTotal() {
		return $this->total;
	}

	function increaseTotal($value) {
		$this->total += $value;
	}

	function decreaseTotal($value) {
		$this->total -= $value;
	}

	function addTax($tax_class_id, $value) {
		if (!isset($this->taxes[$tax_class_id])) {
			$this->taxes[$tax_class_id] = $value;
		} else {
			$this->taxes[$tax_class_id] += $value;
		}
	}

	function countProducts() {
		$total = 0;

		foreach ($this->data as $key => $value) {
			$total += $value;
		}

	return $total;
	}

	function hasProducts() {
	return count($this->data);
	}

	function hasStock() {
	return $this->stock;
	}

	function hasShipping() {
	return $this->shipping;
	}

	function hasNoShipping() {
	return $this->noshipping;
	}

	function moreThanMinov($value) {
	if ($this->config->get('minov_status') == "1") {
				if ($value < $this->config->get('minov_value')) 
					{
					$this->minov = FALSE;
					} else {
					$this->minov = TRUE;
				}
				}
	return $this->minov;
	}

	function hasMaxQty() {
		return $this->maxqty;
	}

	function hasProduct($product_id, $options = array()) {
		if (!$options) {
			$key = $product_id;
		} else {
			$key = $product_id . ':' . implode('.', $options);
		}
		return (isset($this->data[$key]) ? $this->data[$key] : NULL);
	}

	function countShippableProducts(){
		$total = 0;
		foreach ($this->products as $product) {
			if($product['shipping']){
				$total += $product['quantity'];
			}
		}
		return $total;
	}
}
?>
