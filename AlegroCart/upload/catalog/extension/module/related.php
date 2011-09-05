<?php  // Related Products AlegroCart
class ModuleRelated extends Controller {
	function fetch() {
		$config   =& $this->locator->get('config');
		$currency =& $this->locator->get('currency');
		$database =& $this->locator->get('database');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$tax      =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');
		$request  =& $this->locator->get('request');
		$template =& $this->locator->get('template');	
		$rand     =& $this->locator->get('randomnumber');
		$head_def =& $this->locator->get('HeaderDefinition');
		$this->modelProducts = $this->model->get('model_products');
		$this->modelCore 	= $this->model->get('model_core');
		require_once('library/application/string_modify.php');		
    	if ($config->get('related_status')) {

    	  	$language->load('extension/module/related.php');
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('heading_title'));
			$view->set('onhand', $language->get('onhand'));
			$view->set('text_model_number', $language->get('text_model_number'));
			$view->set('tax_included', $config->get('config_tax'));

            if ($config->get('related_limit') == '0') {
                $limit = '';
            } else {
				$limit = (int)$config->get('related_limit');
            }
			
			$controller = $this->modelCore->controller; // Template Manager 
			$location = $this->modelCore->module_location['related']; // Template Manager 
			$columns = $config->get('related_columns');
			if($columns == 1 && $location == 'content'){
				$image_width = $config->get('related_image_width');
				$image_height = $config->get('related_image_height');
			} else if ($columns <= 3){
				$image_width = $config->get('related_image_width') <= 175 ? $config->get('related_image_width') : 175;
				$image_height = $config->get('related_image_height') <= 175 ? $config->get('related_image_height') : 175;
			} else {
				$image_width = $config->get('related_image_width') <= 140 ? $config->get('related_image_width') : 140;
				$image_height = $config->get('related_image_height') <= 140 ? $config->get('related_image_height') : 140;
			}
			
			
           $results = $this->modelProducts->get_related((int)$request->gethtml('product_id'));
			
      		$product_data = array();			
    	  	foreach ($results as $result) {
				if ($location == 'content' && $columns == 1) {
					$desc = formatedstring($result['description'],$config->get('related_lines_single'));
					// Product Discounts
					$discounts = $this->modelProducts->get_product_discount($result['product_id']);
					$product_discounts = array();
					if ($discounts) {
						foreach($discounts as $discount){
							if($result['special_price'] >0 && date('Y-m-d') >= $result['sale_start_date'] && date('Y-m-d') <= $result['sale_end_date']){
							  $discount_amount = $result['special_price'] * ($discount['discount'] / 100);
							} else {
							  $discount_amount = $result['price'] * ($discount['discount'] / 100);
							}
							$product_discounts[] = array(
							  'discount_quantity' => $discount['quantity'],
							  'discount_percent'  => round($discount['discount']),
							  'discount_amount'  => $currency->format($tax->calculate($discount_amount, $result['tax_class_id'], $config->get('config_tax')))
							);
						}
					}  // End product Discounts	
					$options = $this->modelProducts->get_options($result['product_id'],$result['tax_class_id']);
					$product_options = $this->modelProducts->get_product_with_options($result['product_id'], $image_width, $image_height);
				} else if ($columns >3) {
					if ($result['alt_description']){
						$desc = strippedstring($result['alt_description'],$config->get('related_lines_char'));
					} else {
						$desc = strippedstring($result['description'],$config->get('related_lines_char'));
					}
					$product_discounts = '';
					$options = $this->modelProducts->check_options($result['product_id']);
					$product_options = FALSE;
				} else {
					if ($result['alt_description']){
						$desc = formatedstring($result['alt_description'],$config->get('related_lines_multi'));
					} else {
						$desc = formatedstring($result['description'],$config->get('related_lines_multi'));
					}
					$product_discounts = '';
					$options = $this->modelProducts->check_options($result['product_id']);
					$product_options = FALSE;
				} 
    	  		$product_data[] = array(
    	  			'name'  => $result['name'],
					'product_id'  => $result['product_id'],
    	  			'description'  => $desc,
					'stock_level' => $result['quantity'],
					'min_qty'	  => $result['min_qty'],
					'product_discounts' => $product_discounts,
    	  			'href'  => $url->href('product', FALSE, array('product_id' => $result['product_id'])),
					'popup'     => $image->href($result['filename']),
					'thumb' => $image->resize($result['filename'], $image_width, $image_height),
				    'special_price' => $currency->format($tax->calculate($result['special_price'], $result['tax_class_id'], $config->get('config_tax'))),
                	'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $config->get('config_tax'))),
					'sale_start_date' => $result['sale_start_date'],
					'sale_end_date'   => $result['sale_end_date'],
					'options'         => $options,
					'model_number'    => $result['model_number'],
					'product_options' => $product_options
    	  		);
    	  	}
			
			$maxrow = count($product_data)-1;
			if ($product_data) {
				if ($maxrow < $limit){
					$view->set('products', $product_data);
				} else {
					$I = 0;
					while ($I < $limit){
						$rand->uRand(0,$maxrow);
						$I ++;
					}
					$I = 0;
					$product_rand = array();
					foreach ($rand->RandomNumbers as $mykey){
						$product_rand[$I] = $product_data[$mykey];
						$I ++;			
					}
					$view->set('products', $product_rand);
				}				
			} else {
				$view->set('text_notfound', $language->get('text_notfound'));
			}
			$rand->clearrand();
			
			$currency_code = $currency->code;
			$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
			$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
			$view->set('symbols', array($symbol_left,$symbol_right,$language->get('thousand_point')));
			$view->set('price_with_options', $language->get('price_with_options'). $symbol_left);
			$view->set('symbol_right', $symbol_right);
			$view->set('symbol_left', $symbol_left);
			$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']);
			$view->set('decimal_point', $language->get('decimal_point'));
			$view->set('thousand_point', $language->get('thousand_point'));
			
			$view->set('show_stock', $config->get('config_show_stock'));
			$view->set('addtocart_quantity_box', $config->get('addtocart_quantity_box'));
			$view->set('addtocart_quantity_max', $config->get('addtocart_quantity_max'));
			$view->set('options_text', $language->get('options_text'));
			$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
			$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
			$view->set('regular_price', $language->get('regular_price'));
			$view->set('sale_price', $language->get('sale_price'));			
			$view->set('head_def',$head_def);
			$view->set('image_display', $config->get('specials_image_display'));
			$view->set('location', $location);
			$view->set('columns', $columns);
			$view->set('text_options', $language->get('text_options'));
			$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
			$template->set('head_def',$head_def);
			$view->set('add_enable',TRUE);
			$view->set('text_enlarge', $language->get('text_enlarge'));
			$view->set('this_controller', 'related');
			$view->set('addtocart',$config->get('related_addtocart'));
			
            if ($location == 'content'){
				return $view->fetch('module/module_content.tpl');
			} else {
				return $view->fetch('module/module_column.tpl');
			}
    	}
	}
}
?>