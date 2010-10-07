<?php //AlegroCart Setting
class ControllerSetting extends Controller {
	var $error = array();
	var $types=array('css');
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelSetting = $model->get('model_admin_setting');
		
		$this->language->load('controller/setting.php');
	}	
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('global_config_store', 'post') && $this->validate_update()) {
			$this->modelSetting->delete_setting();
			$this->modelSetting->update_setting();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('setting'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));
		$view->set('text_none', $this->language->get('text_none'));
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_select', $this->language->get('text_select'));
		$view->set('text_radio', $this->language->get('text_radio'));
		$view->set('text_instruction', $this->language->get('text_instruction'));
		$view->set('text_emails', $this->language->get('text_emails'));
		$view->set('text_cart_quantity', $this->language->get('text_cart_quantity'));
		
		$view->set('entry_store', $this->language->get('entry_store'));
		$view->set('entry_owner', $this->language->get('entry_owner'));
		$view->set('entry_address', $this->language->get('entry_address'));
		$view->set('entry_telephone', $this->language->get('entry_telephone'));
		$view->set('entry_fax', $this->language->get('entry_fax'));
		$view->set('entry_template', $this->language->get('entry_template'));
		$view->set('entry_styles', $this->language->get('entry_styles'));
		$view->set('entry_colors', $this->language->get('entry_colors'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_url_alias', $this->language->get('entry_url_alias'));
		$view->set('entry_seo', $this->language->get('entry_seo'));
		$view->set('entry_parse_time', $this->language->get('entry_parse_time'));
		$view->set('entry_ssl', $this->language->get('entry_ssl'));
		$view->set('entry_rows_per_page', $this->language->get('entry_rows_per_page'));
		$view->set('entry_country', $this->language->get('entry_country'));
		$view->set('entry_zone', $this->language->get('entry_zone'));
		$view->set('entry_language', $this->language->get('entry_language'));
		$view->set('entry_currency', $this->language->get('entry_currency'));
		$view->set('entry_weight', $this->language->get('entry_weight'));
		$view->set('entry_tax', $this->language->get('entry_tax'));
		$view->set('entry_order_status', $this->language->get('entry_order_status'));
		$view->set('entry_stock_check', $this->language->get('entry_stock_check'));
		$view->set('entry_stock_checkout', $this->language->get('entry_stock_checkout'));
		$view->set('entry_stock_subtract', $this->language->get('entry_stock_subtract'));
		$view->set('entry_vat', $this->language->get('entry_vat'));
		$view->set('entry_account', $this->language->get('entry_account'));
		$view->set('entry_checkout', $this->language->get('entry_checkout'));
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('entry_email_send', $this->language->get('entry_email_send'));
		$view->set('entry_cache_query', $this->language->get('entry_cache_query'));
		$view->set('entry_compress_output', $this->language->get('entry_compress_output'));
		$view->set('entry_compress_level', $this->language->get('entry_compress_level'));
		$view->set('entry_download', $this->language->get('entry_download'));
		$view->set('entry_download_status', $this->language->get('entry_download_status'));		
		$view->set('entry_image_resize', $this->language->get('entry_image_resize'));
		$view->set('entry_image_width', $this->language->get('entry_image_width'));
		$view->set('entry_image_height', $this->language->get('entry_image_height'));
		$view->set('entry_product_width', $this->language->get('entry_product_width'));
		$view->set('entry_product_height',$this->language->get('entry_product_height'));
		$view->set('entry_product_addtocart',$this->language->get('entry_product_addtocart'));
		$view->set('entry_additional_width',$this->language->get('entry_additional_width'));
		$view->set('entry_additional_height',$this->language->get('entry_additional_height'));
		$view->set('entry_category_width',$this->language->get('entry_category_width'));
		$view->set('entry_category_height',$this->language->get('entry_category_height'));
		$view->set('entry_category_addtocart',$this->language->get('entry_category_addtocart'));
		$view->set('entry_search_width',$this->language->get('entry_search_width'));
		$view->set('entry_search_height',$this->language->get('entry_search_height'));
		$view->set('entry_search_addtocart',$this->language->get('entry_search_addtocart'));
		$view->set('entry_search_limit',$this->language->get('entry_search_limit'));
		$view->set('entry_options_select',$this->language->get('entry_options_select'));
		$view->set('entry_image_display',$this->language->get('entry_image_display'));
		$view->set('entry_lines_single',$this->language->get('entry_lines_single'));
		$view->set('entry_lines_multi',$this->language->get('entry_lines_multi'));
		$view->set('entry_lines_char',$this->language->get('entry_lines_char'));
		$view->set('image_displays_product',array('thickbox', 'fancybox', 'lightbox'));
		$view->set('image_displays_content',array('no_image', 'image_link', 'thickbox', 'fancybox', 'lightbox'));
		$view->set('page_columns', array('2', '3'));
		$view->set('entry_email_orders',$this->language->get('entry_email_orders'));
		$view->set('entry_email_accounts',$this->language->get('entry_email_accounts'));
		$view->set('entry_email_newsletter',$this->language->get('entry_email_newsletter'));
		$view->set('entry_email_mail',$this->language->get('entry_email_mail'));
		$view->set('entry_email_contact',$this->language->get('entry_email_contact'));
		$view->set('entry_addtocart_quantity',$this->language->get('entry_addtocart_quantity'));
		$view->set('entry_addtocart_maximum',$this->language->get('entry_addtocart_maximum'));
		$view->set('quantity_selects', array('selectbox', 'textbox'));
 
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));

		$view->set('tab_shop', $this->language->get('tab_shop'));
		$view->set('tab_admin', $this->language->get('tab_admin'));
		$view->set('tab_local', $this->language->get('tab_local'));
		$view->set('tab_stock', $this->language->get('tab_stock'));
		$view->set('tab_option', $this->language->get('tab_option'));
		$view->set('tab_mail', $this->language->get('tab_mail'));
		$view->set('tab_cache', $this->language->get('tab_cache'));
		$view->set('tab_image', $this->language->get('tab_image'));
		$view->set('tab_download', $this->language->get('tab_download'));

		$view->set('error', @$this->error['message']);
		$view->set('error_store', @$this->error['store']);
		$view->set('error_owner', @$this->error['owner']);
		$view->set('error_address', @$this->error['address']);
		$view->set('error_telephone', @$this->error['telephone']);
		$view->set('error_color', @$this->error['color']);
		
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('setting'));
		$view->set('cancel', $this->url->ssl('setting'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$results = $this->modelSetting->get_settings();
		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}

		if ($this->request->has('global_config_store', 'post')) {
			$view->set('global_config_store', $this->request->gethtml('global_config_store', 'post'));
		} else {
			$view->set('global_config_store', @$setting_info['global']['config_store']);
		}

		if ($this->request->has('global_config_owner', 'post')) {
			$view->set('global_config_owner', $this->request->gethtml('global_config_owner', 'post'));
		} else {
			$view->set('global_config_owner', @$setting_info['global']['config_owner']);
		}

		if ($this->request->has('global_config_address', 'post')) {
			$view->set('global_config_address', $this->request->gethtml('global_config_address', 'post'));
		} else {
			$view->set('global_config_address', @$setting_info['global']['config_address']);
		}

		if ($this->request->has('global_config_telephone', 'post')) {
			$view->set('global_config_telephone', $this->request->gethtml('global_config_telephone', 'post'));
		} else {
			$view->set('global_config_telephone', @$setting_info['global']['config_telephone']);
		}

		if ($this->request->has('global_config_fax', 'post')) {
			$view->set('global_config_fax', $this->request->gethtml('global_config_fax', 'post'));
		} else {
			$view->set('global_config_fax', @$setting_info['global']['config_fax']);
		}

		if ($this->request->has('global_config_url_alias', 'post')) {
			$view->set('global_config_url_alias', $this->request->gethtml('global_config_url_alias', 'post'));
		} else {
			$view->set('global_config_url_alias', @$setting_info['global']['config_url_alias']);
		}
		
		if ($this->request->has('global_config_seo', 'post')) {
			$view->set('global_config_seo', $this->request->gethtml('global_config_seo', 'post'));
		} else {
			$view->set('global_config_seo', @$setting_info['global']['config_seo']);
		}
		
		if ($this->request->has('catalog_config_template', 'post')) {  // Catalog Template
			$view->set('catalog_config_template', $this->request->gethtml('catalog_config_template', 'post'));
		} else {
			$view->set('catalog_config_template', @$setting_info['catalog']['config_template']); 
		}
		$template_data = array();
		foreach (glob(DIR_CATALOG_TEMPLATE . '*', GLOB_ONLYDIR) as $dirctory) {
			$template_data[] = basename($dirctory);
		}
		$view->set('catalog_templates', $template_data);
		
		if ($this->request->has('catalog_config_styles', 'post')) {  // Catalog Styles
			$style = $this->request->gethtml('catalog_config_styles', 'post');
			$view->set('catalog_config_styles', $style);
		} else {
			$style = @$setting_info['catalog']['config_styles'];
			$view->set('catalog_config_styles', $style);
		}
		$styles_data = array();
		foreach (glob(DIR_CATALOG_STYLES . '*', GLOB_ONLYDIR) as $dir_style) {
			$styles_data[] = basename($dir_style);
		}
		$view->set('catalog_styles', $styles_data);
		
		if ($this->request->has('catalog_config_columns', 'post')){
			$columns = $this->request->gethtml('catalog_config_columns', 'post');
			$view->set('catalog_config_columns', $columns);
		} else {
			$columns = @$setting_info['catalog']['config_columns'];
			$view->set('catalog_config_columns', $columns);
		}
		
		if ($this->request->has('catalog_config_colors', 'post')) {  // Cataloge Colors
			$view->set('catalog_config_colors', $this->request->gethtml('catalog_config_colors', 'post'));
		} else {
			$view->set('catalog_config_colors', @$setting_info['catalog']['config_colors']);
		}
		$view->set('catalog_colors', $this->checkFiles($style, $columns));
		
		if ($this->request->has('admin_config_template', 'post')) {  // Admin Template
			$view->set('admin_config_template', $this->request->gethtml('admin_config_template', 'post'));
		} else {
			$view->set('admin_config_template', @$setting_info['admin']['config_template']);
		}
		$template_data = array();
		foreach (glob(DIR_TEMPLATE . '*', GLOB_ONLYDIR) as $dirctory) {
			$template_data[] = basename($dirctory);
		}
		$view->set('admin_templates', $template_data);

		if ($this->request->has('catalog_config_max_rows', 'post')) {
			$view->set('catalog_config_max_rows', $this->request->gethtml('catalog_config_max_rows', 'post'));
		} else {
			$view->set('catalog_config_max_rows', @$setting_info['catalog']['config_max_rows']);
		}

		if ($this->request->has('admin_config_max_rows', 'post')) {
			$view->set('admin_config_max_rows', $this->request->gethtml('admin_config_max_rows', 'post'));
		} else {
			$view->set('admin_config_max_rows', @$setting_info['admin']['config_max_rows']);
		}

		if ($this->request->has('global_config_tax', 'post')) {
			$view->set('global_config_tax', $this->request->gethtml('global_config_tax', 'post'));
		} else {
			$view->set('global_config_tax', @$setting_info['global']['config_tax']);
		}

		if ($this->request->has('global_config_email', 'post')) {
			$view->set('global_config_email', $this->request->gethtml('global_config_email', 'post'));
		} else {
			$view->set('global_config_email', @$setting_info['global']['config_email']);
		}
		
		if ($this->request->has('global_config_email_orders', 'post')) {
			$view->set('global_config_email_orders', $this->request->gethtml('global_config_email_orders', 'post'));
		} else {
			$view->set('global_config_email_orders', @$setting_info['global']['config_email_orders']);
		}
		
		if ($this->request->has('global_config_email_accounts', 'post')) {
			$view->set('global_config_email_accounts', $this->request->gethtml('global_config_email_accounts', 'post'));
		} else {
			$view->set('global_config_email_accounts', @$setting_info['global']['config_email_accounts']);
		}
		
		if ($this->request->has('global_config_email_newsletter', 'post')) {
			$view->set('global_config_email_newsletter', $this->request->gethtml('global_config_email_newsletter', 'post'));
		} else {
			$view->set('global_config_email_newsletter', @$setting_info['global']['config_email_newsletter']);
		}
		
		if ($this->request->has('global_config_email_mail', 'post')) {
			$view->set('global_config_email_mail', $this->request->gethtml('global_config_email_mail', 'post'));
		} else {
			$view->set('global_config_email_mail', @$setting_info['global']['config_email_mail']);
		}
		
		if ($this->request->has('global_config_email_contact', 'post')) {
			$view->set('global_config_email_contact', $this->request->gethtml('global_config_email_contact', 'post'));
		} else {
			$view->set('global_config_email_contact', @$setting_info['global']['config_email_contact']);
		}

		if ($this->request->has('global_config_email_send', 'post')) {
			$view->set('global_config_email_send', $this->request->gethtml('global_config_email_send', 'post'));
		} else {
			$view->set('global_config_email_send', @$setting_info['global']['config_email_send']);
		}

		if ($this->request->has('catalog_config_parse_time', 'post')) {
			$view->set('catalog_config_parse_time', $this->request->gethtml('catalog_config_parse_time', 'post'));
		} else {
			$view->set('catalog_config_parse_time', @$setting_info['catalog']['config_parse_time']);
		}

		if ($this->request->has('admin_config_parse_time', 'post')) {
			$view->set('admin_config_parse_time', $this->request->gethtml('admin_config_parse_time', 'post'));
		} else {
			$view->set('admin_config_parse_time', @$setting_info['admin']['config_parse_time']);
		}

		if ($this->request->has('catalog_config_ssl', 'post')) {
			$view->set('catalog_config_ssl', $this->request->gethtml('catalog_config_ssl', 'post'));
		} else {
			$view->set('catalog_config_ssl', @$setting_info['catalog']['config_ssl']);
		}

		if ($this->request->has('admin_config_ssl', 'post')) {
			$view->set('admin_config_ssl', $this->request->gethtml('admin_config_ssl', 'post'));
		} else {
			$view->set('admin_config_ssl', @$setting_info['admin']['config_ssl']);
		}

		if ($this->request->has('catalog_config_stock_check', 'post')) {
			$view->set('catalog_config_stock_check', $this->request->gethtml('catalog_config_stock_check', 'post'));
		} else {
			$view->set('catalog_config_stock_check', @$setting_info['catalog']['config_stock_check']);
		}

		if ($this->request->has('catalog_config_stock_checkout')) {
			$view->set('catalog_config_stock_checkout', $this->request->gethtml('catalog_config_stock_checkout'));
		} else {
			$view->set('catalog_config_stock_checkout', @$setting_info['catalog']['config_stock_checkout']);
		}

		if ($this->request->has('catalog_config_stock_subtract')) {
			$view->set('catalog_config_stock_subtract', $this->request->gethtml('catalog_config_stock_subtract'));
		} else {
			$view->set('catalog_config_stock_subtract', @$setting_info['catalog']['config_stock_subtract']);
		}

		if ($this->request->has('catalog_config_vat')) {
			$view->set('catalog_config_vat', $this->request->gethtml('catalog_config_vat'));
		} else {
			$view->set('catalog_config_vat', @$setting_info['catalog']['config_vat']);
		}

		if ($this->request->has('catalog_config_account_id')) {
			$view->set('catalog_config_account_id', $this->request->gethtml('catalog_config_account_id'));
		} else {
			$view->set('catalog_config_account_id', @$setting_info['catalog']['config_account_id']);
		}

		if ($this->request->has('catalog_config_checkout_id')) {
			$view->set('catalog_config_checkout_id', $this->request->gethtml('catalog_config_checkout_id'));
		} else {
			$view->set('catalog_config_checkout_id', @$setting_info['catalog']['config_checkout_id']);
		}

		$view->set('informations',$this->modelSetting->get_informations());

		if ($this->request->has('global_config_cache_query')) {
			$view->set('global_config_cache_query', $this->request->gethtml('global_config_cache_query'));
		} else {
			$view->set('global_config_cache_query', @$setting_info['global']['config_cache_query']);
		}

		if ($this->request->has('global_config_compress_output')) {
			$view->set('global_config_compress_output', $this->request->gethtml('global_config_compress_output'));
		} else {
			$view->set('global_config_compress_output', @$setting_info['global']['config_compress_output']);
		}

		if ($this->request->has('global_config_compress_level')) {
			$view->set('global_config_compress_level', $this->request->gethtml('global_config_compress_level'));
		} else {
			$view->set('global_config_compress_level', @$setting_info['global']['config_compress_level']);
		}

		if ($this->request->has('global_config_image_resize')) {
			$view->set('global_config_image_resize', $this->request->gethtml('global_config_image_resize'));
		} else {
			$view->set('global_config_image_resize', @$setting_info['global']['config_image_resize']);
		}

		if ($this->request->has('global_config_image_width')) {
			$view->set('global_config_image_width', $this->request->gethtml('global_config_image_width'));
		} else {
			$view->set('global_config_image_width', @$setting_info['global']['config_image_width']);
		}

		if ($this->request->has('global_config_image_height')) {
			$view->set('global_config_image_height', $this->request->gethtml('global_config_image_height'));
		} else {
			$view->set('global_config_image_height', @$setting_info['global']['config_image_height']);
		}
		//New Block for Images & Addtocart
		if ($this->request->has('catalog_product_image_width')) {
			$view->set('catalog_product_image_width', $this->request->gethtml('catalog_product_image_width'));
		} else {
			$view->set('catalog_product_image_width', @$setting_info['catalog']['product_image_width']);
		}
		if ($this->request->has('catalog_product_image_height')) {
			$view->set('catalog_product_image_height', $this->request->gethtml('catalog_product_image_height'));
		} else {
			$view->set('catalog_product_image_height', @$setting_info['catalog']['product_image_height']);
		}
		if ($this->request->has('catalog_product_addtocart')) {
			$view->set('catalog_product_addtocart', $this->request->gethtml('catalog_product_addtocart'));
		} else {
			$view->set('catalog_product_addtocart', @$setting_info['catalog']['product_addtocart']);
		}
		if ($this->request->has('catalog_additional_image_width')) {
			$view->set('catalog_additional_image_width', $this->request->gethtml('catalog_additional_image_width'));
		} else {
			$view->set('catalog_additional_image_width', @$setting_info['catalog']['additional_image_width']);
		}
		if ($this->request->has('catalog_additional_image_height')) {
			$view->set('catalog_additional_image_height', $this->request->gethtml('catalog_additional_image_height'));
		} else {
			$view->set('catalog_additional_image_height', @$setting_info['catalog']['additional_image_height']);
		}
		if ($this->request->has('catalog_category_image_width')) {
			$view->set('catalog_category_image_width', $this->request->gethtml('catalog_category_image_width'));
		} else {
			$view->set('catalog_category_image_width', @$setting_info['catalog']['category_image_width']);
		}
		if ($this->request->has('catalog_category_image_height')) {
			$view->set('catalog_category_image_height', $this->request->gethtml('catalog_category_image_height'));
		} else {
			$view->set('catalog_category_image_height', @$setting_info['catalog']['category_image_height']);
		}
		if ($this->request->has('catalog_category_addtocart')) {
			$view->set('catalog_category_addtocart', $this->request->gethtml('catalog_category_addtocart'));
		} else {
			$view->set('catalog_category_addtocart', @$setting_info['catalog']['category_addtocart']);
		}
		
		if ($this->request->has('catalog_addtocart_quantity_box')) {
			$view->set('catalog_addtocart_quantity_box', $this->request->gethtml('catalog_addtocart_quantity_box'));
		} else {
			$view->set('catalog_addtocart_quantity_box', @$setting_info['catalog']['addtocart_quantity_box']);
		}
		
		if ($this->request->has('catalog_addtocart_quantity_max')) {
			$view->set('catalog_addtocart_quantity_max', $this->request->gethtml('catalog_addtocart_quantity_max'));
		} else {
			$view->set('catalog_addtocart_quantity_max', @$setting_info['catalog']['addtocart_quantity_max']);
		}
		
		if ($this->request->has('catalog_search_image_width')) {
			$view->set('catalog_search_image_width', $this->request->gethtml('catalog_search_image_width'));
		} else {
			$view->set('catalog_search_image_width', @$setting_info['catalog']['search_image_width']);
		}
		if ($this->request->has('catalog_search_image_height')) {
			$view->set('catalog_search_image_height', $this->request->gethtml('catalog_search_image_height'));
		} else {
			$view->set('catalog_search_image_height', @$setting_info['catalog']['search_image_height']);
		}
		if ($this->request->has('catalog_search_addtocart')) {
			$view->set('catalog_search_addtocart', $this->request->gethtml('catalog_search_addtocart'));
		} else {
			$view->set('catalog_search_addtocart', @$setting_info['catalog']['search_addtocart']);
		}
		if ($this->request->has('catalog_search_limit')) {
			$view->set('catalog_search_limit', $this->request->gethtml('catalog_search_limit'));
		} else {
			$view->set('catalog_search_limit', @$setting_info['catalog']['search_limit']);
		}
		if ($this->request->has('catalog_product_options_select')) {
			$view->set('catalog_product_options_select', $this->request->gethtml('catalog_product_options_select'));
		} else {
			$view->set('catalog_product_options_select', @$setting_info['catalog']['product_options_select']);
		}
		if ($this->request->has('catalog_category_options_select')) {
			$view->set('catalog_category_options_select', $this->request->gethtml('catalog_category_options_select'));
		} else {
			$view->set('catalog_category_options_select', @$setting_info['catalog']['category_options_select']);
		}
		if ($this->request->has('catalog_search_options_select')) {
			$view->set('catalog_search_options_select', $this->request->gethtml('catalog_search_options_select'));
		} else {
			$view->set('catalog_search_options_select', @$setting_info['catalog']['search_options_select']);
		}
		if ($this->request->has('catalog_product_image_display', 'post')) {
			$view->set('catalog_product_image_display', $this->request->get('catalog_product_image_display', 'post'));
		} else {
			$view->set('catalog_product_image_display', @$setting_info['catalog']['product_image_display']);
		}
		if ($this->request->has('catalog_content_image_display', 'post')) {
			$view->set('catalog_content_image_display', $this->request->get('catalog_content_image_display', 'post'));
		} else {
			$view->set('catalog_content_image_display', @$setting_info['catalog']['content_image_display']);
		}
		if ($this->request->has('catalog_content_lines_single', 'post')) {
			$view->set('catalog_content_lines_single', $this->request->get('catalog_content_lines_single', 'post'));
		} else {
			$view->set('catalog_content_lines_single', @$setting_info['catalog']['content_lines_single']);
		}
		if ($this->request->has('catalog_content_lines_multi', 'post')) {
			$view->set('catalog_content_lines_multi', $this->request->get('catalog_content_lines_multi', 'post'));
		} else {
			$view->set('catalog_content_lines_multi', @$setting_info['catalog']['content_lines_multi']);
		}
		if ($this->request->has('catalog_content_lines_char', 'post')) {
			$view->set('catalog_content_lines_char', $this->request->get('catalog_content_lines_char', 'post'));
		} else {
			$view->set('catalog_content_lines_char', @$setting_info['catalog']['content_lines_char']);
		}
		//End of New Block
		if ($this->request->has('global_config_country_id')) {
			$view->set('global_config_country_id', $this->request->gethtml('global_config_country_id'));
		} else {
			$view->set('global_config_country_id', @$setting_info['global']['config_country_id']);
		}

		$view->set('countries', $this->modelSetting->get_countries());

		if ($this->request->has('global_config_zone_id')) {
			$view->set('global_config_zone_id', $this->request->gethtml('global_config_zone_id'));
		} else {
			$view->set('global_config_zone_id', @$setting_info['global']['config_zone_id']);
		}

		$view->set('zones', $this->modelSetting->get_zones());

		if ($this->request->has('catalog_config_language')) {
			$view->set('catalog_config_language', $this->request->gethtml('catalog_config_language'));
		} else {
			$view->set('catalog_config_language', @$setting_info['catalog']['config_language']);
		}
        
        if ($this->request->has('admin_config_language')) {
            $view->set('admin_config_language', $this->request->gethtml('admin_config_language'));
        } else {
            $view->set('admin_config_language', @$setting_info['admin']['config_language']);
        }

		$view->set('languages', $this->modelSetting->get_languages());

		if ($this->request->has('global_config_currency')) {
			$view->set('global_config_currency', $this->request->gethtml('global_config_currency'));
		} else {
			$view->set('global_config_currency', @$setting_info['global']['config_currency']);
		}

		$view->set('currencies', $this->modelSetting->get_currencies());

		if ($this->request->has('global_config_weight_class_id')) {
			$view->set('global_config_weight_class_id', $this->request->gethtml('global_config_weight_class_id'));
		} else {
			$view->set('global_config_weight_class_id', @$setting_info['global']['config_weight_class_id']);
		}

		$view->set('weight_classes', $this->modelSetting->get_weight_classes());

		if ($this->request->has('global_config_order_status_id')) {
			$view->set('global_config_order_status_id', $this->request->gethtml('global_config_order_status_id'));
		} else {
			$view->set('global_config_order_status_id', @$setting_info['global']['config_order_status_id']);
		}

		$view->set('order_statuses', $this->modelSetting->get_order_statuses());
 
		if ($this->request->has('catalog_config_download')) {
			$view->set('catalog_config_download', $this->request->gethtml('catalog_config_download'));
		} else {
			$view->set('catalog_config_download', @$setting_info['catalog']['config_download']);
		}

		if ($this->request->has('catalog_config_download_status')) {
			$view->set('catalog_config_download_status', $this->request->gethtml('catalog_config_download_status'));
		} else {
			$view->set('catalog_config_download_status', @$setting_info['catalog']['config_download_status']);
		}
		

		$this->template->set('content', $view->fetch('content/setting.tpl'));
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function checkFiles($style, $columns) {
		$colors_data = array();
		$files = glob(DIR_CATALOG_STYLES.$style.D_S.'colors'.$columns.D_S.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$colors_data[] = array(
					'colorcss'   => $filename
				);	
			}
		}
		return $colors_data;
	}
	function validate_update() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'setting')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
        if (!$this->validate->strlen($this->request->gethtml('global_config_store', 'post'),1,32)) {
			$this->error['store'] = $this->language->get('error_store');
		}
        if (!$this->validate->strlen($this->request->gethtml('global_config_owner', 'post'),1,32)) {
			$this->error['owner'] = $this->language->get('error_owner');
		}
        if (!$this->validate->strlen($this->request->gethtml('global_config_address', 'post'),1,128)) {
			$this->error['address'] = $this->language->get('error_address');
		}
        if (!$this->validate->strlen($this->request->gethtml('global_config_telephone', 'post'),5,32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		if (!$this->validate->strlen($this->request->gethtml('catalog_config_colors','post'),6,32)){
			$this->error['color'] = $this->language->get('error_color');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function getColors(){
		$style = $this->request->gethtml('style');
		$columns = $this->request->gethtml('columns');
		$results = $this->checkFiles($style,$columns);
		if($results){
			$output = '<select name="catalog_config_colors">';
			foreach($results as $result){
				$output .= '<option value="'. $result['colorcss'].'">';
				$output .= $result['colorcss']. '</option>';
			}
			$output .= '</select>';
		} else {
			$output = '<select name="catalog_config_colors"></select>';
		}
		$this->response->set($output);
	}
	function zone() {
		$output = '<select name="global_config_zone_id">';
		$results = $this->modelSetting->get_country_zones();
		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';
			if ($this->request->gethtml('zone_id') == $result['zone_id']) {
				$output .= ' SELECTED';
			}
			$output .= '>' . $result['name'] . '</option>';
		}
		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
		$output .= '</select>';

		$this->response->set($output);
	}
}
?>