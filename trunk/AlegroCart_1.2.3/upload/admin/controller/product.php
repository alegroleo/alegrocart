<?php // Admin Product AlegroCart
class ControllerProduct extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency'); 
		$this->generate_seo =& $locator->get('generateseo');
		$this->image    	=& $locator->get('image');   
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelProduct = $model->get('model_admin_product');
		
		$this->language->load('controller/product.php');
		$this->language->load('controller/product_lfs.php');
	}

  	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
  	}
  
  	function insert() {
    	$this->template->set('title', $this->language->get('heading_title'));
    	if (($this->request->isPost()) && ($this->validateForm())) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
		   	foreach ($this->request->get('name', 'post', array()) as $value) {
				if ($this->modelProduct->check_product_name($value)){
					$this->session->set('message',  $this->language->get('error_already_exists'));
					$this->response->redirect($this->url->ssl('product'));
				}
			}
      		$this->modelProduct->insert_product();
			$insert_id = $this->modelProduct->get_insert_id();
			$name        = $this->request->get('name', 'post');
			$this->modelProduct->get_description_post();
      		foreach ($this->request->get('name', 'post', array()) as $key => $value) {
				if($key == (int)$this->language->getId() && $url_alias && $url_seo){
					$this->product_seo($insert_id, @htmlspecialchars($name[$key]));
				}
				$this->modelProduct->write_description($key, $insert_id,$name[$key]);
      		}
			foreach ($this->request->gethtml('product_discount', 'post', array()) as $product_discount) {
				$this->modelProduct->write_discount($insert_id, $product_discount['quantity'], $product_discount['discount']);
			}
      		foreach ($this->request->gethtml('image', 'post', array()) as $image_id) {
        		$this->modelProduct->write_PtoImage($insert_id, $image_id);
      		}
      		foreach ($this->request->gethtml('download', 'post', array()) as $download_id) {
        		$this->modelProduct->write_download($insert_id, $download_id);
      		}
      		foreach ($this->request->gethtml('category', 'post', array()) as $category_id) {
        		$this->modelProduct->write_PtoCategory($insert_id, $category_id);
				if($url_alias && $url_seo){
					$this->product_to_category_seo($insert_id,$category_id);
				}
	  		}
			if($url_alias && $url_seo){
				$this->manufacturer_to_product_seo($insert_id, $this->request->gethtml('manufacturer_id', 'post'));
				$this->cache->delete('url');
			}
            foreach ($this->request->gethtml('relateddata', 'post', array()) as $product_id) {
				$this->modelProduct->write_related($insert_id, $product_id);
	  		}			
			
	  		$this->cache->delete('product');
			$this->session->set('message', $this->language->get('text_message'));
	  
	  		$this->response->redirect($this->url->ssl('product'));
    	}
    
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function update() {
    	$this->template->set('title', $this->language->get('heading_title'));
	
    	if (($this->request->isPost()) && ($this->validateForm())) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
		   	foreach ($this->request->get('name', 'post', array()) as $value) {
				if($this->modelProduct->check_product_id_name($value)){
					$this->session->set('message', $this->language->get('error_duplicate_name'));
					$this->response->redirect($this->url->ssl('product'));
				}
			}
			$this->modelProduct->update_product();			
			$this->modelProduct->delete_description();
			$this->modelProduct->get_description_post();
		  	foreach ($this->request->get('name', 'post', array()) as $key => $value) {
				if($key == (int)$this->language->getId() && $url_alias && $url_seo){
					$this->delete_product_seo($this->request->gethtml('product_id'));
					$this->product_seo($this->request->gethtml('product_id'), @htmlspecialchars($value));
				}
				$this->modelProduct->write_description($key,$this->request->gethtml('product_id'),$value); 
      		}
			$this->modelProduct->delete_discount();
			foreach ($this->request->gethtml('product_discount', 'post', array()) as $product_discount) {
				$this->modelProduct->write_discount($this->request->gethtml('product_id'), $product_discount['quantity'], $product_discount['discount']);
			}
			$this->modelProduct->delete_PtoImage();
      		foreach ($this->request->gethtml('image', 'post', array()) as $image_id) {
        		$this->modelProduct->write_PtoImage($this->request->gethtml('product_id'), $image_id);
      		}
			$this->modelProduct->delete_download();
      		foreach ($this->request->gethtml('download', 'post', array()) as $download_id) {
        		$this->modelProduct->write_download($this->request->gethtml('product_id'), $download_id);
      		}
			$this->modelProduct->delete_PtoCategory();
      		foreach ($this->request->gethtml('category', 'post', array()) as $category_id) { 
        		$this->modelProduct->write_PtoCategory($this->request->gethtml('product_id'), $category_id);
				if($url_alias && $url_seo){
					$this->delete_product_to_category_seo($this->request->gethtml('product_id'),$category_id);
					$this->product_to_category_seo($this->request->gethtml('product_id'),$category_id);
				}
      		} 
			if($url_alias && $url_seo){
				$this->delete_manufacturer_to_product_seo($this->request->gethtml('product_id'), $this->request->gethtml('manufacturer_id', 'post'));
				$this->manufacturer_to_product_seo($this->request->gethtml('product_id'), $this->request->gethtml('manufacturer_id', 'post'));
				$this->cache->delete('url');
			}
            $this->modelProduct->deleted_related();
            foreach ($this->request->gethtml('relateddata', 'post', array()) as $product_id) {
				$this->modelProduct->write_related($this->request->gethtml('product_id'), $product_id);
	  		}

			$this->cache->delete('product');
			$this->session->set('message', $this->language->get('text_message'));
	  		
			$this->response->redirect($this->url->ssl('product'));
		}
    
    	$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function delete() {
    	$this->template->set('title', $this->language->get('heading_title'));
	
    	if (($this->request->gethtml('product_id')) && ($this->validateDelete())) {
			$url_alias = $this->config->get('config_url_alias');
			$url_seo = $this->config->get('config_seo');
			if($url_alias && $url_seo){
				$this->delete_product_seo($this->request->gethtml('product_id'));
				$manufacturer_info = $this->modelProduct->get_manufacturer_id();
				$this->delete_manufacturer_to_product_seo($this->request->gethtml('product_id'), $manufacturer_info['manufacturer_id']);
				$categorys = $this->modelProduct->get_categorys();
				foreach ($categorys as $category) { 
					if($url_alias && $url_seo){
						$this->delete_product_to_category_seo($this->request->gethtml('product_id'),$category['category_id']);
					}
				} 
				$this->cache->delete('url');
			}
			$this->modelProduct->delete_product();
			$this->modelProduct->delete_description();
			$this->modelProduct->delete_discount();
			$this->modelProduct->delete_ProductOption();
			$this->modelProduct->delete_PtoImage();
			$this->modelProduct->delete_download();
			$this->modelProduct->delete_PtoCategory();
			$this->modelProduct->delete_review();
			$this->modelProduct->deleted_related();

	  		$this->cache->delete('product');
			$this->session->set('message', $this->language->get('text_message'));
	  
	  		$this->response->redirect($this->url->ssl('product'));
    	}
    
    	$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
	
    	$this->response->set($this->template->fetch('layout.tpl'));
  	}

  	function getList() {
		$this->session->set('product_validation', md5(time()));
    	$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_options'),
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_name'),
      		'sort'  => 'pd.name',
      		'align' => 'left'
    	);
		$cols[] = array(
             'name'  => $this->language->get('column_price'),
             'sort'  => 'p.price',
             'align' => 'left'
       );
        $cols[] = array(
             'name'  => $this->language->get('column_stock'),
             'sort'  => 'p.quantity',
             'align' => 'left'
       );
        $cols[] = array(
             'name'  => $this->language->get('column_weight'),
             'sort'  => 'p.weight',
             'align' => 'left'
       );
    	$cols[] = array(
      		'name'  => $this->language->get('column_model'),
      		'sort'  => 'pd.model',
      		'align' => 'left'
    	);
		$cols[] = array(
			'name'  => $this->language->get('column_dated_special'),
			'sort'  => 'p.special_price',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_featured'),
			'sort'  => 'p.featured',
			'align' => 'center'
		);		
    	$cols[] = array(
      		'name'  => $this->language->get('column_status'),
      		'sort'  => 'p.status',
      		'align' => 'center'
    	);
		$cols[] = array(
             'name'  => $this->language->get('column_image'),
             'sort'  => 'i.filename',
             'align' => 'right'
       );
    	$cols[] = array(
      		'name'  => $this->language->get('column_sort_order'),
      		'sort'  => 'p.sort_order',
      		'align' => 'right'
    	);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'right'
    	);

		$decimal_place = $this->currency->currencies[$this->currency->code]['decimal_place'];
		$results = $this->modelProduct->get_page();
    	$rows = array();
    	foreach ($results as $result) {
      		$cell = array();
      		$cell[] = array(
        		'icon'  => 'folder.png',
        		'align' => 'center',
				'path'  => $this->url->ssl('product_option', FALSE, array('product_id' => $result['product_id']))
		  	);
      		$cell[] = array(
        		'value' => $result['name'],
        		'align' => 'left'
		  	);
			$cell[] = array(
               'value' => number_format($result['price'],$decimal_place,'.',''),
               'align' => 'left'
            );
            $cell[] = array(
               'value' => $result['quantity'],
               'align' => 'left'
            );
            $cell[] = array(
               'value' => $result['weight'],
               'align' => 'left'
            );
      		$cell[] = array(
        		'value' => $result['model'],
        		'align' => 'left'
      		);
			$cell[] = array(
			    'value' => number_format($result['special_price'],$decimal_place,'.',''),
				'align' => 'center'
			);
			$featured_special = "";
			$featured_special .= $result['featured'] ? " F " : "";
			$featured_special .= $result['special_offer'] ? " S " : "";
			$cell[] = array(
				'value' => $featured_special,
				'align' => 'center'
			);
      		$cell[] = array(
        		'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
        		'align' => 'center'
      		);
			$cell[] = array(
               'image' => $this->image->resize($result['filename'], '22', '22'),
               'align' => 'right'
            );
      		$cell[] = array(
        		'value' => $result['sort_order'],
        		'align' => 'right'
      		);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('product', 'update', array('product_id' => $result['product_id']))
      		);
			
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('product', 'delete', array('product_id' => $result['product_id'],'product_validation' =>$this->session->get('product_validation')))
				);
			}
			
      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'right'
      		);
			$rows[] = array('cell' => $cell);
    	}

    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_results', $this->modelProduct->get_text_results());

    	$view->set('entry_page', $this->language->get('entry_page'));
    	$view->set('entry_search', $this->language->get('entry_search'));

    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
   	 	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete')); // In English.php

    	$view->set('error', @$this->error['message']);
 
 		$view->set('message', $this->session->get('message'));
		
		$this->session->delete('message');
		 
    	$view->set('action', $this->url->ssl('product', 'page'));
		$view->set('action_delete', $this->url->ssl('product', 'enableDelete')); // Enable Delete Button
		
    	$view->set('search', $this->session->get('product.search'));
    	$view->set('sort', $this->session->get('product.sort'));
    	$view->set('order', $this->session->get('product.order'));
    	$view->set('page', $this->session->get('product.page'));
 
    	$view->set('cols', $cols);
    	$view->set('rows', $rows);

    	$view->set('list', $this->url->ssl('product'));
    	$view->set('insert', $this->url->ssl('product', 'insert'));
  
    	$view->set('pages', $this->modelProduct->get_pagination());

		return $view->fetch('content/list.tpl');
  	}

  	function getForm() {
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

    	$view->set('text_enabled', $this->language->get('text_enabled'));
    	$view->set('text_disabled', $this->language->get('text_disabled'));
    	$view->set('text_none', $this->language->get('text_none'));
    	$view->set('text_yes', $this->language->get('text_yes'));
    	$view->set('text_no', $this->language->get('text_no'));
 		$view->set('text_plus', $this->language->get('text_plus'));
		$view->set('text_minus', $this->language->get('text_minus'));
		$view->set('text_model', $this->language->get('text_model'));
		$view->set('select_model', $this->language->get('select_model'));
		$view->set('text_unique', $this->language->get('text_unique'));
		
		$symbol_right = $this->currency->currencies[$this->currency->code]['symbol_right'];
		$symbol_left = $this->currency->currencies[$this->currency->code]['symbol_left'];
		
    	$view->set('entry_name', $this->language->get('entry_name'));
    	$view->set('entry_description', $this->language->get('entry_description'));
    	$view->set('entry_model', $this->language->get('entry_model'));
		$view->set('entry_model_number', $this->language->get('entry_model_number'));
		$view->set('entry_manufacturer', $this->language->get('entry_manufacturer'));
    	$view->set('entry_shipping', $this->language->get('entry_shipping'));
    	$view->set('entry_date_available', $this->language->get('entry_date_available'));
    	$view->set('entry_quantity', $this->language->get('entry_quantity'));
		$view->set('entry_discount', $this->language->get('entry_discount'));
    	$view->set('entry_status', $this->language->get('entry_status'));
    	$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
    	$view->set('entry_tax_class', $this->language->get('entry_tax_class'));
    	$view->set('entry_price', $this->language->get('entry_price'));
    	$view->set('entry_weight_class', $this->language->get('entry_weight_class'));
    	$view->set('entry_weight', $this->language->get('entry_weight'));
		$view->set('entry_prefix', $this->language->get('entry_prefix'));
    	$view->set('entry_image', $this->language->get('entry_image'));
    	$view->set('entry_images', $this->language->get('entry_images'));
    	$view->set('entry_download', $this->language->get('entry_download'));
    	$view->set('entry_category', $this->language->get('entry_category'));
        $view->set('entry_min_qty', $this->language->get('entry_min_qty'));
        $view->set('entry_featured', $this->language->get('entry_featured'));
        $view->set('entry_specials', $this->language->get('entry_specials'));
		$view->set('entry_related', $this->language->get('entry_related'));
		$view->set('entry_dated_special', $this->language->get('entry_dated_special', $symbol_left ? $symbol_left : $symbol_right));
		$view->set('entry_regular_price', $this->language->get('entry_regular_price', $symbol_left ? $symbol_left : $symbol_right));
		$view->set('entry_percent_discount', $this->language->get('entry_percent_discount'));
		$view->set('entry_start_date', $this->language->get('entry_start_date'));
		$view->set('entry_end_date', $this->language->get('entry_end_date'));
		$view->set('entry_alt_description', $this->language->get('entry_alt_description'));
		$view->set('entry_technical', $this->language->get('entry_technical'));
		$view->set('entry_meta_title', $this->language->get('entry_meta_title'));
		$view->set('entry_meta_description', $this->language->get('entry_meta_description'));
		$view->set('entry_meta_keywords', $this->language->get('entry_meta_keywords'));
		$view->set('entry_quantity_discount',$this->language->get('entry_quantity_discount'));
		
    	$view->set('button_list', $this->language->get('button_list'));
    	$view->set('button_insert', $this->language->get('button_insert'));
    	$view->set('button_update', $this->language->get('button_update'));
    	$view->set('button_delete', $this->language->get('button_delete'));
    	$view->set('button_save', $this->language->get('button_save'));
    	$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
		
    	$view->set('tab_general', $this->language->get('tab_general'));
    	$view->set('tab_data', $this->language->get('tab_data'));
		$view->set('tab_discount', $this->language->get('tab_discount'));
    	$view->set('tab_image', $this->language->get('tab_image'));
    	$view->set('tab_download', $this->language->get('tab_download'));
    	$view->set('tab_category', $this->language->get('tab_category'));
        $view->set('tab_home', $this->language->get('tab_home'));
		$view->set('tab_dated_special', $this->language->get('tab_dated_special'));	
		$view->set('tab_alt_description', $this->language->get('tab_alt_description')); 
		
    	$view->set('error', @$this->error['message']);
    	$view->set('error_name', @$this->error['name']);
    	$view->set('error_description', @$this->error['description']);
    	$view->set('error_model', @$this->error['model']);
    	$view->set('error_date_available', @$this->error['date_available']);
    	$view->set('error_start_date', @$this->error['start_date']); 
    	$view->set('error_end_date', @$this->error['end_date']); 
		
    	$view->set('action', $this->url->ssl('product', $this->request->gethtml('action'), array('product_id' => $this->request->gethtml('product_id'))));
  
    	$view->set('list', $this->url->ssl('product'));
    	$view->set('insert', $this->url->ssl('product', 'insert'));
		$view->set('cancel', $this->url->ssl('product'));
		
		$currency_code = $this->config->get('config_currency');
		$decimal_place = $this->currency->currencies[$currency_code]['decimal_place'];
		$view->set('decimal_place', $decimal_place);
		$view->set('currency_code' , $currency_code);
		
    	if ($this->request->gethtml('product_id')) {
     		$view->set('update', $this->url->ssl('product', 'update', array('product_id' => $this->request->gethtml('product_id'))));
      		$view->set('delete', $this->url->ssl('product', 'delete', array('product_id' => $this->request->gethtml('product_id'),'product_validation' =>$this->session->get('product_validation'))));
    	}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

    	

    	$product_data = array();
     		 
    	$results = $this->modelProduct->get_languages();
    
    	foreach ($results as $result) {
			$model_data = array();
			$models_results = $this->modelProduct->get_models($result['language_id']);//Get all models
			foreach($models_results as $model_result){
				$model_data[] = array(
					'model'	=> $model_result['model']
				);
			}		
			if (($this->request->gethtml('product_id')) && (!$this->request->isPost())) {
	  			$product_description_info = $this->modelProduct->get_product_description($result['language_id']);
			}
			
			$name             = $this->request->get('name', 'post');
			$model			  = $this->request->get('model', 'post');
			$model_number     = $this->request->get('model_number', 'post');
			$description      = $this->request->get('description', 'post');
			$technical        = $this->request->get('technical', ' post');
			$alt_description  = $this->request->get('alt_destription', 'post');
			$meta_title       = $this->request->gethtml('meta_title', 'post');
			$meta_description = $this->request->gethtml('meta_description', 'post');
			$meta_keywords    = $this->request->gethtml('meta_keywords', 'post');
	  		
			$product_data[] = array(
				'models_data'   => $model_data,
	    		'language_id' 	=> $result['language_id'],
	    		'language'    	=> $result['name'],
	    		'name'        	=> (isset($name[$result['language_id']]) ? $name[$result['language_id']] : @$product_description_info['name']),
				'model'         => (isset($model[$result['language_id']]) ? $model[$result['language_id']] : @$product_description_info['model']),
				'model_number'  => (isset($model_number[$result['language_id']]) ? $model_number[$result['language_id']] : @$product_description_info['model_number']),
	    		'description' 	=> (isset($description[$result['language_id']]) ? $description[$result['language_id']] : @$product_description_info['description']),
				'technical'     => (isset($technical[$result['language_id']]) ? $technical[$result['language_id']] : @$product_description_info['technical']),
				'alt_description' => (isset($alt_description[$result['language_id']]) ? $alt_description[$result['language_id']] : @$product_description_info['alt_description']),
	    		'meta_title' 	=> (isset($meta_title[$result['language_id']]) ? $meta_title[$result['language_id']] : @$product_description_info['meta_title']),			
	    		'meta_description'=> (isset($meta_description[$result['language_id']]) ? $meta_description[$result['language_id']] : @$product_description_info['meta_description']),
	    		'meta_keywords' => (isset($meta_keywords[$result['language_id']]) ? $meta_keywords[$result['language_id']] : @$product_description_info['meta_keywords'])				
			);
    	}

    	$view->set('products', $product_data);

    	if (($this->request->gethtml('product_id')) && (!$this->request->isPost())) {
      		$product_info = $this->modelProduct->get_product_info();
    	}
		
    	$view->set('manufacturers', $this->modelProduct->get_manufacturers());

    	if ($this->request->has('manufacturer_id', 'post')) {
      		$view->set('manufacturer_id', $this->request->gethtml('manufacturer_id', 'post'));
    	} else {
      		$view->set('manufacturer_id', @$product_info['manufacturer_id']);
    	} 
		
    	if ($this->request->has('shipping', 'post')) {
      		$view->set('shipping', $this->request->gethtml('shipping', 'post'));
    	} else {
      		$view->set('shipping', @$product_info['shipping']);
    	}
  
    	if ($this->request->has('image_id', 'post')) {
      		$view->set('image_id', $this->request->gethtml('image_id', 'post'));
    	} else {
      		$view->set('image_id', @$product_info['image_id']);
    	}

    	$month_data = array();
    	$month_data[] = array(
      		'value' => '01',
      		'text'  => $this->language->get('text_january')
    	);
    	$month_data[] = array(
      		'value' => '02',
      		'text'  => $this->language->get('text_february')
    	);
    	$month_data[] = array(
      		'value' => '03',
      		'text'  => $this->language->get('text_march')
    	);
    	$month_data[] = array(
      		'value' => '04',
      		'text'  => $this->language->get('text_april')
    	);
    	$month_data[] = array(
      		'value' => '05',
      		'text'  => $this->language->get('text_may')
    	);
    	$month_data[] = array(
      		'value' => '06',
      		'text'  => $this->language->get('text_june')
    	);
    	$month_data[] = array(
      		'value' => '07',
      		'text'  => $this->language->get('text_july')
    	);
    	$month_data[] = array(
      		'value' => '08',
      		'text'  => $this->language->get('text_august')
    	);
    	$month_data[] = array(
      		'value' => '09',
      		'text'  => $this->language->get('text_september')
    	);
    	$month_data[] = array(
      		'value' => '10',
      		'text'  => $this->language->get('text_october')
    	);
    	$month_data[] = array(
      		'value' => '11',
      		'text'  => $this->language->get('text_november')
    	);
    	$month_data[] = array(
      		'value' => '12',
      		'text'  => $this->language->get('text_december')
    	);
    	$view->set('months', $month_data);
      	
		if (isset($product_info['date_available'])) {
        	$date = explode('/', date('d/m/Y', strtotime($product_info['date_available'])));
      	} else {
        	$date = explode('/', date('d/m/Y', time()));
      	}
			
    	if ($this->request->has('date_available_day', 'post')) {
      		$view->set('date_available_day', $this->request->gethtml('date_available_day', 'post'));
    	} else {
      		$view->set('date_available_day', $date[0]);
    	}			
			
    	if ($this->request->has('date_available_month', 'post')) {
      		$view->set('date_available_month', $this->request->gethtml('date_available_month', 'post'));
    	} else {
      		$view->set('date_available_month', $date[1]);
    	}

    	if ($this->request->has('date_available_year', 'post')) {
      		$view->set('date_available_year', $this->request->gethtml('date_available_year', 'post'));
    	} else {
      		$view->set('date_available_year', $date[2]);
    	}					
			
    	if ($this->request->has('quantity', 'post')) {
      		$view->set('quantity', $this->request->gethtml('quantity', 'post'));
    	} else {
      		$view->set('quantity', @$product_info['quantity']);
    	}

    	if ($this->request->has('price', 'post')) {
      		$view->set('price', number_format($this->request->gethtml('price', 'post'),$decimal_place,'.',''));
    	} else {
      		$view->set('price', @number_format($product_info['price'],$decimal_place,'.',''));
    	}
  
    	if ($this->request->has('sort_order', 'post')) {
      		$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
    	} else {
      		$view->set('sort_order', @$product_info['sort_order']);
    	}

    	if ($this->request->has('status', 'post')) {
      		$view->set('status', $this->request->gethtml('status', 'post'));
    	} else {
      		$view->set('status', @$product_info['status']);
    	}

    	if ($this->request->has('featured', 'post')) {
      		$view->set('featured', $this->request->gethtml('featured', 'post'));
    	} else {
      		$view->set('featured', @$product_info['featured']);
    	}

    	if ($this->request->has('special_offer', 'post')) {
      		$view->set('special_offer', $this->request->gethtml('special_offer', 'post'));
    	} else {
      		$view->set('special_offer', @$product_info['special_offer']);
    	}	

		if ($this->request->has('related', 'post')) {
      		$view->set('related', $this->request->gethtml('related', 'post'));
    	} else {
      		$view->set('related', @$product_info['related']);
    	}
		
		if (isset($product_info['sale_start_date']) && $product_info['sale_start_date'] >= "1970-01-01") {
        	$start_date = explode('/', date('d/m/Y', strtotime($product_info['sale_start_date'])));
      	} else {
        	$start_date = array('00', '00', '0000'); 
      	}
		
		if ($this->request->has('start_date_day', 'post')) {
      		$view->set('start_date_day', $this->request->gethtml('start_date_day', 'post'));
    	} else {
      		$view->set('start_date_day', $start_date[0]);
    	}			
			
    	if ($this->request->has('start_date_month', 'post')) {
      		$view->set('start_date_month', $this->request->gethtml('start_date_month', 'post'));
    	} else {
      		$view->set('start_date_month', $start_date[1]);
    	}

    	if ($this->request->has('start_date_year', 'post')) {
      		$view->set('start_date_year', $this->request->gethtml('start_date_year', 'post'));
    	} else {
      		$view->set('start_date_year', $start_date[2]);
    	}			
		
		if (isset($product_info['sale_end_date']) && $product_info['sale_end_date'] >= "1970-01-01") {
        	$end_date = explode('/', date('d/m/Y', strtotime($product_info['sale_end_date'])));
      	} else {
        	$end_date = array('00', '00', '0000');
      	}
		
		if ($this->request->has('end_date_day', 'post')) {
      		$view->set('end_date_day', $this->request->gethtml('end_date_day', 'post'));
    	} else {
      		$view->set('end_date_day', $end_date[0]);
    	}			
			
    	if ($this->request->has('end_date_month', 'post')) {
      		$view->set('end_date_month', $this->request->gethtml('end_date_month', 'post'));
    	} else {
      		$view->set('end_date_month', $end_date[1]);
    	}

    	if ($this->request->has('end_date_year', 'post')) {
      		$view->set('end_date_year', $this->request->gethtml('end_date_year', 'post'));
    	} else {
      		$view->set('end_date_year', $end_date[2]);
    	}

		if ($this->request->has('special_price', 'post')) {  // Special price
      		$view->set('special_price', number_format($this->request->gethtml('special_price', 'post'),$decimal_place,'.',''));
    	} else {
      		$view->set('special_price', @number_format($product_info['special_price'],$decimal_place,'.',''));
    	}
		
    	if ($this->request->has('tax_class_id', 'post')) {
      		$view->set('tax_class_id', $this->request->gethtml('tax_class_id', 'post'));
    	} else {
      		$view->set('tax_class_id', @$product_info['tax_class_id']);
    	}
    	$view->set('tax_classes', $this->modelProduct->get_tax_classes());
		
    	if ($this->request->has('weight', 'post')) {
      		$view->set('weight', $this->request->gethtml('weight', 'post'));
    	} else {
      		$view->set('weight', @$product_info['weight']);
    	} 

        if ( $this->request->has('min_qty', 'post')) {
            $view->set('min_qty', $this->request->gethtml('min_qty', 'post'));
        } elseif (isset($product_info['min_qty'])) {
            $view->set('min_qty', $product_info['min_qty']);
        } else {
            $view->set('min_qty', 1);
        }

    	if ($this->request->has('weight_class_id', 'post')) {
      		$view->set('weight_class_id', $this->request->gethtml('weight_class_id', 'post'));
    	} elseif (isset($product_info['weight_class_id'])) {
      		$view->set('weight_class_id', $product_info['weight_class_id']);
    	} else {
      		$view->set('weight_class_id', $this->config->get('config_weight_class_id'));
    	}

    	$view->set('weight_classes', $this->modelProduct->get_weight_classes());

     	$product_discount_data = array();
		if (!$this->request->has('product_discount', 'post')) {
    		$results = $this->modelProduct->get_product_discounts();
    		foreach ($results as $result) {
      			$product_discount_data[] = array(
        			'quantity' => $result['quantity'],
					'discount' => $result['discount']
      			);
    		}
			$view->set('product_discounts', $product_discount_data);
		} else {
			$view->set('product_discounts', $this->request->gethtml('product_discount', 'post', array()));
		}
  
    	$image_data = array();
		$results = $this->modelProduct->get_images();
    	foreach ($results as $result) {
			if (($this->request->gethtml('product_id')) && (!$this->request->isPost())) {  
	  			$product_to_image_info = $this->modelProduct->get_product_image($result['image_id']);
			}
      		$image_data[] = array(
        		'image_id'   => $result['image_id'],
        		'title'      => $result['title'],
        		'product_id' => (isset($product_to_image_info) ? $product_to_image_info : in_array($result['image_id'], $this->request->gethtml('image', 'post', array())))
      		);
    	}
    	$view->set('images', $image_data);

    	$download_data = array();
    	$results = $this->modelProduct->get_downloads();
    	foreach ($results as $result) {
			if (($this->request->gethtml('product_id')) && (!$this->request->isPost())) {
	  			$product_to_download_info = $this->modelProduct->get_product_download($result['download_id']);
			}
      		$download_data[] = array(
        		'download_id' => $result['download_id'],
        		'name'        => $result['name'],
        		'product_id'  => (isset($product_to_download_info) ? $product_to_download_info : in_array($result['download_id'], $this->request->gethtml('download', 'post', array())))
      		);
    	}
    	$view->set('downloads', $download_data);
	
    	$category_data = array();
    	$results = $this->modelProduct->get_categories();
    	foreach ($results as $result) {
			if (($this->request->gethtml('product_id')) && (!$this->request->isPost())) {
	  			$product_to_category_info = $this->modelProduct->get_productToCategory($result['category_id']);
			} 		
			$category_data[] = array(
        		'category_id' => $result['category_id'],
        		'name'        => str_repeat('&nbsp;&nbsp;&nbsp;', count(explode('_', $result['path'])) - 1) . $result['name'],
        		'product_id'  => (isset($product_to_category_info) ? $product_to_category_info : in_array($result['category_id'], $this->request->gethtml('category', 'post', array())))
      		);
    	}
    	$view->set('categories', $category_data);

    	$related_data = array();
    	$results = $this->modelProduct->get_related_products();
    	foreach ($results as $result) {
			if (($this->request->gethtml('product_id')) && (!$this->request->isPost())) {
	  			$related_info = $this->modelProduct->get_relatedToProduct($result['product_id']);
			}
			$related_data[] = array(
        		'product_id' => $result['product_id'],
        		'name'        => $result['name'],
				'relateddata'	=> (isset($related_info) ? $related_info : in_array($result['product_id'], $this->request->gethtml('relateddata', 'post', array()))));
    	}
    	$view->set('relateddata', $related_data);		

 		return $view->fetch('content/product.tpl');
  	}
	
	function enableDelete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->validateEnableDelete()){
			if($this->session->get('enable_delete')){
				$this->session->delete('enable_delete');
			} else {
				$this->session->set('enable_delete', TRUE);
			}
			$this->response->redirect($this->url->ssl('product'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('product'));
		}
	}
	
  	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		
    	if (!$this->user->hasPermission('modify', 'product')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
	      
    	foreach ($this->request->get('name', 'post', array()) as $value) {
            if (!$this->validate->strlen($value,1,64)) {
                $this->error['name'] = $this->language->get('error_name');
            }
    	}

		if (!$this->request->gethtml('date_available_month', 'post') || !$this->request->gethtml('date_available_day', 'post') || !$this->request->gethtml('date_available_year', 'post')) {
	  		$this->error['date_available'] = $this->language->get('error_date_available');
		}
    	elseif (!checkdate($this->request->gethtml('date_available_month', 'post'), $this->request->gethtml('date_available_day', 'post'), $this->request->gethtml('date_available_year', 'post'))) {
	  		$this->error['date_available'] = $this->language->get('error_date_available');
		}
		
	   if (!($this->request->gethtml('start_date_month', 'post') === '00' && $this->request->gethtml('start_date_day', 'post') === '00' && $this->request->gethtml('start_date_year', 'post') === '0000') && (!checkdate($this->request->gethtml('start_date_month', 'post'), $this->request->gethtml('start_date_day', 'post'), $this->request->gethtml('start_date_year', 'post')))){
			$this->error['start_date'] = $this->language->get('error_start_date');
		}
	   
	   if (!($this->request->gethtml('end_date_month', 'post') === '00' && $this->request->gethtml('end_date_day', 'post') === '00' && $this->request->gethtml('end_date_year', 'post') === '0000') && (!checkdate($this->request->gethtml('end_date_month', 'post'), $this->request->gethtml('end_date_day', 'post'), $this->request->gethtml('end_date_year', 'post')))){
			$this->error['end_date'] = $this->language->get('error_end_date');
		}		

    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'product')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
  	function validateDelete() {
		if(($this->session->get('product_validation') != $this->request->sanitize('product_validation')) || (strlen($this->session->get('product_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('product_validation');
		
    	if (!$this->user->hasPermission('modify', 'product')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
	  	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}	
	
  	function page() {
		if ($this->request->has('search', 'post')) {
	  		$this->session->set('product.search', $this->request->gethtml('search', 'post'));
		}
	 
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('product.page', (int)$this->request->gethtml('page', 'post'));
		} 
	
		if ($this->request->has('sort', 'post')) {
	  		$this->session->set('product.order', (($this->session->get('product.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('product.order') == 'asc') ? 'desc' : 'asc'));
		}
		
		if ($this->request->has('sort', 'post')) {
			$this->session->set('product.sort', $this->request->gethtml('sort', 'post'));
		}
				
		$this->response->redirect($this->url->ssl('product'));
  	} 	

	function discount() {
		$view = $this->locator->create('template');
	
		$view->set('entry_quantity', $this->language->get('entry_quantity'));
		$view->set('entry_discount', $this->language->get('entry_discount'));
		$view->set('entry_percent_discount', $this->language->get('entry_percent_discount'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
						
		$option_data = array();

		$view->set('discount_id', $this->request->gethtml('discount_id'));
		
		$this->response->set($view->fetch('content/product_discount.tpl'));
	}	
	function product_seo($product_id, $product_name){
		$alias = '';
		$alias .= $this->generate_seo->clean_alias($product_name);
		$query_path = 'controller=product&product_id=' . $product_id;
		$alias .= '.html';
		$this->generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_product_seo($product_id){
		$query_path = 'controller=product&product_id=' . $product_id;
		$this->modelProduct->delete_SEO($query_path);
	}
	function product_to_category_seo($product_id,$category_id){
		$product_info = $this->modelProduct->get_product_name($product_id);
		$category_info = $this->modelProduct->get_category_path($category_id);
		$categories = explode('_', $category_info['path']);
		$alias = '';
		foreach ($categories as $cat_id){
			$row = $this->modelProduct->get_category_name($cat_id);
			$alias .= $this->generate_seo->clean_alias($row['category_name']);
			$alias .= '/';
		}
		$alias .= $this->generate_seo->clean_alias($product_info['product_name']);	
		$alias .= '.html';
		$query_path = 'controller=product&path=' . $category_info['path'] . '&product_id=' . $product_id;
		$this->generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_product_to_category_seo($product_id,$category_id){
		$category_info = $this->modelProduct->get_category_path($category_id);
		$query_path = 'controller=product&path=' . $category_info['path'] . '&product_id=' . $product_id;
		$this->modelProduct->delete_SEO($query_path);
	}
	function manufacturer_to_product_seo($product_id, $manufacturer_id){
		$manufacturer_info = $this->modelProduct->get_manufacturer_name($manufacturer_id);
		$product_info = $this->modelProduct->get_product_name($product_id);
		$alias = '';
		$query_path = 'controller=product&manufacturer_id=' . $manufacturer_id . '&product_id=' . $product_id;
		$alias = $this->generate_seo->clean_alias($manufacturer_info['name']). '/';
		$alias .= $this->generate_seo->clean_alias($product_info['product_name']);
		$alias .= '.html';
		$this->generate_seo->_insert_url_alias($query_path, $alias);
	}
	function delete_manufacturer_to_product_seo($product_id, $manufacturer_id){
		$query_path = 'controller=product&manufacturer_id=' . $manufacturer_id . '&product_id=' . $product_id;
		$this->modelProduct->delete_SEO($query_path);
	}
}
?>