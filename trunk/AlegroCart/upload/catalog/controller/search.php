<?php //Search AlegroCart
class ControllerSearch extends Controller {
		var $remaining = false;
		var $discounted = false;
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->config		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->module		=& $locator->get('module');
		$this->template		=& $locator->get('template');
		$this->modelProducts	= $model->get('model_products');
		$this->modelSearch	= $model->get('model_search');
		$this->modelCore	= $model->get('model_core');
		$this->tpl_manager	= $this->modelCore->get_tpl_manager('search'); // Template Manager
		$this->locations	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns	= $this->modelCore->get_columns();// Template Manager
	}

	function index() {
		$cart     =& $this->locator->get('cart');
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
	$session->set('search.page', $request->has('page') && ($request->gethtml('page') > 0) ? abs((int)$request->gethtml('page')) : 1);
	$language->load('controller/search.php');
	
		$this->template->set('title', $language->get('heading_title'));

	$view = $this->locator->create('template');

	$view->set('heading_title', $language->get('heading_title'));

		$view->set('tax_included', $this->config->get('config_tax'));

	$view->set('text_critea', $language->get('text_critea'));
	$view->set('text_search', $language->get('text_search'));
		$view->set('text_keywords', $language->get('text_keywords'));
		$view->set('text_error', $language->get('text_error'));

		$view->set('entry_search', $language->get('entry_search'));
	$view->set('entry_description', $language->get('entry_description'));
	$view->set('entry_page', $language->get('entry_page'));

	$view->set('button_search', $language->get('button_search'));

	$view->set('action', $url->href('search', 'search_page'));
	$view->set('location', 'content');

		// Set Session Variables for options
		if ($session->get('search.columns')){
			$columns = $session->get('search.columns');
		} else {
			$columns = $this->config->get('search_columns');
			$session->set('search.columns', $columns);
		}
		if ($session->get('search.page_rows')){
			$page_rows = (int)$session->get('search.page_rows');
		} else {
			$page_rows = $this->config->get('search_rows') ? $this->config->get('search_rows') : $this->config->get('config_max_rows');
		}
		If ($columns > 1){
			$page_rows = (ceil($page_rows/$columns))*$columns;
		}
		$session->set('search.page_rows', $page_rows);
		if($session->has('search.max_rows') && $session->get('search.max_rows')>= 0){
			$max_rows = (int)$session->get('search.max_rows');
			if ($max_rows < $page_rows && $max_rows > 0){
				$max_rows = $page_rows;
			} else if($max_rows < 0){
				$max_rows = 0;
			}
		} else {
			$max_rows = $this->config->get('search_limit');
		}
		$session->set('search.max_rows', $max_rows);
		if ($session->get('search.sort_order')){
			$default_order = $session->get('search.sort_order');
		} else {
			$default_order = $language->get('entry_ascending');
			$session->set('search.sort_order', $default_order);
		}
		if ($session->get('search.sort_filter')){
			$default_filter = $session->get('search.sort_filter');
		} else {
			$default_filter = $language->get('entry_number');
			$session->set('search.sort_filter', $default_filter);
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
		// Manufacturer Session
		$man_search = explode('*_*', $session->get('search.manufacturer'));
		if (end($man_search) == $session->get('search.search')){
			$manufacturer_id = (int)substr($session->get('search.manufacturer'),0,strpos($session->get('search.manufacturer'),"*_*"));
			if ($manufacturer_id > 0){
				$manufacturer_filter = "'".$manufacturer_id."'";
				$manufacturer_sql = " and p.manufacturer_id = ";
			} else {
				$manufacturer_filter = "";
				$manufacturer_sql = "";
				$session->set('search.manufacturer', 0 . "*_*".$session->get('search.search'));
			}
		} else {
			$manufacturer_filter = "";
			$manufacturer_sql = "";
			$session->set('search.manufacturer', 0 . "*_*".$session->get('search.search'));
		}
		// Model Session
		$model_search = explode('*_*', $session->get('search.model'));
		if (end($model_search) == $session->get('search.search')){
			$model = substr($session->get('search.model'),0,strpos($session->get('search.model'),"*_*"));
			If($model && $model != "all"){
				$model_sql = " and pd.model like ";
				$model_filter = "'".$model."'";
			} else {
				$model_sql = "";
				$model_filter = "";
				$session->set('search.model', 'all'."*_*".$session->get('search.search'));
			}
		} else {
			$model_sql = "";
			$model_filter = "";
			$session->set('search.model', 'all'."*_*".$session->get('search.search'));
		}
		$view->set('model', $session->get('search.model'));
		$view->set('manufacturer', $session->get('search.manufacturer'));
		// End Set Session Variables for options

		// Image size
		if ($columns == 1){
			$image_width = $this->config->get('search_image_width');
			$image_height = $this->config->get('search_image_height');
		} else if ($columns <= 3){
			$image_width = $this->config->get('search_image_width') <= 175 ? $this->config->get('search_image_width') : 175;
			$image_height = $this->config->get('search_image_height') <= 175 ? $this->config->get('search_image_height') : 175;
		} else {
			$image_width = $this->config->get('search_image_width') <= 140 ? $this->config->get('search_image_width') : 140;
			$image_height = $this->config->get('search_image_height') <= 140 ? $this->config->get('search_image_height') : 140;
		}    // End image

		$view->set('max_rows', $max_rows);
		$view->set('columns', $columns);
		$view->set('page_rows', $page_rows);
		$view->set('default_order', $default_order);
		$view->set('default_filter', $default_filter);

		$search = wildcardsearch($session->get('search.search'),$language);
		$view->set('search', $search);
		$description = $session->get('search.description');
		$view->set('description', $description);
		if ($description == "on"){
			$description_sql = "or pd.description like ";
			$search_description = $search;
		}else {
			$description_sql = '';
			$search_description = '';
		}

		if ($session->get('search.search')) {
			$results = $this->modelSearch->get_products($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter,$model_sql,$model_filter,$search_filter,$search_order,$page_rows,$max_rows);

			$view->set('discount_options', $this->config->get('config_discount_options')); //****
			// Currency
			$currency_code = $currency->code;
			$symbol_right = $currency->currencies[$currency_code]['symbol_right'];
			$symbol_left = $currency->currencies[$currency_code]['symbol_left'];
			$view->set('symbols', array($symbol_left,$symbol_right,$language->get('thousand_point')));
			$view->set('price_with_options', $language->get('price_with_options'));
			$view->set('symbol_right', $symbol_right);
			$view->set('symbol_left', $symbol_left);
			$view->set('decimal_point', $language->get('decimal_point'));
			$view->set('thousand_point', $language->get('thousand_point'));
			$view->set('decimal_place', $currency->currencies[$currency_code]['decimal_place']); // End Currency

			if ($results) {
				$view->set('text_results', $this->modelSearch->get_text_results());

			$product_data = array();

			foreach ($results as $result) {
					$days_remaining = ''; //***
					if($result['special_price'] >0 && date('Y-m-d') >= $result['sale_start_date'] && date('Y-m-d') <= $result['sale_end_date']){
						$this->discounted = true; // we have at least 1 price_old div
						if ($this->discounted && $result['remaining']) {
							$this->remaining = true; // we have at least 1 remaining div
						}
						$number_days = intval((strtotime($result['sale_end_date']) - time())/86400);  
						$days_remaining = $language->get(($number_days > 1 ? 'days_remaining' : 'day_remaining') , ($number_days ? $number_days : 1));
					}
					if($result['vendor_id']!='0' && $this->config->get('config_unregistered')){
						$vendor = $this->modelProducts->get_vendor($result['vendor_id']);
						$vendor_name = $vendor['name'];
					} else {
						$vendor_name = NULL;
					}
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
							  'discount_percent' => (round($discount['discount']*10))/10,
							  'discount_amount'  => number_format($tax->calculate($discount_amount, $result['tax_class_id'], $this->config->get('config_tax')),$currency->currencies[$currency_code]['decimal_place'],$language->get('decimal_point'),'')
							);
						}
					}  // End product Discounts
					// Product Options
					if ($columns == 1){
						$options = $this->modelProducts->get_options($result['product_id'],$result['tax_class_id']);
						$product_options = $this->modelProducts->get_product_with_options($result['product_id'], $image_width, $image_height);
					} else {
						$options = $this->modelProducts->get_option_names($result['product_id']);
						$product_options = FALSE;
					} // End Product Options
					// Description
					if ($columns == 1){
						$lines = $this->config->get('content_lines_single') ? $this->config->get('content_lines_single') : 6;
						$desc = formatedstring($result['description'],$lines);
					} else if ($columns <= 3){
						$lines = $this->config->get('content_lines_multi') ? $this->config->get('content_lines_multi') : $lines;
						if ($result['alt_description']){
							$desc = formatedstring($result['alt_description'],$lines);
						} else {
							$desc = formatedstring($result['description'],4);
						}
					} else {
						$lines = $this->config->get('content_lines_char') ? $this->config->get('content_lines_char') : 108;
						if ($result['alt_description']){
							$desc = strippedstring($result['alt_description'],$lines);
						} else {
							$desc = strippedstring($result['description'],$lines);
						}
					} // End Description
				$product_data[] = array(
					'name'  => $result['name'],
					'product_id'  => $result['product_id'],
					'description' => $desc,
					'stock_level' => $result['quantity'],
					'min_qty'	=> $result['min_qty'],
					'max_qty'	=> $result['max_qty'],
					'multiple'	=> $result['multiple'],
					'cart_level'		=> $cart->hasProduct($result['product_id']),
					'product_discounts' => $product_discounts,
					'href'  => $url->href('product', FALSE, array('product_id' => $result['product_id'])),
					'popup'     => $image->href($result['filename']),
					'thumb' => $image->resize($result['filename'], $image_width, $image_height),
					'special_price' => $currency->format($tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'sale_start_date' => $result['sale_start_date'],
					'sale_end_date'   => $result['sale_end_date'],
					'show_days_remaining' => $result['remaining'],
					'options'         => $options,
					'model_number'    => $result['model_number'],
					'product_options' => $product_options,
					'days_remaining'  => $days_remaining,
					'vendor_name'     => $vendor_name
				);
			}
				if($max_rows == $this->modelSearch->get_total()){
					$maximum_results = 1;
				} else {
					$maximum_results = 0;
				}
				$view->set('maximum_results', $maximum_results);
			$view->set('products', $product_data);
			$view->set('page', $session->get('search.page'));
				$view->set('onhand', $language->get('onhand'));
				$view->set('text_model_number', $language->get('text_model_number'));
				$view->set('text_soldby', $language->get('text_soldby'));
			$view->set('previous' , $language->get('previous_page'));
				$view->set('next' , $language->get('next_page'));
				$view->set('first_page', $language->get('first_page'));
				$view->set('last_page', $language->get('last_page'));

				$view->set('pages', $this->modelSearch->get_pagination());
			$view->set('total_pages', $this->modelSearch->get_pages());
				$view->set('addtocart_quantity_box', $this->config->get('addtocart_quantity_box'));
				$view->set('addtocart_quantity_max', $this->config->get('addtocart_quantity_max'));
				$view->set('product_options_select', $this->config->get('search_options_select'));
				$view->set('text_options', $language->get('text_options'));
				$view->set('text_quantity_discount', $language->get('text_quantity_discount'));
				$view->set('Add_to_Cart', $language->get('button_add_to_cart'));
				$view->set('Added_to_Cart', $language->get('button_added_to_cart'));
				$view->set('regular_price', $language->get('regular_price'));
				$view->set('sale_price', $language->get('sale_price'));
			}
		}

		$view->set('show_stock', $this->config->get('config_show_stock'));
		$view->set('show_stock_icon',$this->config->get('config_show_stock_icon'));
		if($this->config->get('config_show_stock_icon')){
			$view->set('low_stock_warning',$this->config->get('config_low_stock_warning'));
		}
		$view->set('text_stock_icon', $language->get('text_stock_icon'));
		$view->set('addtocart',$this->config->get('search_addtocart'));
		$view->set('text_inc_results', $language->get('text_inc_results'));
		$view->set('text_max_reached', $language->get('text_max_reached'));
		$view->set('entry_max_results', $language->get('entry_max_results'));
		$view->set('head_def',$head_def);
		$this->template->set('head_def',$head_def);
		$view->set('remaining', $this->remaining);
		$view->set('discounted', $this->discounted);
		$view->set('text_enlarge', $language->get('text_enlarge'));
		$view->set('image_display', $this->config->get('content_image_display'));
		$view->set('this_controller', 'search');
		$this->template->set('content', $view->fetch('content/search.tpl'));
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
		if($this->tpl_columns != 2){
			$modules_extra['columnright'] = array('searchoptions','specials');
		} else {
			$modules_extra['column'][] = 'searchoptions';
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
		require_once('library/application/string_modify.php');

	$language->load('extension/module/searchoptions.php');
		
		$text_all = $language->get('text_all');
		$text_model = $language->get('text_model');
		$search = wildcardsearch($session->get('search.search'),$language);
		$description = $session->get('search.description');
		if ($description == "on"){
			$description_sql = "or pd.description like ";
			$search_description = $search;
		}else {
			$description_sql = '';
			$search_description = '';
		}
		$manufacturer_id = (int)substr($request->gethtml('manufacturer'),0,strpos($request->gethtml('manufacturer'),"*_*"));
		$model = substr($session->get('search.model'),0,strpos($session->get('search.model'),"*_*"));
		$search = wildcardsearch($session->get('search.search'),$language);
		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}
		$results = $this->modelSearch->get_model($search,$description_sql,$search_description,$manufacturer_sql,$manufacturer_filter);
		if (count($results) > 1){
			$models_data = array();
			foreach($results as $result){
				$models_data[] = array(
					'model'		=> $result['model'],
					'model_value'	=> $result['model']."*_*".$session->get('search.search')
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

	function search_page() {
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');

		$query=array();
		if ($request->has('search', 'post')) {
			$session->set('search.search', $request->sanitize('search', 'post'));
		}
		if ($request->has('description', 'post')) {
			$session->set('search.description', "on"); 
		} else {
			$session->set('search.description', "off");
		}
		if ($request->isPost() && $request->has('sort_order','post')){
			$session->set('search.sort_order', $request->gethtml('sort_order','post'));
		}
		if ($request->isPost() && $request->has('sort_filter','post')){
			$session->set('search.sort_filter', $request->gethtml('sort_filter','post'));
		}
		if ($request->isPost() && $request->has('page_rows','post')){
			$session->set('search.page_rows', abs((int)$request->gethtml('page_rows','post')));
		}		
		if ($request->isPost() && $request->has('max_rows','post')){
			$session->set('search.max_rows', abs((int)$request->gethtml('max_rows','post')));
		}
		if ($request->isPost() && $request->has('columns', 'post')){
			$session->set('search.columns', abs((int)$request->gethtml('columns', 'post')));
		}
		if ($request->isPost() && $request->has('manufacturer', 'post')){
			$session->set('search.manufacturer', $request->gethtml('manufacturer', 'post'));
		} else {
			$session->set('search.manufacturer',  0 . "*_* ");
		}
		if ($request->isPost() && $request->has('model', 'post')){
			$session->set('search.model', $request->gethtml('model', 'post'));
		} else {
			$session->set('search.model',  "all*_* ");
		}
		$response->redirect($url->href('search', FALSE, $query));
	}
}
?>
