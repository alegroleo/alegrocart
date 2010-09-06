<?php //Category AlegroCart
class ControllerCategory extends Controller {  
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelProducts = $model->get('model_products');
		$this->modelCategory = $model->get('model_category');
		$this->modelCore 	= $model->get('model_core');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('category'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}

	function index() { 
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$tax      =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');
		$head_def   =& $this->locator->get('HeaderDefinition');
		require_once('library/application/string_modify.php'); 
		
		//pagination
		$set=$request->has('path')?'category.'.$request->gethtml('path').'.page':'category.page';
        $session->set($set, $request->has('page')?(int)$request->gethtml('page'):1);
		
		// Get Options Post Variables
		if ($request->isPost() && $request->has('sort_order','post')){
			$session->set('category.sort_order', $request->gethtml('sort_order','post'));
		}
		if ($request->isPost() && $request->has('sort_filter','post')){
			$session->set('category.sort_filter', $request->gethtml('sort_filter','post'));
		}
		if ($request->isPost() && $request->has('page_rows','post')){
			$session->set('category.page_rows', (int)$request->gethtml('page_rows','post'));
		}
		if ($request->isPost() && $request->has('max_rows','post')){
			$session->set('category.max_rows', (int)$request->gethtml('max_rows','post'));
		}
		if ($request->isPost() && $request->has('columns', 'post')){
			$session->set('category.columns', (int)$request->gethtml('columns', 'post'));
		}
		if ($request->isPost() && $request->has('manufacturer', 'post')){
			$session->set('category.manufacturer', $request->gethtml('manufacturer', 'post'));
		}
		if ($request->isPost() && $request->has('model', 'post')){
			$session->set('category.model', $request->gethtml('model', 'post'));
		}		
		// End Options Post Variables
		
		$language->load('controller/category.php');
		$category_info = $this->modelCategory->getRow_category_info($request->gethtml('path'));
		
		$view = $this->locator->create('template');

      	$view->set('button_continue', $language->get('button_continue'));
      	$view->set('continue', $url->href('home'));

		if ($category_info) {
	  		$this->template->set('title', $category_info['name']);
        	
			$view->set('heading_title', $category_info['name']);
			$view->set('meta_title', $category_info['meta_title']);
			$view->set('meta_description', $category_info['meta_description']);			
			$view->set('meta_keywords', $category_info['meta_keywords']);			

      		$breadcrumb = array();

      		$breadcrumb[] = array(
        		'href'      => $url->href('home'),
        		'text'      => $language->get('text_home'),
        		'separator' => FALSE
      		);

      		foreach (explode('_', $request->gethtml('path')) as $category_id) {
        		$result =$this->modelCategory->getRow_category_name($category_id);

        		$breadcrumb[] = array(
          			'href'      => $url->href('category', FALSE, array('path' => $result['path'])),
          			'text'      => $result['name'],
          			'separator' => $language->get('text_separator')
        		);
      		}

			$view->set('breadcrumbs', $breadcrumb);
			if($this->modelCategory->checkContent_category($request->gethtml('path'))){

        		$category_data = array();
                $results = $this->modelCategory->get_categories($request->gethtml('path'));
				
        		foreach ($results as $result) {
          			$category_data[] = array(
            			'name'  => $result['name'],
            			'href'  => $url->href('category', FALSE, array('path' => ($request->gethtml('path')) ? $request->gethtml('path').'_'.$result['category_id'] : $result['category_id'])),
                        'thumb' => (isset($result['filename']) && file_exists(DIR_IMAGE.$result['filename'])) ? $image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')) : NULL
          			);
        		}

        		$view->set('categories', $category_data);

				// Set Session Variables for options
				if ($session->get('category.columns')){
					$columns = $session->get('category.columns');
				} else {
					$columns = $this->config->get('category_columns');
					$session->set('category.columns', $columns);
				}			
				if ($session->get('category.page_rows')){
					$page_rows = (int)$session->get('category.page_rows');
				} else {
					$page_rows = $this->config->get('config_max_rows');
				}
				If ($columns > 1){
				$page_rows = (ceil($page_rows/$columns))*$columns;
				}
				$session->set('category.page_rows', $page_rows);
				if($session->get('category.max_rows')){
					$max_rows = (int)$session->get('category.max_rows');
					if ($max_rows < $page_rows && $max_rows > 0){
						$max_rows = $page_rows;
					} else if($max_rows < 0){
						$max_rows = 0;
					}
				} else {
					$max_rows = 0;
				}
				$session->set('category.max_rows', $max_rows);
			
				if ($session->get('category.sort_order')){
					$default_order = $session->get('category.sort_order');
				} else {
					$default_order = $language->get('entry_ascending');
					$session->set('category.sort_order', $default_order);
				}
				if ($session->get('category.sort_filter')){
					$default_filter = $session->get('category.sort_filter');
				} else {
					$default_filter = $language->get('entry_number');
					$session->set('category.sort_filter', $default_filter);
				}
				if ($default_filter == $language->get('entry_number')){
					$search_filter = ' order by p.sort_order, pd.name ';
				} else {
					$search_filter = ' order by p.price ';
				}
				If ($default_order == $language->get('entry_ascending')){
					$search_order = ' asc ';
				} else {
					$search_order = ' desc ';
				}
				// Manufacturer & Model Session
				$cat_id = explode('_', $request->gethtml('path'));
				$man_id = explode('_', $session->get('category.manufacturer'));
				if (end($man_id) == (int)end($cat_id)){
					$manufacturer_id = (int)substr($session->get('category.manufacturer'),0,strpos($session->get('category.manufacturer'),"_"));
					if ($manufacturer_id > 0){
						$manufacturer_filter = "'".$manufacturer_id."'";
						$manufacturer_sql = " and p.manufacturer_id = ";
						
					} else {
						$manufacturer_filter = "";
						$manufacturer_sql = "";
						$session->set('category.manufacturer', '0'."_".(int)end($cat_id));
					}

				} else {
					$session->set('category.manufacturer', '0'."_".(int)end($cat_id));
					$manufacturer_filter = "";
					$manufacturer_sql = "";
				}
				$mod_id = explode('_', $session->get('category.model'));
				if (end($mod_id) == (int)end($cat_id)){
					$model = substr($session->get('category.model'),0,strpos($session->get('category.model'),"_"));
					If($model && $model != "all"){
						$model_sql = " and pd.model like ";
						$model_filter = "'".$model."'";
					} else {
						$model_sql = "";
						$model_filter = "";
						$session->set('category.model', 'all'."_".(int)end($cat_id));
					}				
				} else {
					$session->set('category.model', 'all'."_".(int)end($cat_id));
					$model_sql = "";
					$model_filter = "";
				}
				// End Set Session Variables for options
				// Image size
				if ($columns == 1){
					$image_width = $this->config->get('category_image_width');
					$image_height = $this->config->get('category_image_height');
				} else if ($columns <= 3){
					$image_width = $this->config->get('category_image_width') <= 175 ? $this->config->get('category_image_width') : 175;
					$image_height = $this->config->get('category_image_height') <= 175 ? $this->config->get('category_image_height') : 175;
				} else {
					$image_width = $this->config->get('category_image_width') <= 140 ? $this->config->get('category_image_width') : 140;
					$image_height = $this->config->get('category_image_height') <= 140 ? $this->config->get('category_image_height') : 140;
				}    // End image
	
				$product_data = array();
        		
				$results = $this->modelCategory->get_products($manufacturer_sql,$manufacturer_filter,$model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows);

        		foreach ($results as $result) {
          			$query = array(
            			'path'       => $request->gethtml('path'),
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
					} else {
						$options = $this->modelProducts->check_options($result['product_id']);
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
						'options'         => $options						
          			);
        		}

        		$view->set('products', $product_data);

				$view->set('description', $category_info['description']);
        		$view->set('text_product', $language->get('text_product'));
        		$view->set('text_results', $this->modelCategory->get_text_results());
	      		$view->set('entry_page', $language->get('entry_page'));
				$view->set('onhand', $language->get('onhand'));
      			$view->set('previous' , $language->get('previous_page'));
				$view->set('next' , $language->get('next_page'));
				$view->set('first_page', $language->get('first_page'));
				$view->set('last_page', $language->get('last_page'));
				$view->set('action', $url->href('category', 'page', array('path' => $request->gethtml('path'))));
				$view->set('page', ($request->has('path') ? $session->get('category.'.$request->gethtml('path').'.page') : $session->get('category.page')));
				
				$view->set('pages', $this->modelCategory->get_pagination());
				$view->set('total_pages', $this->modelCategory->get_pages());
				$view->set('columns', $columns);
				$view->set('product_options_select', $this->config->get('category_options_select'));
				$view->set('addtocart_quantity_box', $this->config->get('addtocart_quantity_box'));
			$view->set('addtocart_quantity_max', $this->config->get('addtocart_quantity_max'));
				$view->set('options_text', $language->get('options_text'));
				$view->set('text_options', $language->get('text_options'));
				$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
				$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
				$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
				$view->set('regular_price', $language->get('regular_price'));
				$view->set('sale_price', $language->get('sale_price'));
				$view->set('text_price', $language->get('text_price'));
				$view->set('addtocart',$this->config->get('category_addtocart'));
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
				$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']); // End Currency				
				$view->set('head_def',$head_def);
				$this->template->set('head_def',$head_def);
				$view->set('text_enlarge', $language->get('text_enlarge'));
				$view->set('image_display', $this->config->get('content_image_display'));
				$view->set('this_controller', 'category');
				$this->template->set('content', $view->fetch('content/category.tpl'));
				
				$this->has_products = $product_data ? TRUE : FALSE;

      		} else {
        		$view->set('text_error', $language->get('text_empty'));

				$this->template->set('content', $view->fetch('content/error.tpl'));
      		}
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
		if(@$this->has_products){
			if($this->tpl_columns != 2){
				$modules_extra['columnright'] = array('categoryoptions','specials');
			} else {
				$modules_extra['column'][] = 'categoryoptions';
			}
		} else {
			if($this->tpl_columns != 2){
				$modules_extra['columnright'] = array('specials');
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
	
	function model(){
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$response =& $this->locator->get('response');
		
    	$language->load('extension/module/categoryoptions.php');
		
		$text_all = $language->get('text_all');
		$text_model = $language->get('text_model');
		$manufacturer_id = (int)substr($request->gethtml('manufacturer'),0,strpos($request->gethtml('manufacturer'),"_"));
		$model = substr($session->get('category.model'),0,strpos($session->get('category.model'),"_"));		
		$category = $session->get('category.category');
		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}
		$results = $this->modelCategory->get_model($category,$manufacturer_sql,$manufacturer_filter);		
		if (count($results) > 1){
			$models_data = array();
			foreach($results as $result){
				$models_data[] = array(
					'model'			=> $result['model'],
					'model_value'	=> $result['model']."_".$category
				);
			}
		} else {
			$models_data = "";
		}		
		if ($models_data){
			$output = '<tr><td>' . $text_model . '</td></tr>'."\n";
			$output .= '<tr><td style="width: 190px;">';
			$output .= '<select style="width: 180px;" name="model">'."\n";		
			$output .= '<option value="all">';
			$output .= $text_all . '</option>'."\n";
			foreach ($models_data as $model_data){
				$output .= '<option value="'. $model_data['model_value'].'">';
				$output .= $model_data['model'] . '</option>'."\n";
			}
			$output .= '</select><div class="divider"></div></td></tr>'."\n";
		} else {
			$output = "";
		}
		$response->set($output);
	}
}
?>