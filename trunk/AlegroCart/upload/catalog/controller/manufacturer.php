<?php //Manufacturer AlegroCart
class ControllerManufacturer extends Controller { 	
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelProducts = $model->get('model_products');
		$this->modelManufacturer = $model->get('model_manufacturer');
		$this->modelCategory = $model->get('model_category');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('manufacturer'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() { 
		$currency =& $this->locator->get('currency');
		$customer =& $this->locator->get('customer');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$tax      =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');	
		$head_def =& $this->locator->get('HeaderDefinition');
		require_once('library/application/string_modify.php');
		//pagination
		if(!$this->config->get('manufacturer_status')){ RETURN;}
        $session->set('manufacturer.page', $request->has('page') && ($request->gethtml('page') > 0) ? abs((int)$request->gethtml('page')) : 1);
    	$language->load('controller/manufacturer.php');
	
	   	$view = $this->locator->create('template');
		$this->template->set('title', $language->get('heading_title'));
		$view->set('heading_title', $language->get('heading_title'));
		$view->set('text_error', $language->get('text_error'));
		$view->set('button_continue', $language->get('button_continue'));
		$view->set('continue', $url->href('home'));

		$view->set('tax_included', $this->config->get('config_tax'));
		
		if ($request->has('manufacturer_id')){
			$result = $this->modelProducts->getRow_manufacturer($request->gethtml('manufacturer_id'));
			if ($result){
				$manufacturer = array(
					'name'  => $result['name'],
					'manufacturer_id' => $result['manufacturer_id']
					);
				
				$session->set('manufacturer.name', $manufacturer['name']);
				$session->set('manufacturer.manufacturer_id', $manufacturer['manufacturer_id']);
			} else {
				$session->delete('manufacturer.name');
				$session->delete('manufacturer.manufacturer_id');
			}
		}
		$view->set('manufacturer', $session->get('manufacturer.name'));
		if ($request->isPost() && $request->has('sort_order','post')){
			$session->set('manufacturer.sort_order', $request->gethtml('sort_order','post'));
		}
		if ($request->isPost() && $request->has('sort_filter','post')){
			$session->set('manufacturer.sort_filter', $request->gethtml('sort_filter','post'));
		}
		if ($request->isPost() && $request->has('page_rows','post')){
			$session->set('manufacturer.page_rows', abs((int)$request->gethtml('page_rows','post')));
		}
		if ($request->isPost() && $request->has('max_rows','post')){
			$session->set('manufacturer.max_rows', abs((int)$request->gethtml('max_rows','post')));
		}
		if ($request->isPost() && $request->has('columns', 'post')){
			$session->set('manufacturer.columns', abs((int)$request->gethtml('columns', 'post')));
		}
		if ($request->isPost() && $request->has('model', 'post')){
			$session->set('manufacturer.model', $request->gethtml('model', 'post'));
		}
		
		if ($session->get('manufacturer.manufacturer_id')) {
			if ($session->get('manufacturer.columns')){
				$columns = $session->get('manufacturer.columns');
			} else {
				$columns = $this->config->get('manufacturer_columns');
				$session->set('manufacturer.columns', $columns);
			}			
			if ($session->get('manufacturer.page_rows')){
				$page_rows = (int)$session->get('manufacturer.page_rows');
			} else {
				$page_rows = $this->config->get('manufacturer_rows') ? $this->config->get('manufacturer_rows') : $this->config->get('config_max_rows');
			}
			If ($columns > 1){
				$page_rows = (ceil($page_rows/$columns))*$columns;
			}
			$session->set('manufacturer.page_rows', $page_rows);
			if($session->get('manufacturer.max_rows')){
				$max_rows = (int)$session->get('manufacturer.max_rows');
				if ($max_rows < $page_rows && $max_rows > 0){
					$max_rows = $page_rows;
				} else if($max_rows < 0){
					$max_rows = 0;
				}
			} else {
				$max_rows = 0;
			}
			$session->set('manufacturer.max_rows', $max_rows);
			if ($session->get('manufacturer.sort_order')){
				$default_order = $session->get('manufacturer.sort_order');
			} else {
				$default_order = $language->get('entry_ascending');
				$session->set('manufacturer.sort_order', $default_order);
			}
			if ($session->get('manufacturer.sort_filter')){
				$default_filter = $session->get('manufacturer.sort_filter');
			} else {
				$default_filter = $language->get('entry_number');
				$session->set('manufacturer.sort_filter', $default_filter);
			}
			$man_id = explode('_', $session->get('manufacturer.model'));
			if ((int)end($man_id) == $session->get('manufacturer.manufacturer_id')){
				$model = substr($session->get('manufacturer.model'),0,strpos($session->get('manufacturer.model'),"_"));
			} else {
				$model = "all";
			}
			$results = $this->modelManufacturer->get_models($session->get('manufacturer.manufacturer_id'));
			if (count($results) > 1){
				$model_data = array();
				foreach($results as $result){
					$model_data[] = array(
						'model'			=> $result['model'],
						'model_value'	=> $result['model']."_".(int)$session->get('manufacturer.manufacturer_id')
					);
				}
			} else{
				$model_data = "";
			}
			$view->set('model', $model);
			$view->set('models_data', $model_data);			
			$view->set('default_max_rows', $max_rows);
			$view->set('default_page_rows', $page_rows);
			$view->set('default_order', $default_order);
			$view->set('default_filter', $default_filter);
			$view->set('display_lock', $this->config->get('manufacturer_display_lock'));
			$view->set('default_columns', $columns);
			$view->set('columns', $columns);
			if ($default_filter == $language->get('entry_number')){
				$search_filter = ' order by pd.name ';
			} else {
				$search_filter = ' order by p.price ';
			}
			If ($default_order == $language->get('entry_ascending')){
				$search_order = ' asc ';
			} else {
				$search_order = ' desc ';
			}
			if ($columns == 1){
				$image_width = $this->config->get('manufacturer_image_width');
				$image_height = $this->config->get('manufacturer_image_height');
				
			} else if ($columns <= 3){
				$image_width = $this->config->get('manufacturer_image_width') <= 175 ? $this->config->get('manufacturer_image_width') : 175;
				$image_height = $this->config->get('manufacturer_image_height') <= 175 ? $this->config->get('manufacturer_image_height') : 175;
			} else {
				$image_width = $this->config->get('manufacturer_image_width') <= 140 ? $this->config->get('manufacturer_image_width') : 140;
				$image_height = $this->config->get('manufacturer_image_height') <= 140 ? $this->config->get('manufacturer_image_height') : 140;
			}
			If($model && $model != "all"){
				$model_sql = " and pd.model like ";
				$model_filter = "'".$model."'";
			} else {
				$model_sql = "";
				$model_filter = "";
			}
			$results = $this->modelManufacturer->get_products($model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows);
			if ($results) {
				$view->set('text_results', $this->modelManufacturer->get_text_results());
        		$product_data = array();
        		foreach ($results as $result) {
					$query = array(
						'manufacturer_id'  => (int)$request->gethtml('manufacturer_id'),
						'product_id' => $result['product_id']
          			);
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
							  'discount_amount'  => $currency->format($tax->calculate($discount_amount, $result['tax_class_id'], $this->config->get('config_tax')))
							);
						}
					}  // End product Discounts	
					// Product Options
					if ($columns == 1){
						$options = $this->modelProducts->get_options($result['product_id'],$result['tax_class_id']);
						$product_options = $this->modelProducts->get_product_with_options($result['product_id'], $image_width, $image_height);
					} else {
						$options = $this->modelProducts->check_options($result['product_id']);
						$product_options = FALSE;
					} // End Product Options
					// Description
					if ($columns == 1){
						$desc = formatedstring($result['description'],6);
					} else if ($columns <= 3){
						if ($result['alt_description']){
							$desc = formatedstring($result['alt_description'],4);
						} else {
							$desc = formatedstring($result['description'],4);
						}
					} else {
						if ($result['alt_description']){
							$desc = strippedstring($result['alt_description'],108);
						} else {
							$desc = strippedstring($result['description'],108);
						}
					} // End Description
          			$product_data[] = array(
            			'name'  => $result['name'],
						'product_id'  => $result['product_id'],
						'description' => $desc,
						'stock_level' => $result['quantity'],
						'min_qty'	  => $result['min_qty'],
						'product_discounts' => $product_discounts,
            			'href'  => $url->href('product', FALSE, $query),
						'popup'     => $image->href($result['filename']),
            			'thumb' => $image->resize($result['filename'], $image_width, $image_height),
						'special_price' => $currency->format($tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'))),
            			'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'sale_start_date' => $result['sale_start_date'],
						'sale_end_date'   => $result['sale_end_date'],
						'options'         => $options,
						'model_number'    => $result['model_number'],
						'product_options' => $product_options
          			);
        		}
       			$view->set('products', $product_data);
				
				$view->set('number_columns', $this->config->get('config_columns') == 2 ? array(1,2,3,4,5) : array(1,2,3,4));
				$view->set('entry_page', $language->get('entry_page'));
 		       	$view->set('page', $session->get('manufacturer.page'));
				$view->set('onhand', $language->get('onhand'));
				$view->set('text_model_number', $language->get('text_model_number'));
      			$view->set('previous' , $language->get('previous_page'));
				$view->set('next' , $language->get('next_page'));
				$view->set('pages', $this->modelManufacturer->get_pagination());
				$query=array('manufacturer_id' => $session->get('manufacturer.manufacturer_id'));
		    	$view->set('action', $url->href('manufacturer', FALSE, $query));
				$view->set('sort_filter', $this->sort_filter());
				$view->set('sort_order', $this->sort_order());
				$view->set('first_page', $language->get('first_page'));
				$view->set('last_page', $language->get('last_page'));
				$view->set('total_pages', $this->modelManufacturer->get_pages());
				$view->set('addtocart_quantity_box', $this->config->get('addtocart_quantity_box'));
				$view->set('addtocart_quantity_max', $this->config->get('addtocart_quantity_max'));
				$view->set('options_text', $language->get('options_text'));
				$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
				$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
				$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
				$view->set('regular_price', $language->get('regular_price'));
				$view->set('sale_price', $language->get('sale_price'));	
				$view->set('text_sort_by',$language->get('text_sort_by'));
				$view->set('text_order', $language->get('text_order'));
				$view->set('text_options', $language->get('text_options'));
				$view->set('text_max_rows', $language->get('text_max_rows'));
				$view->set('text_page_rows', $language->get('text_page_rows'));
				$view->set('text_columns', $language->get('text_columns'));
				$view->set('text_model', $language->get('text_model'));
				$view->set('text_all', $language->get('text_all'));
				$view->set('entry_submit', $language->get('entry_submit'));
				$view->set('display_options', $this->config->get('manufacturer_options_status'));	
				$view->set('product_options_select', $this->config->get('manufacturer_options_select'));
				// Currency
				$currency_code = $currency->code;
				$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
				$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
				$view->set('symbols', array($symbol_left,$symbol_right,$language->get('thousand_point')));
				$view->set('price_with_options', $language->get('price_with_options'). $symbol_left);
				$view->set('symbol_right', $symbol_right);
				$view->set('symbol_left', $symbol_left);
				$view->set('decimal_point', $language->get('decimal_point'));
				$view->set('thousand_point', $language->get('thousand_point'));
				$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']);
			}
			
		$breadcrumb = array();
		$breadcrumb[] = array(
			'href'      => $url->href('home'),
			'text'      => $language->get('text_home'),
			'separator' => FALSE
		);
		
        $breadcrumb[] = array(
          	'href'      => $url->href('manufacturer', FALSE, array('manufacturer_id'  => (int)$request->gethtml('manufacturer_id'))),
          	'text'      => $session->get('manufacturer.name'),
          	'separator' => $language->get('text_separator')
        );
		$view->set('breadcrumbs', $breadcrumb);
				
		$view->set('head_def',$head_def);
		$this->template->set('head_def',$head_def);
		$view->set('addtocart',$this->config->get('manufacturer_addtocart'));
		$view->set('text_enlarge', $language->get('text_enlarge'));
		$view->set('image_display',$this->config->get('content_image_display'));
		$view->set('this_controller', 'manufacturer');
		$view->set('tpl_columns', $this->modelCore->tpl_columns);
		if ($columns > 1 && $this->config->get('manufacturer_options_status' ) && ($this->tpl_columns == 3)) {
			$this->set_options = TRUE;
		} else {
			$this->set_options = FALSE;
		}
		
		$this->template->set('content', $view->fetch('content/manufacturer.tpl'));
    	} else {
		
	  		$this->template->set('title', $language->get('text_error'));
      		$view->set('heading_title', $language->get('text_error'));
      		$view->set('text_error', $language->get('text_error'));
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
					$this->template->set($this->module->load($module));
				}
			}
		}
	}
	
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('manufacturer', 'popular');
		if(@$this->set_options && $this->tpl_columns != 2){
			$modules_extra['columnright'] = array('manufactureroptions','specials');
		} else {
			$modules_extra['columnright'] = array('specials');
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

	function sort_filter(){
		$language =& $this->locator->get('language');	
    	$language->load('controller/manufacturer.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}
	function sort_order(){
		$language =& $this->locator->get('language');
    	$language->load('controller/manufacturer.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>