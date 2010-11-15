<?php //AlegroCart
class ShippingItem extends Shipping {    
	function __construct(&$locator) { 
		$this->address  =& $locator->get('address');
		$this->cart     =& $locator->get('cart');
		$this->config   =& $locator->get('config');
		$this->currency =& $locator->get('currency');
		$this->language =& $locator->get('language');
		$this->session  =& $locator->get('session');
		$this->tax      =& $locator->get('tax');
		$model 			=& $locator->get('model');
		$this->modelShipping = $model->get('model_shipping');
				
		$this->language->load('extension/shipping/item.php');
  	}
  	
  	function quote() {
		if ($this->config->get('item_status')) {
      		if (!$this->config->get('item_geo_zone_id')) {
        		$status = true;
			} elseif ($this->modelShipping->get_itemstatus()) {
        		$status = true;
      		} else {
        		$status = false;
      		}
		} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {
		
			if($this->config->get('item_max') < $this->cart->getWeight() && $this->config->get('item_max') > 0){
				$error_weight = $this->language->get('error_weight', $this->cart->formatWeight($this->config->get('item_max')));
			}
			$quote_data = array();
			
      		$quote_data['item'] = array(
        		'id'    => 'item_item',
        		'title' => $this->language->get('text_item_description'),
        		'cost'  => $this->config->get('item_cost') * $this->cart->countProducts(),
        		'text'  => $this->currency->format($this->tax->calculate($this->config->get('item_cost') * $this->cart->countProducts(), $this->config->get('item_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'id'           => 'item',
        		'title'        => $this->language->get('text_item_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('item_tax_class_id'),
				'sort_order'   => $this->config->get('item_sort_order'),
        		'error'        => isset($error_weight) ? $error_weight : false
      		);
		}
	
		return $method_data;
  	}
}
?>