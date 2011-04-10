<?php // Cart AlegroCart
class ControllerCart extends Controller {
	var $error = array();
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->calculate 	=& $locator->get('calculate');
		$this->cart     	=& $locator->get('cart');
		$this->coupon   	=& $locator->get('coupon');
		$this->currency 	=& $locator->get('currency');
		$this->customer 	=& $locator->get('customer');
		$this->head_def 	=& $locator->get('HeaderDefinition');  
		$this->language 	=& $locator->get('language');
		$this->image    	=& $locator->get('image');
		$this->module   	=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->request  	=& $locator->get('request');
		$this->shipping 	=& $locator->get('shipping');
		$this->session  	=& $locator->get('session');
		$this->tax      	=& $locator->get('tax');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->modelCore 	= $model->get('model_core');
		require_once('library/application/string_modify.php');   
		$this->tpl_manager = $this->modelCore->get_tpl_manager('cart'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
		$this->language->load('controller/cart.php');
	}
	function index() {
	
    	if ($this->request->isPost() && !$this->request->has('currency', 'post') && !$this->request->has('module_language', 'post')) { 
      		if ($this->request->gethtml('product_id', 'post')) {
        		$this->cart->add($this->request->gethtml('product_id', 'post'), '1', $this->request->gethtml('option', 'post'));
      		}
            
      		if ($this->request->gethtml('quantity', 'post') != null && $this->request->gethtml('quantity', 'post')) {
                foreach ($this->request->gethtml('quantity', 'post') as $key => $value) {
                    $this->session->set('min_qty_error['.$key.']', '0');
                    $this->session->set('line_min_error['.$key.']', '0');
                    if ($this->request->gethtml('min_qty', 'post') != null) {
                        foreach ($this->request->gethtml('min_qty', 'post') as $k => $v) {
                            if ($k == $key) {
                                if ($value != 0) {
                                    if ($value < $v) {
                                        $value = $v;
                                        $this->session->set('min_qty_error['.$key.']', '1');
                                        $this->session->set('line_min_error['.$key.']', '1');
                                    }
                                }
                                $this->cart->update($key, (int)$value);
                            }
                        }
                    } else {
                        $this->cart->update($key, (int)$value);
                    }
                }
            }

      		if ($this->request->gethtml('remove', 'post')) {
	    		foreach (array_keys($this->request->gethtml('remove', 'post')) as $key) {
          			$this->cart->remove($key);
				}
      		}

			$this->validate();

	  		$this->response->redirect($this->url->href('cart'));
    	}

    	$this->template->set('title', $this->language->get('heading_title'));
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);    // New Header
      	$view->set('heading_title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);    // New Header	
		$view->set('tax_included', $this->config->get('config_tax'));

    	if ($this->cart->hasProducts()) {
			$this->calculate->getTotals(); //************************
			$view->set('text_subtotal', $this->language->get('text_subtotal'));
			$view->set('text_stock_ind', $this->language->get('text_stock_ind'));
			$view->set('text_min_order_value', $this->language->get('text_min_order_value'));
			$view->set('text_min_qty_ind', $this->language->get('text_min_qty_ind'));
			$view->set('text_shipping', $this->language->get('text_shipping'));
			$view->set('text_shippable', $this->language->get('text_shippable'));
			$view->set('text_non_shippable', $this->language->get('text_non_shippable'));
			$view->set('text_tax', $this->language->get('text_tax'));
			$view->set('text_tax_explantion', $this->language->get('text_tax_explantion'));
			$view->set('text_product_totals', $this->language->get('text_product_totals'));
			$view->set('text_downloadable', $this->language->get('text_downloadable'));
            
      		$view->set('column_remove', $this->language->get('column_remove'));
      		$view->set('column_image', $this->language->get('column_image'));
      		$view->set('column_name', $this->language->get('column_name'));
      		$view->set('column_quantity', $this->language->get('column_quantity'));
			$view->set('column_price', $this->language->get('column_price'));
      		$view->set('column_special', $this->language->get('column_special'));
			$view->set('column_discount_value', $this->language->get('column_discount_value'));
			$view->set('column_coupon_value', $this->language->get('column_coupon_value'));
			$view->set('column_extended', $this->language->get('column_extended'));
      		$view->set('column_total', $this->language->get('column_total'));
			$view->set('column_min_qty', $this->language->get('column_min_qty'));

      		$view->set('entry_coupon', $this->language->get('entry_coupon'));
			$view->set('button_update', $this->language->get('button_update'));
      		$view->set('button_shopping', $this->language->get('button_shopping'));
      		$view->set('button_checkout', $this->language->get('button_checkout'));
			
			
      		$view->set('error', @$this->error['message']);
			$view->set('error', ((!$this->cart->hasStock()) && ($this->config->get('config_stock_check')) ? $this->language->get('error_stock') : NULL));
      		$view->set('stock_check', $this->config->get('config_stock_check'));
			if ($this->session->has('error')) {
				$view->set('error', $this->session->get('error'));
				$this->session->delete('error');
				$view->set('message', '');
			} else {
				$view->set('message', $this->coupon->getCode() ? $this->session->get('coupon_message') : '');
			}
			$this->session->delete('coupon_message');
			
			$view->set('coupon', $this->coupon->getCode());
      		$view->set('action', $this->url->href('cart'));

      		$product_data = array();
			$subtotal = 0;
			$coupon_total = NULL;
			$discount_total = NULL;
			$extended_total = 0;
			$net_total = 0;
     		foreach ($this->cart->getProducts() as $result) {
        		$option_data = array();

        		foreach ($result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value'],
          			);
        		}
                
                // Minimum Order Verification
                $min_qty_error = '0';
                $line_min_error = '0';
                if ($result['quantity'] != 0) {
                    if ($result['quantity'] < $result['min_qty']) {
                        $result['quantity'] = $result['min_qty'];
                        $min_qty_error = '1';
                        $line_min_error = '1';
                    }
                }
                $special_price = $result['special_price'] ?$result['special_price'] - $result['discount'] : 0;
				$extended_total += $this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'));
				$coupon_total += $result['coupon'] ? $result['coupon'] : NULL;
				$discount_total += $result['general_discount'] ? $result['general_discount'] : NULL;
				$net_total += $result['total_discounted'];
				$subtotal += $result['total_discounted'] + ($this->config->get('config_tax') ? $result['product_tax'] : 0);
        		$product_data[] = array(
          			'key'           => $result['key'],
          			'name'          => $result['name'],
          			'model_number'  => $result['model_number'],
					'shipping'   	=> $result['shipping'],
					'download'      => $result['download'],
          			'thumb'         => $this->image->resize($result['image'], 40, 40),
          			'option'        => $option_data,
          			'quantity'      => $result['quantity'],
                    'min_qty'       => $result['min_qty'],
                    'min_qty_error' => ($line_min_error || $this->session->get('line_min_error['.$result['key'].']') ? '1' : '0'),
          			'stock'         => $result['stock'],
					'price'         => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'special_price' => $special_price ? $this->currency->format($this->tax->calculate($special_price, $result['tax_class_id'], $this->config->get('config_tax'))) : NULL,
          			'discount'      => ($result['discount'] ? $this->currency->format($this->tax->calculate($result['price'] - $result['discount'], $result['tax_class_id'], $this->config->get('config_tax'))) : NULL),
					'coupon'     =>  ($result['coupon'] ? '-' . $this->currency->format($result['coupon']) : NULL),
					'general_discount' => ($result['general_discount'] ? '-' . $this->currency->format($result['general_discount']) : NULL),
					'total_discounted'  => $this->currency->format($result['total_discounted'] + ($this->config->get('config_tax') ? $result['product_tax'] : 0)),
					'total'      => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'          => $this->url->href('product', FALSE, array('product_id' => $result['product_id']))
        		);
				
                if ($min_qty_error == '1' || $this->session->get('min_qty_error['.$result['key'].']')) {
                    $view->set('error', $this->language->get('error_min_qty'));
                    $this->session->set('min_qty_error['.$result['key'].']', '0');
                    $this->session->set('line_min_error['.$result['key'].']', '0');
                }
                    
      		}
			
			$discount_lprice = $this->config->get('discount_lprice');
			$discount_gprice = $this->config->get('discount_gprice');
			if ($discount_lprice != 0 && $discount_lprice > $net_total){
				$view->set('text_discount_lprice', $this->language->get('text_discount_lprice', $this->config->get('discount_lprice_percent'), $this->currency->format($discount_lprice)));
			}
			if ($discount_gprice != 0 && $discount_gprice > $net_total){
				$view->set('text_discount_gprice', $this->language->get('text_discount_gprice', $this->config->get('discount_gprice_percent'), $this->currency->format($discount_gprice)));
			}
			
			if (!$this->cart->moreThanMinov($this->cart->getNetTotal())) {
				$shortfall = 0;
				$shortfall = $this->config->get('minov_value') - $this->cart->getNetTotal();			
				$view->set('text_shortfall', $this->language->get('text_shortfall', $this->currency->format($shortfall)));
			}
			
			$view->set('columns', $this->tpl_columns);
			$view->set('coupon_sort_order', $this->config->get('coupon_sort_order'));
			$view->set('discount_sort_order', $this->config->get('discount_sort_order'));
      		$view->set('products', $product_data);
     		$view->set('subtotal', $this->currency->format($subtotal));
			
			$view->set('text_net_total', $this->language->get('text_net_total'));
			$view->set('net_total', $this->currency->format($net_total));

			$view->set('extended_total', $this->currency->format($extended_total));
			$view->set('coupon_total', $coupon_total ? '-' . $this->currency->format($coupon_total) : NULL);
			$view->set('discount_total', $discount_total ? '-' . $this->currency->format($discount_total) : NULL);
			$view->set('weight', $this->cart->formatWeight($this->cart->getWeight()));
			$view->set('minov_value', $this->currency->format($this->config->get('minov_value')));
     		$view->set('minov_status', $this->config->get('minov_status'));
			$view->set('text_cart_weight', $this->language->get('text_cart_weight'));
			$referer_page = $this->url->get_controller($this->session->get('current_page'),array('category','product', 'manufacturer' , 'search'));
      		$view->set('continue', "location='" . ($referer_page && $this->session->get('current_page') ? $this->session->get('current_page') : $this->url->href('home')) . "'");

      		$view->set('checkout', $this->url->ssl('checkout_shipping'));

	  		$this->template->set('content', $view->fetch('content/cart.tpl'));
    	} else {
      		$view->set('text_error', $this->language->get('text_error'));
      		$view->set('button_continue', $this->language->get('button_continue'));
      		$view->set('continue', $this->url->href('home'));
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
    	}

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
	  	$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
	
	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}
	
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('popular');
		$modules_extra['columnright'] = array('specials');
		return $modules_extra;
	}
	
	function set_tpl_modules(){ // Template Manager
		if($this->modelCore->tpl){
			if(isset($this->modelCore->tpl['tpl_headers'])){$this->template->set('tpl_headers',$this->modelCore->tpl['tpl_headers']);}
			if(isset($this->modelCore->tpl['tpl_extras'])){$this->template->set('tpl_extras',$this->modelCore->tpl['tpl_extras']);}
			if(isset($this->modelCore->tpl['tpl_left_columns'])){$this->template->set('tpl_left_columns',$this->modelCore->tpl['tpl_left_columns']);}
			if(isset($this->modelCore->tpl['tpl_contents'])){$this->template->set('tpl_contents',$this->modelCore->tpl['tpl_contents']);}
			if(isset($this->modelCore->tpl['tpl_right_columns'])){$this->template->set('tpl_right_columns',$this->modelCore->tpl['tpl_right_columns']);}
			if(isset($this->modelCore->tpl['tpl_footers'])){$this->template->set('tpl_footers',$this->modelCore->tpl['tpl_footers']);}
			if(isset($this->modelCore->tpl['tpl_bottom'])){$this->template->set('tpl_bottom',$this->modelCore->tpl['tpl_bottom']);}
		}
		if(isset($this->tpl_manager['tpl_color']) && $this->tpl_manager['tpl_color']){$this->template->set('template_color',$this->tpl_manager['tpl_color']);}
		$this->template->set('tpl_columns', $this->modelCore->tpl_columns);
	}
	function validate() {
		if(!$this->request->gethtml('coupon', 'post')){
			$this->coupon->set(NULL);
			$this->session->delete('coupon_message');
		} else {
			if ($this->coupon->getCode() != $this->request->gethtml('coupon', 'post')){
				$this->session->set('coupon_message', $this->language->get('text_coupon'));
			}
			if (!$this->coupon->set($this->request->gethtml('coupon', 'post'))) {
				$this->session->set('error', $this->language->get('error_coupon'));
				$this->session->delete('coupon_message');
				if (!$this->coupon->hasProduct()) {
					$this->session->set('error', $this->language->get('error_product')); 
				}
			}
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
