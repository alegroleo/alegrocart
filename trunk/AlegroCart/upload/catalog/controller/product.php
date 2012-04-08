<?php  // Product ALegroCart 
class ControllerProduct extends Controller {
	function __construct(&$locator){ 
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelProducts = $model->get('model_products');
		$this->modelCategory = $model->get('model_category');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('product'); 
		$this->locations = $this->modelCore->get_tpl_locations();
		$this->tpl_columns = $this->modelCore->get_columns();
 		$this->download 	=& $locator->get('download');
	}
	function index() { 
		$cart     =& $this->locator->get('cart');
		$currency =& $this->locator->get('currency');
		$dates    = $this->locator->get('dates');
		$this->dimension=& $this->locator->get('dimension');
		$language =& $this->locator->get('language');
		$this->image    =& $this->locator->get('image');
		$this->barcode  =& $this->locator->get('barcode'); 
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
			$view->set('text_review_by', $language->get('text_review_by'));
			$view->set('text_weight', $language->get('text_weight'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating1', $language->get('text_rating1'));
      		$view->set('text_rating2', $language->get('text_rating2'));
      		$view->set('text_rating3', $language->get('text_rating3'));
      		$view->set('text_rating4', $language->get('text_rating4'));
      		$view->set('text_error', $language->get('text_empty'));	
			$view->set('text_model_number', $language->get('text_model_number'));
			$view->set('text_downloadable', $language->get('text_downloadable'));
			$view->set('text_product_download', $language->get('text_product_download'));
			$view->set('text_product_detail', $language->get('text_product_detail'));
			$view->set('text_barcode', $language->get('text_barcode'));
			$view->set('text_stock_icon', $language->get('text_stock_icon'));
			$view->set('text_barcode_img', $language->get('text_barcode_img'));
			$view->set('text_free_downloads', $language->get('text_free_downloads'));

      		$view->set('button_reviews', $language->get('button_reviews'));
      		$view->set('button_add_to_cart', $language->get('button_add_to_cart'));
// 			$view->set('product_number', $language->get('product_number'));
			$view->set('quantity_available', $language->get('quantity_available'));
			$view->set('standard_price', $language->get('standard_price'));
			$view->set('tab_description', $language->get('tab_description')); 
			$view->set('tab_images', $language->get('tab_images'));
			$view->set('tab_information', $language->get('tab_information'));
			$view->set('tab_reviews', $language->get('tab_reviews'));
			$view->set('tab_write', $language->get('tab_write'));
			$view->set('tab_technical', $product_info['technical_name'] ? $product_info['technical_name'] : $language->get('tab_technical'));

			$view->set('tax_included', $this->config->get('config_tax'));
			$view->set('text_tax_rate', ($this->config->get('config_tax') ? $language->get('text_tax_included') : '') . $language->get('text_tax_rate')); 
			
      		$query = array(
        		'path'       => $request->gethtml('path'),
        		'product_id' => $request->gethtml('product_id')
      		);
     		$view->set('action', $url->href('product', FALSE, $query));
			
			$dimension_class = $this->modelProducts->get_dimension_class($product_info['dimension_id']);
			$dimension_value = $this->dimension->getValues($product_info['dimension_value'], $dimension_class['type_id'], $product_info['dimension_id']);
			$view->set('dimensions', $this->modelProducts->dimensionView($dimension_class, $dimension_value));
			
			$view->set('shipping',$product_info['shipping']);
      		$view->set('description', formatedstring($product_info['description'],40));
			$view->set('technical', formatedstring($product_info['technical'],40));
			
			if ($product_info['alt_description'] && $this->config->get('alternate_description')){
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
			$view->set('tax_rate', round($tax->getRate($product_info['tax_class_id'], $currency->currencies[$currency_code]['decimal_place'])) . '%');
			
			$product_weight = $weight->convert($product_info['weight'],$product_info['weight_class_id'], $this->config->get('config_weight_class_id'));
			$product_weight = number_format($product_weight, $this->config->get('config_weight_decimal'), $language->get('decimal_point'),'');
			$view->set('weight', $product_weight);
			$view->set('weight_unit', $weight->getClass($this->config->get('config_weight_class_id')));
			$view->set('weight_decimal', $this->config->get('config_weight_decimal'));
			$view->set('option_weights', $this->modelProducts->get_option_weight($product_info['product_id'], $this->config->get('config_weight_class_id')));
			
			//  Product Discounts
			$view->set('discount_options', $this->config->get('config_discount_options'));
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
				  'discount_amount'  => number_format($tax->calculate($discount_amount, $product_info['tax_class_id'], $this->config->get('config_tax')),$currency->currencies[$currency_code]['decimal_place'],$language->get('decimal_point'),'')
				);
			  }
			  $view->set('product_discounts',$product_discounts);
			}

      		$image_data = array(); // Additional Images
			$results = $this->modelProducts->get_additional_images((int)$request->gethtml('product_id'));
      		foreach ($results as $result) {
        		$image_data[] = array(
					'image_id' => $result['image_id'],
          			'title' => $result['title'],
          			'popup' => $this->image->href($result['filename']),
          			'thumb' => $this->image->resize($result['filename'], $this->config->get('additional_image_width'), $this->config->get('additional_image_height')),
        		);
      		}
			$downloads = $this->modelProducts->get_downloads($request->gethtml('product_id'));
			$view->set('downloads', $downloads);

			$this->fdownloads = $this->modelProducts->get_fdownloads($request->gethtml('product_id'));
			$view->set('fdownloads', $this->fdownloads);

			$days_remaining = ''; //***
			if($product_info['special_price'] >0 && date('Y-m-d') >= $product_info['sale_start_date'] && date('Y-m-d') <= $product_info['sale_end_date']){
			    $number_days = intval((strtotime($product_info['sale_end_date']) - time())/86400);
			    $days_remaining = $language->get(($number_days > 1 ? 'days_remaining' : 'day_remaining') , ($number_days ? $number_days : 1)); //***** 
				$view->set('sale_start',$dates->getDate($language->get('date_format_short'), strtotime($product_info['sale_start_date'])));
				$view->set('sale_end', $dates->getDate($language->get('date_format_short'), strtotime($product_info['sale_end_date'])));
			}

			$product_data = array(
				'product_id'=> $request->gethtml('product_id'),
				'thumb'     => $this->image->resize($product_info['filename'], $this->config->get('product_image_width'), $this->config->get('product_image_height')),
				'name'      => $product_info['name'],
				'model_number' => $product_info['model_number'],
				'barcode'   => $product_info['barcode'],
				'barcode_url'	=> $product_info['barcode'] ? $this->barcode->show($product_info['barcode']) : NULL,
				'popup'     => $this->image->href($product_info['filename']),
				'min_qty'   => isset($product_info['min_qty'])?$product_info['min_qty']:1,
				'special_price' => $currency->format($tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $this->config->get('config_tax'))),

            	'price' => $currency->format($tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'))),
				'sale_start_date' => $product_info['sale_start_date'],
				'sale_end_date'   => $product_info['sale_end_date'],
				'show_days_remaining' => $product_info['remaining'],
				'options'         => $this->modelProducts->get_options($product_info['product_id'],$product_info['tax_class_id']),
				'days_remaining'  => $days_remaining
			);
			$view->set('product',$product_data);
			
			if ($this->config->get('review_status')) {
			  $view->set('review_status', true);
			  $view->set('text_write', $language->get('text_write'));
			  $view->set('text_write_short', $language->get('text_write_short'));
			  $view->set('write', $url->href('review_write', false, array('product_id' => $request->gethtml('product_id'))));
			} else {
			  $view->set('review_status', false);			  
			}
			$view->set('review_data', $this->review());
			$view->set('show_stock', $this->config->get('config_show_stock'));
			$view->set('show_stock_icon',$this->config->get('config_show_stock_icon'));
			if($this->config->get('config_show_stock_icon')){
				$view->set('low_stock_warning',$this->config->get('config_low_stock_warning'));
				$view->set('stock_status_g', $this->image->href('stock_status_g.png'));
				$view->set('stock_status_o', $this->image->href('stock_status_o.png'));
				$view->set('stock_status_r', $this->image->href('stock_status_r.png'));
				$view->set('stock_status_y', $this->image->href('stock_status_y.png'));
			}
			
			$view->set('addtocart_quantity_box', $this->config->get('addtocart_quantity_box'));
			$view->set('addtocart_quantity_max', $this->config->get('addtocart_quantity_max'));
			$view->set('maxrow', count($this->review()));
      		$view->set('images', $image_data);
			$view->set('image_display', $this->config->get('product_image_display'));
			$view->set('magnifier', $this->config->get('magnifier'));
			$view->set('magnifier_width', $this->config->get('magnifier_width'));
			$view->set('magnifier_height', $this->config->get('magnifier_height'));
			$view->set('head_def',$head_def);
			$view->set('product_addtocart',$this->config->get('product_addtocart')); 
			$view->set('columns', 1);
			$view->set('product_options', $this->modelProducts->get_product_with_options($product_info['product_id'],$this->config->get('product_image_width'), $this->config->get('product_image_height')));
			$view->set('this_controller', 'product');	
			$this->template->set('head_def',$head_def);
			$this->has_related = $product_info['related'];
			$view->set('freedownload',$this->config->get('config_freedownload'));
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

	private function load_modules(){ // Template Manager
        $modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					if(in_array('related',$modules[$location['location']]) && @$this->has_related){
						if ($module != 'specials'){
							$this->template->set($this->module->load($module));
						}
					} else if($module != 'related'){
						$this->template->set($this->module->load($module));
					}
				}
			}
		}
	} 
	
	private function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
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

	private function set_tpl_modules(){ // Template Manager
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

	private function review(){		
		$database =& $this->locator->get('database');		
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$url      =& $this->locator->get('url');

		$results = $this->modelProducts->get_reviews((int)$request->gethtml('product_id'));
		$review_data = array();
		foreach ($results as $result) {
        		$review_data[] = array(
          			'href'       => $url->href('review_info', FALSE, array('review_id' => $result['review_id'])),
          			'name'       => $result['name'],
          			'text'       => trim(substr(strip_tags($result['text']), 0, 1000)),
          			'rating1'     => $result['rating1'],
				'rating2'     => $result['rating2'],
				'rating3'     => $result['rating3'],
				'rating4'     => $result['rating4'],
          			'out_of1'     => $language->get('text_out_of', $result['rating1']),
				'out_of2'     => $language->get('text_out_of', $result['rating2']),
				'out_of3'     => $language->get('text_out_of', $result['rating3']),
				'out_of4'     => $language->get('text_out_of', $result['rating4']),
          			'author'     => $result['author'],
          			'date_added' => $language->formatDate($language->get('date_format_long'), strtotime($result['date_added']))
        		);
      		}
		return $review_data;
	}

	function download() {
	
		$request  =& $this->locator->get('request');

 		$fdownload_info = $this->modelProducts->get_fdownload((int)$request->gethtml('product_id'), (int)$request->gethtml('download_id'));	
 		if ($fdownload_info) {
			$this->download->setSource(DIR_DOWNLOAD . $fdownload_info['filename']);
			$this->download->setFilename($fdownload_info['mask']);
			$this->download->output(); 			
 		} 
	}
}
?>