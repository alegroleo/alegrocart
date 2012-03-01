<?php //AlegroCart
class ShippingWarehouse extends Shipping {    
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
		
		$this->language->load('extension/shipping/warehouse.php');
  	}
  	
  	function quote() {
		if ($this->config->get('warehouse_status')) {
      		if(!isset($this->ware_count)){
			if (!$this->config->get('warehouse_geo_zone_id')) {
        		$status = true;
			} elseif ($this->modelShipping->get_warehousestatus()) {
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
			$this->ware_count = TRUE;
			$quote_data = array();
      		$quote_data['warehouse'] = array(
        		'id'    => 'warehouse_warehouse',
        		'title' => $this->language->get('text_warehouse_description'),
        		'cost'  => $this->config->get('warehouse_handling_fee'),
				'shipping_form'=> '',
        		'text'  => $this->currency->format($this->tax->calculate($this->config->get('warehouse_handling_fee'), $this->config->get('warehouse_tax_class_id'), $this->config->get('config_tax')))
      		);

      		$method_data = array(
        		'id'           => 'warehouse',
        		'title'        => $this->language->get('text_warehouse_title'),
        		'quote'        => $quote_data,
        		'tax_class_id' => $this->config->get('warehouse_tax_class_id'),
			'sort_order'   => $this->config->get('warehouse_sort_order'),
        		'error'        => false
      		);
		}
	
		return $method_data;
  	}
}
?>