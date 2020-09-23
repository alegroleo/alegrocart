<?php //Bought AlegroCart
class ControllerBought extends Controller {

	private $remaining = false;
	private $discounted = false;

	public function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cart		=& $locator->get('cart');
		$this->config		=& $locator->get('config');
		$this->config->set('config_tax', $this->config->get('config_tax_store'));
		$this->currency		=& $locator->get('currency');
		$this->customer		=& $locator->get('customer');
		$this->image		=& $locator->get('image');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->tax		=& $locator->get('tax');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->head_def		=& $this->locator->get('HeaderDefinition');
		$this->modelProducts	= $model->get('model_products');
		$this->modelBought	= $model->get('model_bought');
		$this->modelCore	= $model->get('model_core');
		$this->tpl_manager	= $this->modelCore->get_tpl_manager('bought'); // Template Manager
		$this->locations	= $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns	= $this->modelCore->get_columns();// Template Manager

		require_once('library/application/string_modify.php'); 
	}

	protected function index() {

		if(!$this->config->get('bought_status')){ 
			$this->response->redirect($this->url->ssl('account')); 
		}

		if (!$this->customer->isLogged()) {
			$this->session->set('redirect', $this->url->ssl('bought'));
			$this->response->redirect($this->url->ssl('account_login')); 
		}

		//pagination
		$this->session->set('bought.page', $this->request->has('page') && ($this->request->gethtml('page') > 0) ? abs((int)$this->request->gethtml('page')) : 1);

		// Get Options Post Variables
		if ($this->request->isPost() && $this->request->has('sort_order','post')){
			$this->session->set('bought.sort_order', $this->request->gethtml('sort_order','post'));
		}
		if ($this->request->isPost() && $this->request->has('sort_filter','post')){
			$this->session->set('bought.sort_filter', $this->request->gethtml('sort_filter','post'));
		}
		if ($this->request->isPost() && $this->request->has('page_rows','post')){
			$this->session->set('bought.page_rows', abs((int)$this->request->gethtml('page_rows','post')));
		}
		if ($this->request->isPost() && $this->request->has('max_rows','post')){
			$this->session->set('bought.max_rows', abs((int)$this->request->gethtml('max_rows','post')));
		}
		if ($this->request->isPost() && $this->request->has('columns', 'post')){
			$this->session->set('bought.columns', abs((int)$this->request->gethtml('columns', 'post')));
		}
		if ($this->request->isPost() && $this->request->has('manufacturer', 'post')){
			$this->session->set('bought.manufacturer', $this->request->gethtml('manufacturer', 'post'));
		}
		if ($this->request->isPost() && $this->request->has('model', 'post')){
			$this->session->set('bought.model', $this->request->gethtml('model', 'post'));
		}
		// End Options Post Variables

		$this->language->load('controller/bought.php');

		$view = $this->locator->create('template');

		$this->template->set('title', $this->language->get('heading_title'));
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('text_error', $this->language->get('text_error'));
		$view->set('button_continue', $this->language->get('button_continue'));
		$view->set('continue', $this->url->ssl('account'));
		$view->set('location', 'content');

		$view->set('tax_included', $this->config->get('config_tax'));

		// Set Session Variables for options
		if ($this->session->get('bought.columns')){
			$columns = $this->session->get('bought.columns');
		} else {
			$columns = $this->config->get('bought_columns');
			$this->session->set('bought.columns', $columns);
		}

		if ($this->session->get('bought.page_rows')){
			$page_rows = (int)$this->session->get('bought.page_rows');
		} else {
			$page_rows = $this->config->get('bought_rows') ? $this->config->get('bought_rows') : $this->config->get('config_max_rows');
		}
		if ($columns > 1){
			$page_rows = (ceil($page_rows/$columns))*$columns;
		}
		$this->session->set('bought.page_rows', $page_rows);

		if($this->session->get('bought.max_rows')){
			$max_rows = (int)$this->session->get('bought.max_rows');
			if ($max_rows < $page_rows && $max_rows > 0){
				$max_rows = $page_rows;
			} else if($max_rows < 0){
				$max_rows = 0;
			}
		} else {
			$max_rows = 0;
		}
		$this->session->set('bought.max_rows', $max_rows);

		if ($this->session->get('bought.sort_order')){
			$default_order = $this->session->get('bought.sort_order');
		} else {
			$default_order = $this->language->get('entry_ascending');
			$this->session->set('bought.sort_order', $default_order);
		}

		if ($this->session->get('bought.sort_filter')){
			$default_filter = $this->session->get('bought.sort_filter');
		} else {
			$default_filter = $this->language->get('entry_number');
			$this->session->set('bought.sort_filter', $default_filter);
		}

		if ($default_filter == $this->language->get('entry_number')){
			$search_filter = ' order by p.sort_order, pd.name ';
		} else {
			$search_filter = ' order by p.price ';
		}

		if ($default_order == $this->language->get('entry_ascending')){
			$search_order = ' asc ';
		} else {
			$search_order = ' desc ';
		}

		// Manufacturer & Model Session
		$customer_id = (int)$this->customer->getId();
		$man_id = explode('_', $this->session->get('bought.manufacturer'));
		if (end($man_id) == $customer_id){
			$manufacturer_id = (int)substr($this->session->get('bought.manufacturer'),0,strpos($this->session->get('bought.manufacturer'),"_"));
			if ($manufacturer_id > 0){
				$manufacturer_filter = "'".$manufacturer_id."'";
				$manufacturer_sql = " and p.manufacturer_id = ";

			} else {
				$manufacturer_filter = "";
				$manufacturer_sql = "";
				$this->session->set('bought.manufacturer', '0'."_".$customer_id);
			}
		} else {
			$this->session->set('bought.manufacturer', '0'."_".$customer_id);
			$manufacturer_filter = "";
			$manufacturer_sql = "";
		}

		$mod_id = explode('_', $this->session->get('bought.model'));
		if (end($mod_id) == $customer_id){
			$model = substr($this->session->get('bought.model'),0,strpos($this->session->get('bought.model'),"_"));
			if($model && $model != "all"){
				$model_sql = " and pd.model like ";
				$model_filter = "'".$model."'";
			} else {
				$model_sql = "";
				$model_filter = "";
				$this->session->set('bought.model', 'all'."_".$customer_id);
			}
		} else {
			$this->session->set('bought.model', 'all'."_".$customer_id);
			$model_sql = "";
			$model_filter = "";
		}
		// End Set Session Variables for options
		// Image size
		if ($columns == 1){
			$image_width = $this->config->get('bought_image_width');
			$image_height = $this->config->get('bought_image_height');
		} else if ($columns <= 3){
			$image_width = $this->config->get('bought_image_width') <= 175 ? $this->config->get('bought_image_width') : 175;
			$image_height = $this->config->get('bought_image_height') <= 175 ? $this->config->get('bought_image_height') : 175;
		} else {
			$image_width = $this->config->get('bought_image_width') <= 140 ? $this->config->get('bought_image_width') : 140;
			$image_height = $this->config->get('bought_image_height') <= 140 ? $this->config->get('bought_image_height') : 140;
		}    // End image

		$manufacturer_id = (int)substr($this->session->get('bought.manufacturer'),0,strpos($this->session->get('bought.manufacturer'),"_"));
		$model = substr($this->session->get('bought.model'),0,strpos($this->session->get('bought.model'),"_"));

		$customer_id = (int)$this->customer->getId();
		$man_results = $this->modelBought->get_manufacturer($customer_id);
		$manufacturers_data = array();
		if (count($man_results) > 1){
			foreach ($man_results as $man_result){
				$result = $this->modelProducts->getRow_manufacturer($man_result['manufacturer_id']);
				$manufacturers_data[] = array(
					'manufacturer'	=> $result['manufacturer_id']."_".$customer_id,
					'name'		=> $result['name']
				);
			}
		}

		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}
		$results = $this->modelBought->get_model($customer_id,$manufacturer_sql,$manufacturer_filter);
		$model_data = array();
		if (count($results) > 1){
			foreach($results as $result){
				$model_data[] = array(
					'model'		=> $result['model'],
					'model_value'	=> $result['model']."_".$customer_id
				);
			}
		}

		$view->set('models_data', $model_data);
		$view->set('model', $model);
		$view->set('manufacturers_data', $manufacturers_data);
		$view->set('default_max_rows', (int)$this->session->get('bought.max_rows'));
		$view->set('default_page_rows', (int)$this->session->get('bought.page_rows'));

		$view->set('discount_options', $this->config->get('config_discount_options')); //****
		// Currency
		$currency_code = $this->currency->code;
		$symbol_right = $this->currency->currencies[$currency_code]['symbol_right'];
		$symbol_left = $this->currency->currencies[$currency_code]['symbol_left'];
		$view->set('symbols', array($symbol_left,$symbol_right,$this->language->get('thousand_point')));
		$view->set('price_with_options', $this->language->get('price_with_options'));
		$view->set('symbol_right', $symbol_right);
		$view->set('symbol_left', $symbol_left);
		$view->set('decimal_point', $this->language->get('decimal_point'));
		$view->set('thousand_point', $this->language->get('thousand_point'));
		$view->set('decimal_place', $this->currency->currencies[$currency_code]['decimal_place']); // End Currency

		// get every product that the customer has ever bought
		$all_results = $this->modelBought->get_all_products($customer_id);

		$already_bought_existing = array(); // to store already bought existing products, remove duplicates
		$already_bought_existing_ids = array(); //to store uniqe order_product_ids for further processing
		$already_bought_deleted = array(); // to store deleted products separately
		$already_bought_deleted_ids = array(); //to store uniqe order_product_ids for further processing

		if ($all_results) {
			foreach ($all_results as $all_result) {
				$option_data = $all_result['orderedoptions'] ? $all_result['orderedoptions'] : $all_result['product_id'];
				if (!in_array($option_data, $already_bought_existing) && !in_array($option_data, $already_bought_deleted)) {
					if ($all_result['notdeleted']){
						$already_bought_existing[] = $option_data;
						$already_bought_existing_ids[] = $all_result['order_product_id'];
					} else {
						$already_bought_deleted[]=$option_data;
						$already_bought_deleted_ids[] = $all_result['order_product_id'];
					}
				}
			}
		}

		$results = $this->modelBought->get_products($already_bought_existing_ids, $already_bought_deleted_ids, $customer_id, $manufacturer_sql, $manufacturer_filter, $model_sql, $model_filter, $search_filter, $search_order, $page_rows, $max_rows);

		if ($results) {
			$product_data = array();

			foreach ($results as $result) {

				if ($this->config->get('bought_ratings')) {
					$averageRating = number_format($this->modelProducts->getAverageRating($result['product_id']), 0);
					$alt_rating = $this->language->get('text_out_of', $averageRating);
				} else {
					$averageRating = NULL;
					$alt_rating = NULL;
				}
					$days_remaining = ''; //***
					if(isset($result['special_price']) && $result['special_price'] >0 && date('Y-m-d') >= $result['sale_start_date'] && date('Y-m-d') <= $result['sale_end_date']){
						$this->discounted = true; // we have at least 1 price_old div
						if ($this->discounted && $result['remaining']) {
							$this->remaining = true; // we have at least 1 remaining div
						}
						$number_days = intval((strtotime($result['sale_end_date']) - time())/86400);
						$days_remaining = $this->language->get(($number_days > 1 ? 'days_remaining' : 'day_remaining') , ($number_days ? $number_days : 1));
					}
					if($result['vendor_id']!='0' && $this->config->get('config_unregistered')){
						$vendor = $this->modelProducts->get_vendor($result['vendor_id']);
						$vendor_name = $vendor['name'];
					} else {
						$vendor_name = NULL;
					}

					$query = array(
						'path'       => $this->request->gethtml('path'),
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
							  'discount_percent' => (round($discount['discount']*10))/10,
							  'discount_amount'  => number_format($this->tax->calculate($discount_amount, $result['tax_class_id'], $this->config->get('config_tax')),$this->currency->currencies[$currency_code]['decimal_place'],$this->language->get('decimal_point'),'')
							);
						}
					}  // End product Discounts
					// Product Options
					if ($columns == 1 && isset($result['tax_class_id'])){
						$options = $this->modelProducts->get_options($result['product_id'],$result['tax_class_id']);
						$product_options = $this->modelProducts->get_product_with_options($result['product_id'], $image_width, $image_height);
					} else {
						$options = $this->modelProducts->get_option_names($result['product_id']);
						$product_options = FALSE;
					} // End Product Options
					//Bought products options
						$bought_options = $this->modelBought->get_bought_options($result['order_product_id']);
					//End bought products options
					// Description
					if ($columns == 1){
						$lines = $this->config->get('content_lines_single') ? $this->config->get('content_lines_single') : 6;
						$desc = isset($result['description']) ? formatedstring($result['description'],$lines) : '';
					} else if ($columns <= 3){
						$lines = $this->config->get('content_lines_multi') ? $this->config->get('content_lines_multi') : 4;
						if (isset($result['alt_description'])){
							$desc = formatedstring($result['alt_description'],$lines);
						} else {
							$desc = isset($result['description']) ? formatedstring($result['description'],$lines) : '';
						}
					} else {
						$lines = $this->config->get('content_lines_char') ? $this->config->get('content_lines_char') : 108;
						if (isset($result['alt_description'])){
							$desc = strippedstring($result['alt_description'],$lines);
						} else {
							$desc = isset($result['description']) ? strippedstring($result['description'],$lines) : '';
						}
					} // End Description

				$product_data[] = array(
					'name'  => $bought_options ? $result['name'].' <br>('.$bought_options.')': $result['name'],
					'product_id'  => $result['product_id'],
					'description' => $desc,
					'stock_level' => isset($result['quantity']) ? $result['quantity'] : '',
					'min_qty'	=> isset($result['min_qty']) ? $result['min_qty'] : '',
					'max_qty'	=> isset($result['max_qty']) ? $result['max_qty'] : '',
					'multiple'	=> isset($result['multiple']) ? $result['multiple'] : '',
					'cart_level'		=> $this->cart->hasProduct($result['product_id']),
					'product_discounts' => $product_discounts,
					'href'  => $this->url->ssl('product', FALSE, $query),
					'popup'     => isset($result['filename']) ? $this->image->href($result['filename']) : '',
					'thumb' => isset($result['filename']) ? $this->image->resize($result['filename'], $image_width, $image_height) : $this->image->resize('no_image.png', $image_width, $image_height),
					'image_width' => $image_width, 
					'image_height' => $image_height, 
					'special_price' => isset($result['special_price']) ? $this->currency->format($this->tax->calculate($result['special_price'], $result['tax_class_id'], $this->config->get('config_tax'))) : '',
					'price' => isset($result['price']) ? $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))) : '',
					'sale_start_date' => isset($result['sale_start_date']) ? $result['sale_start_date'] : '',
					'sale_end_date'   => isset($result['sale_end_date']) ? $result['sale_end_date'] : '',
					'show_days_remaining' => isset($result['remaining']) ? $result['remaining'] : '',
					'options'         => $options,
					'model_number'    => $result['model_number'],
					'product_options' => $product_options,
					'days_remaining'  => $days_remaining,
					'average_rating'	=> $averageRating,
					'alt_rating'		=> $alt_rating,
					'vendor_name'		=> $vendor_name,
					'status'		=> isset($result['status']) ? $result['status'] : NULL
				);
			}

				$view->set('products', $product_data);
				$view->set('text_model_number', $this->language->get('text_model_number'));
				$view->set('text_soldby', $this->language->get('text_soldby'));
				$view->set('text_results', $this->modelBought->get_text_results());
				$view->set('text_sold_out', $this->language->get('text_sold_out'));
				$view->set('text_discontinued', $this->language->get('text_discontinued'));
				$view->set('entry_page', $this->language->get('entry_page'));
				$view->set('onhand', $this->language->get('onhand'));
				$view->set('previous' , $this->language->get('previous_page'));
				$view->set('next' , $this->language->get('next_page'));
				$view->set('first_page', $this->language->get('first_page'));
				$view->set('last_page', $this->language->get('last_page'));
				$view->set('number_columns', $this->config->get('config_columns') != 3 ? array(1,2,3,4,5) : array(1,2,3,4));
				$view->set('action', $this->url->ssl('bought', FALSE, array('path' => $this->request->gethtml('path'))));
				$view->set('page', ($this->request->has('path') ? $this->session->get('bought.'.$this->request->gethtml('path').'.page') : $this->session->get('bought.page')));

				$view->set('pages', $this->modelBought->get_pagination());
				$view->set('total_pages', $this->modelBought->get_pages());
				$view->set('columns', $columns);
				$view->set('default_order', $default_order);
				$view->set('default_filter', $default_filter);
				$view->set('display_lock', $this->config->get('bought_display_lock'));
				$view->set('options_manufacturer', $this->config->get('options_manufacturer'));
				$view->set('options_model', $this->config->get('options_model'));
				$view->set('text_max_rows', $this->language->get('text_max_rows'));
				$view->set('text_page_rows', $this->language->get('text_page_rows'));
				$view->set('text_columns', $this->language->get('text_columns'));
				$view->set('entry_submit', $this->language->get('entry_submit'));
				$view->set('text_model', $this->language->get('text_model'));
				$view->set('text_all', $this->language->get('text_all'));
				$view->set('text_manufacturer_all', $this->language->get('text_manufacturer_all'));
				$view->set('text_manufacturer', $this->language->get('text_manufacturer'));
				$view->set('manufacturer_id', $this->session->get('bought.manufacturer'));
				$view->set('product_options_select', $this->config->get('bought_options_select'));
				$view->set('addtocart_quantity_box', $this->config->get('addtocart_quantity_box'));
				$view->set('addtocart_quantity_max', $this->config->get('addtocart_quantity_max'));
				$view->set('text_options', $this->language->get('text_options'));
				$view->set('text_quantity_discount', $this->language->get('text_quantity_discount'));
				$view->set('Add_to_Cart', $this->language->get('button_add_to_cart'));
				$view->set('Added_to_Cart', $this->language->get('button_added_to_cart'));
				$view->set('regular_price', $this->language->get('regular_price'));
				$view->set('sale_price', $this->language->get('sale_price'));
				$view->set('text_price', $this->language->get('text_price'));
				$view->set('addtocart',false);
				$view->set('show_stock', $this->config->get('config_show_stock'));
				$view->set('text_sort_by',$this->language->get('text_sort_by'));
				$view->set('text_order', $this->language->get('text_order'));
				$view->set('sort_filter', $this->sort_filter());
				$view->set('sort_order', $this->sort_order());
				$view->set('text_sold_out', $this->language->get('text_sold_out'));
				$view->set('text_discontinued', $this->language->get('text_discontinued'));

				$view->set('show_stock_icon',$this->config->get('config_show_stock_icon'));
				if($this->config->get('config_show_stock_icon')){
					$view->set('low_stock_warning',$this->config->get('config_low_stock_warning'));
				}
				$view->set('text_stock_icon', $this->language->get('text_stock_icon'));
				$view->set('image_base', $this->url->getDomain());
				$view->set('head_def',$this->head_def);
				$this->template->set('head_def',$this->head_def);
				$view->set('remaining', $this->remaining);
				$view->set('discounted', $this->discounted);
				$view->set('text_enlarge', $this->language->get('text_enlarge'));
				$view->set('image_display', $this->config->get('content_image_display'));
				$view->set('this_controller', 'bought');
				$view->set('tpl_columns', $this->modelCore->tpl_columns);

				$this->has_products = $product_data ? TRUE : FALSE;

				$this->template->set('content', $view->fetch('content/bought.tpl'));

		} else {
			$this->template->set('title', $this->language->get('text_error'));
			$view->set('this_controller', 'bought');
			$view->set('tpl_columns', $this->modelCore->tpl_columns);
			$view->set('image_display', $this->config->get('content_image_display'));
			$view->set('heading_title', $this->language->get('text_error'));
			$view->set('text_error', $this->language->get('text_error'));

			$this->template->set('content', $view->fetch('content/error.tpl'));
		}

		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl')); 
	}

	private function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}

	private function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		if($this->tpl_columns == 1.2 || $this->tpl_columns == 3){
			$modules_extra['column'] = array('popular');
		} elseif ($this->tpl_columns == 2.1) {
			$modules_extra['columnright'] = array('popular');
		}

		if(@$this->has_products){ //i.e. customer has bought something
			if($this->tpl_columns == 3){
				$modules_extra['columnright'] = array('boughtoptions','specials');
			} elseif ($this->tpl_columns == 1.2){
				$modules_extra['column'][] = 'boughtoptions';
			} elseif ($this->tpl_columns == 2.1) {
				$modules_extra['columnright'][] = 'boughtoptions';
			}
		} else {
			if($this->tpl_columns == 3){
				$modules_extra['columnright'] = array('specials');
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

	private function model(){
		$language =& $this->locator->get('language');
		$request  =& $this->locator->get('request');
		$session  =& $this->locator->get('session');
		$response =& $this->locator->get('response');

		$language->load('extension/module/boughtoptions.php');

		$text_all = $language->get('text_all');
		$text_model = $language->get('text_model');
		$manufacturer_id = (int)substr($request->gethtml('manufacturer'),0,strpos($request->gethtml('manufacturer'),"_"));
		$model = substr($session->get('bought.model'),0,strpos($session->get('bought.model'),"_"));
		if ($manufacturer_id > 0){
			$manufacturer_sql = " and p.manufacturer_id = ";
			$manufacturer_filter = "'".$manufacturer_id."'";
		} else {
			$manufacturer_sql = "";
			$manufacturer_filter = "";
		}
		$results = $this->modelBought->get_model((int)$this->customer->getId(),$manufacturer_sql,$manufacturer_filter);
		if (count($results) > 1){
			$models_data = array();
			foreach($results as $result){
				$models_data[] = array(
					'model'		=> $result['model'],
					'model_value'	=> $result['model']."_".$this->customer->getId()
				);
			}
		} else {
			$models_data = "";
		}
		if ($models_data){
			$output = '<tr><td>' . $text_model . '</td></tr>'."\n";
			$output .= '<tr><td>';
			$output .= '<select name="model">'."\n";
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

	private function sort_filter(){
		$language =& $this->locator->get('language');
		$language->load('extension/module/boughtoptions.php');
		$sort_filter = array();
		$sort_filter[0] = $language->get('entry_number');
		$sort_filter[1] = $language->get('entry_price');
		return $sort_filter;
	}

	private function sort_order(){
		$language =& $this->locator->get('language');
		$language->load('extension/module/boughtoptions.php');
		$sort_order = array();
		$sort_order[0] = $language->get('entry_ascending');
		$sort_order[1] = $language->get('entry_descending');
		return $sort_order;
	}
}
?>
