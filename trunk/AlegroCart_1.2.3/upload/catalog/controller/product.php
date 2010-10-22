<?php  // Product ALegroCart 
class ControllerProduct extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelProducts = $model->get('model_products');
		$this->modelCategory = $model->get('model_category');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('product'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() { 
		$cart     =& $this->locator->get('cart');
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$shipping =& $this->locator->get('shipping');
		$tax      =& $this->locator->get('tax');
		$weight   =& $this->locator->get('weight');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');
		require_once('library/application/string_modify.php');

    	if ($request->isPost() && $request->has('product_id', 'post')) {
      		$cart->add($request->gethtml('product_id', 'post'), ($request->gethtml('quantity', 'post') > 0) ? $request->gethtml('quantity', 'post') : 1, $request->gethtml('option', 'post'));
      		$response->redirect($url->href('cart'));
    	}

    	$language->load('controller/product.php');
		
		$product_info = $this->modelProducts->getRow_product((int)$request->gethtml('product_id'));

    	
		if ($product_info) {
			$this->modelProducts->update_viewed((int)$request->gethtml('product_id'));
      		$breadcrumb = array();
      		$breadcrumb[] = array(
        		'href'      => $url->href('home'),
        		'text'      => $language->get('text_home'),
        		'separator' => FALSE
      		);
     		if ($request->gethtml('path')) {
        		foreach (explode('_', $request->gethtml('path')) as $category_id) {
					$category_info =$this->modelCategory->getRow_category_name($category_id);
          			$breadcrumb[] = array(
						'href'      => $url->href('category', FALSE, array('path' => $category_info['path'])),
            			'text'      => $category_info['name'],
            			'separator' => $language->get('text_separator')
          			);
        		}
      		}
			if ($request->gethtml('manufacturer_id')){
				$result = $this->modelProducts->getRow_manufacturer((int)$request->gethtml('manufacturer_id'));
				if ($result){
					$breadcrumb[] = array(
						'href'      => $url->href('manufacturer', FALSE, array('manufacturer_id'  => $request->gethtml('manufacturer_id'))),
						'text'      => $result['name'],
						'separator' => $language->get('text_separator')
					);
				}
			}
      		$query = array(
			    'manufacturer_id' => $request->gethtml('manufacturer_id'),
        		'path'       => $request->gethtml('path'),
        		'product_id' => $request->gethtml('product_id')
      		);
      		$breadcrumb[] = array(
        		'href'      => $url->href('product', FALSE, $query),
        		'text'      => $product_info['name'],
        		'separator' => $language->get('text_separator')
      		);

	  		$this->template->set('title', $product_info['name']);
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $product_info['name']);
		$view->set('breadcrumbs', $breadcrumb);
      		$view->set('text_enlarge', $language->get('text_enlarge'));
      		$view->set('text_images', $language->get('text_images'));
		$view->set('text_shippable', $language->get('text_shippable'));
		$view->set('text_non_shippable', $language->get('text_non_shippable'));
      		$view->set('text_options', $language->get('text_options'));
		$view->set('text_min_qty', $language->get('text_min_qty'));
			$view->set('text_manufacturer', $language->get('text_manufacturer'));
			$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
			$view->set('text_qty_discount', $language->get('text_qty_discount'));
			$view->set('text_discount', $language->get('text_discount'));
			$view->set('text_sale_start', $language->get('text_sale_start'));
			$view->set('text_sale_end', $language->get('text_sale_end'));
			$view->set('text_date', $language->get('text_date'));
			$view->set('text_shipping_yes', $language->get('text_shipping_yes'));
			$view->set('text_shipping_no', $language->get('text_shipping_no'));
			$view->set('text_weight', $language->get('text_weight'));			
			$view->set('text_review_by', $language->get('text_review_by'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating', $language->get('text_rating'));
      		$view->set('text_error', $language->get('text_empty'));	
      		$view->set('button_reviews', $language->get('button_reviews'));
      		$view->set('button_add_to_cart', $language->get('button_add_to_cart'));
			$view->set('product_number', $language->get('product_number'));
			$view->set('quantity_available', $language->get('quantity_available'));
			$view->set('standard_price', $language->get('standard_price'));
			$view->set('tab_description', $language->get('tab_description')); 
			$view->set('tab_technical', $language->get('tab_technical'));
			$view->set('tab_images', $language->get('tab_images'));
			$view->set('tab_information', $language->get('tab_information'));
			$view->set('tab_reviews', $language->get('tab_reviews'));
			$view->set('tab_write', $language->get('tab_write'));
		
      		
      		$query = array(
        		'path'       => $request->gethtml('path'),
        		'product_id' => $request->gethtml('product_id')
      		);
     		$view->set('action', $url->href('product', FALSE, $query));

			$view->set('weight',$weight->format($weight->convert($product_info['weight'],$product_info['weight_class_id'], $this->config->get('config_weight_class_id')),$this->config->get('config_weight_class_id')));
			$view->set('shipping',$product_info['shipping']);
      		$view->set('description', formatedstring($product_info['description'],40));
			$view->set('technical', formatedstring($product_info['technical'],40));
			if (isset($product_info['alt_description'])){
			  $view->set('alt_description', formatedstring($product_info['alt_description'],4));
			}
			$view->set('onhand', $language->get('onhand')); 
			$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
			$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
			$view->set('regular_price', $language->get('regular_price'));
			$view->set('sale_price', $language->get('sale_price'));			
			$view->set('stock_level', $product_info['quantity']);
			$view->set('meta_title', $product_info['meta_title']);
			$view->set('meta_description', $product_info['meta_description']);
			$view->set('meta_keywords', $product_info['meta_keywords']);
			$view->set('product_options_select', $this->config->get('product_options_select'));
            //  Product Discounts
			$results = $this->modelProducts->get_product_discount((int)$request->gethtml('product_id'));
			$product_discounts = array();
			if ($results) {
          	  foreach($results as $result){
			    if($product_info['special_price'] >0 && date('Y-m-d') >= $product_info['sale_start_date'] && date('Y-m-d') <= $product_info['sale_end_date']){
				  $discount_amount = $product_info['special_price'] * ($result['discount'] / 100);
				} else {
				  $discount_amount = $product_info['price'] * ($result['discount'] / 100);
			    }
			  
				$product_discounts[] = array(
				  'discount_quantity' => $result['quantity'],
				  'discount_percent' => (round($result['discount']*10))/10,
				  'discount_amount'  => $currency->format($tax->calculate($discount_amount, $product_info['tax_class_id'], $this->config->get('config_tax')))
				);
			  }
			  $view->set('product_discounts',$product_discounts);
			}
			// New manufaturer
			$result = $this->modelProducts->getRow_manufacturer((int)$product_info['manufacturer_id']);
			if ($result){
			     $manufacturer = array(
					'name'  => $result['name']
			    );
				$view->set('manufacturer', $manufacturer['name']);
			}
			// Currency
			$currency_code = $currency->code;
			$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
			$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
			$view->set('symbols', array($symbol_left,$symbol_right,$language->get('thousand_point'))); // **************
			$view->set('price_with_options', $language->get('price_with_options'). $symbol_left);
			$view->set('symbol_right', $symbol_right);
			$view->set('symbol_left', $symbol_left);
			$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']);
			$view->set('decimal_point', $language->get('decimal_point'));//********
			$view->set('thousand_point', $language->get('thousand_point')); //*****************
			
      		$image_data = array(); // Additional Images
			$results = $this->modelProducts->get_additional_images((int)$request->gethtml('product_id'));
      		foreach ($results as $result) {
        		$image_data[] = array(
					'image_id' => $result['image_id'],
          			'title' => $result['title'],
          			'popup' => $image->href($result['filename']),
          			'thumb' => $image->resize($result['filename'], $this->config->get('additional_image_width'), $this->config->get('additional_image_height')),
        		);
      		}
			
			$product_data = array(
				'product_id'=> $request->gethtml('product_id'),
				'thumb'     => $image->resize($product_info['filename'], $this->config->get('product_image_width'), $this->config->get('product_image_height')),
				'name'      => $product_info['name'],
				'popup'     => $image->href($product_info['filename']),
				'min_qty'   => isset($product_info['min_qty'])?$product_info['min_qty']:1,
						'special_price' => $currency->format($tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))),

            			'price' => $currency->format($tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))),
						'sale_start_date' => $product_info['sale_start_date'],
						'sale_end_date'   => $product_info['sale_end_date'],
				'options'         => $this->modelProducts->get_options($product_info['product_id'],$product_info['tax_class_id'])
			);
			$view->set('product',$product_data);
			
			
			if ($this->config->get('review_status')) {
			  $view->set('review_status', true);
			  $view->set('text_write', $language->get('text_write'));
			  $view->set('write', $url->href('review_write', false, array('product_id' => $request->gethtml('product_id'))));
			} else {
			  $view->set('review_status', false);			  
			}
			$view->set('review_data', $this->review());
			$view->set('addtocart_quantity_box', $this->config->get('addtocart_quantity_box'));
			$view->set('addtocart_quantity_max', $this->config->get('addtocart_quantity_max'));
			$view->set('maxrow', count($this->review()));
      		$view->set('images', $image_data);
			$view->set('image_display', $this->config->get('product_image_display'));
			$view->set('head_def',$head_def);
			$view->set('product_addtocart',$this->config->get('product_addtocart')); 
			$view->set('columns', 1);
			$view->set('this_controller', 'product');	
			$this->template->set('head_def',$head_def);
			$this->has_related = $product_info['related'];

	  		$this->template->set('content', $view->fetch('content/product.tpl'));
			
    	} else {
		
      		$this->template->set('title', $language->get('text_error'));

      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('text_error'));
      		$view->set('text_error', $language->get('text_error'));
      		$view->set('button_continue', $language->get('button_continue'));
      		$view->set('continue', $url->href('home'));

	  		$this->template->set('content', $view->fetch('content/error.tpl'));

    	}
		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$response->set($this->template->fetch('layout.tpl'));	
  	}

	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					if(in_array('related',$modules[$location['location']]) && @$this->has_related){
							$this->template->set($this->module->load('related'));
						break;
					} else {
						$this->template->set($this->module->load($module));
					}	
				}
			}
		}
	}
	
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('manufacturer', 'popular');
		if(@$this->has_related){
			if($this->tpl_columns != 2){
				$modules_extra['columnright'] = array('related');
			} else {
				$modules_extra['content'] = array('related');
			}
		} else {
			if($this->tpl_columns != 2){
				$modules_extra['columnright'] = array('specials');
			} else {
				$modules_extra['content'] = array('specials');
			}
		}
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

	function review(){		
		$database =& $this->locator->get('database');		
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$url      =& $this->locator->get('url');

		$results = $this->modelProducts->get_reviews((int)$request->gethtml('product_id'));
		$review_data = array();
		foreach ($results as $result) {
        		$review_data[] = array(
          			'href'       => $url->href('review_info', FALSE, array('review_id' => $result['review_id'])),
          			'name'       => $result['name'],
          			'text'       => trim(substr(strip_tags($result['text']), 0, 1000)),
          			'rating'     => $result['rating'],
          			'out_of'     => $language->get('text_out_of', $result['rating']),
          			'author'     => $result['author'],
          			'date_added' => $language->formatDate($language->get('date_format_long'), strtotime($result['date_added']))
        		);
      		}
		return $review_data;
	}
}
?>