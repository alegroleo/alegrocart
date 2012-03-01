<?php 
class ModuleCart extends Controller {  
	function fetch() {
		$config   =& $this->locator->get('config');
		$cart     =& $this->locator->get('cart');
		$currency =& $this->locator->get('currency');
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$tax 	  =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');
		$this->modelCore 	= $this->model->get('model_core');
		
		if ($config->get('cart_status')) {	
			$language->load('extension/module/cart.php');
		
			$view = $this->locator->create('template');

    		$view->set('heading_title', $language->get('heading_title'));

    		$view->set('text_subtotal', $language->get('text_subtotal'));
			$view->set('text_view_cart', $language->get('text_view_cart'));
			$view->set('text_products', $language->get('text_products'));
			$view->set('text_items', $language->get('text_items'));
			
			$view->set('view_cart', $url->href('cart'));

			$view->set('text_checkout', $language->get('text_checkout'));
    		$view->set('checkout', $url->href('cart'));

    		$product_data = array();

    		foreach ($cart->getProducts() as $result) {
      			$product_data[] = array(
        			'href'     => $url->href('product', false, array('product_id' => $result['product_id'])),
        			'name'     => $result['name'],
        			'quantity' => $result['quantity'],
					'total'    => $currency->format($tax->calculate($result['total'], $result['tax_class_id'], $config->get('config_tax')))
      			);
    		}
 
    		$view->set('products', $product_data);
			$view->set('item_total', $cart->countProducts());
			$view->set('product_total', count($product_data));
    		$view->set('subtotal', $currency->format($cart->getsubTotal()));

    		$view->set('text_empty', $language->get('text_empty'));
			$view->set('location', $this->modelCore->module_location['cart']); // Template Manager 
	
			return $view->fetch('module/cart.tpl');
		}
  	}
}
?>