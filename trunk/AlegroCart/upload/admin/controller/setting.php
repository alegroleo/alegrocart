<?php //AlegroCart Setting
class ControllerSetting extends Controller {
	var $error = array();
	var $types=array('css');
	var $logo_types = array('jpg','gif','jpeg','png');
	var $wm_types = array('png');
	var $wm_method = 'auto';
	var $mr_status = NULL;

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
		$this->modelWatermark = $model->get('model_admin_watermark');

		$this->language->load('controller/setting.php');
	}	
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('global_config_store', 'post') && $this->validate_update()) {
			$this->modelSetting->delete_setting();
			$this->modelWatermark->delete_watermark($this->wm_method);
			
			if($this->request->gethtml('global_config_currency', 'post') != $this->request->gethtml('default_currency', 'post')){
				$this->updateRates();
			}

			$this->modelSetting->update_setting();
			$this->modelWatermark->update_watermark($this->wm_method);

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
		$view->set('text_prices_tax', $this->language->get('text_prices_tax'));
		$view->set('text_tax_products', $this->language->get('text_tax_products'));
		$view->set('text_invoice_number', $this->language->get('text_invoice_number'));
		
		$view->set('text_surcharge', $this->language->get('text_surcharge'));
		$view->set('text_instruction', $this->language->get('text_instruction'));
		$view->set('text_emails', $this->language->get('text_emails'));
		$view->set('text_items_per_page', $this->language->get('text_items_per_page'));
		$view->set('text_default_rows', $this->language->get('text_default_rows'));
		$view->set('text_cart_quantity', $this->language->get('text_cart_quantity'));
		$view->set('text_cart_wide', $this->language->get('text_cart_wide'));
		$view->set('text_rss_info', $this->language->get('text_rss_info'));
		$view->set('text_rss_status', $this->language->get('text_rss_status'));
		$view->set('text_dimension_decimal', $this->language->get('text_dimension_decimal'));
		$view->set('text_weight_decimal', $this->language->get('text_weight_decimal'));  //Weight decimal
		$view->set('text_store_logo', $this->language->get('text_store_logo'));
		$view->set('text_footer_logo', $this->language->get('text_footer_logo'));
		$view->set('text_captcha', $this->language->get('text_captcha'));
		$view->set('text_captcha_contactus', $this->language->get('text_captcha_contactus'));
		$view->set('text_captcha_reg', $this->language->get('text_captcha_reg'));
		$view->set('text_captcha_length', $this->language->get('text_captcha_length'));
		$view->set('text_show_stock', $this->language->get('text_show_stock'));
		$view->set('text_product', $this->language->get('text_product'));
		$view->set('text_category', $this->language->get('text_category'));
		$view->set('text_search', $this->language->get('text_search'));
		$view->set('text_category_search', $this->language->get('text_category_search'));
		$view->set('text_check_stock_explantion', $this->language->get('text_check_stock_explantion'));
		$view->set('text_allow_checkout_explantion', $this->language->get('text_allow_checkout_explantion'));
		$view->set('text_subtract_stock_explantion', $this->language->get('text_subtract_stock_explantion'));
		$view->set('text_favicons', $this->language->get('text_favicons'));

		$view->set('text_stock_help', $this->language->get('text_stock_help'));
		$view->set('text_address_explantion', $this->language->get('text_address_explantion'));
		$view->set('text_address_help', $this->language->get('text_address_help'));
		$view->set('text_logo_top_exp', $this->language->get('text_logo_top_exp'));
		$view->set('text_logo_left_exp', $this->language->get('text_logo_left_exp'));
		$view->set('text_footer_top_exp', $this->language->get('text_footer_top_exp'));
		$view->set('text_footer_left_exp', $this->language->get('text_footer_left_exp'));
		$view->set('text_error_handler', $this->language->get('text_error_handler'));
		$view->set('text_error_email', $this->language->get('text_error_email'));
		$view->set('text_error_show_user', $this->language->get('text_error_show_user'));
		$view->set('text_error_show_developer', $this->language->get('text_error_show_developer'));
		$view->set('text_error_developer_ip', $this->language->get('text_error_developer_ip'));
		$view->set('text_error_email_status', $this->language->get('text_error_email_status'));
		$view->set('text_guest_checkout', $this->language->get('text_guest_checkout'));
		$view->set('text_time_zone', $this->language->get('text_time_zone'));
		$view->set('text_token', $this->language->get('text_token'));
		$view->set('text_ean', $this->language->get('text_ean'));
 		$view->set('text_upc', $this->language->get('text_upc'));
		$view->set('text_wm_with_text', $this->language->get('text_wm_with_text'));
		$view->set('text_wm_with_image', $this->language->get('text_wm_with_image'));
		$view->set('text_left', $this->language->get('text_left'));
		$view->set('text_center', $this->language->get('text_center'));
		$view->set('text_right', $this->language->get('text_right'));
		$view->set('text_top', $this->language->get('text_top'));
		$view->set('text_bottom', $this->language->get('text_bottom'));
		$view->set('text_watermark', $this->language->get('text_watermark'));
		$view->set('text_slogo', $this->language->get('text_slogo'));
		$view->set('text_flogo', $this->language->get('text_flogo'));

		$view->set('entry_token', $this->language->get('entry_token'));
		$view->set('entry_store', $this->language->get('entry_store'));
		$view->set('entry_owner', $this->language->get('entry_owner'));
		$view->set('entry_address', $this->language->get('entry_address'));
		$view->set('entry_telephone', $this->language->get('entry_telephone'));
		$view->set('entry_fax', $this->language->get('entry_fax'));
		$view->set('entry_template', $this->language->get('entry_template'));
		$view->set('entry_styles', $this->language->get('entry_styles'));
		$view->set('entry_colors', $this->language->get('entry_colors'));
		$view->set('entry_logo', $this->language->get('entry_logo'));
		$view->set('entry_logo_top', $this->language->get('entry_logo_top'));
		$view->set('entry_logo_left', $this->language->get('entry_logo_left'));
		$view->set('entry_logo_width', $this->language->get('entry_logo_width'));
		$view->set('entry_logo_height', $this->language->get('entry_logo_height'));
		$view->set('entry_captcha_contactus', $this->language->get('entry_captcha_contactus'));
		$view->set('entry_captcha_reg', $this->language->get('entry_captcha_reg'));
		$view->set('entry_captcha_length', $this->language->get('entry_captcha_length'));
		$view->set('entry_admin_favicon', $this->language->get('entry_admin_favicon'));
		$view->set('entry_catalog_favicon', $this->language->get('entry_catalog_favicon'));
		$view->set('entry_footer_logo', $this->language->get('entry_footer_logo'));
		$view->set('entry_footer_logo_top', $this->language->get('entry_footer_logo_top'));
		$view->set('entry_footer_logo_left', $this->language->get('entry_footer_logo_left'));
		$view->set('entry_footer_logo_width', $this->language->get('entry_footer_logo_width'));
		$view->set('entry_footer_logo_height', $this->language->get('entry_footer_logo_height'));
		$view->set('entry_columns', $this->language->get('entry_columns'));
		$view->set('entry_url_alias', $this->language->get('entry_url_alias'));
		$view->set('entry_seo', $this->language->get('entry_seo'));
		$view->set('entry_parse_time', $this->language->get('entry_parse_time'));
		$view->set('entry_ssl', $this->language->get('entry_ssl'));
		$view->set('entry_rows_per_page', $this->language->get('entry_rows_per_page'));
		$view->set('entry_items_per_page', $this->language->get('entry_items_per_page'));
		$view->set('entry_country', $this->language->get('entry_country'));
		$view->set('entry_zone', $this->language->get('entry_zone'));
		$view->set('entry_language', $this->language->get('entry_language'));
		$view->set('entry_currency', $this->language->get('entry_currency'));
		$view->set('entry_currency_surcharge', $this->language->get('entry_currency_surcharge'));
		$view->set('entry_weight', $this->language->get('entry_weight'));
		$view->set('entry_barcode', $this->language->get('entry_barcode'));
		$view->set('entry_dimension_type', $this->language->get('entry_dimension_type'));
		$entry_dimension = array(1 => $this->language->get('entry_linear'),
								2 => $this->language->get('entry_area'),
								3 =>$this->language->get('entry_volume'));
								
		$view->set('entry_dimension', $entry_dimension);
		$view->set('entry_tax', $this->language->get('entry_tax'));
		$view->set('entry_tax_store', $this->language->get('entry_tax_store'));
		$view->set('entry_invoice_number', $this->language->get('entry_invoice_number'));
		$view->set('entry_order_status', $this->language->get('entry_order_status'));
		$view->set('entry_stock_check', $this->language->get('entry_stock_check'));
		$view->set('entry_stock_checkout', $this->language->get('entry_stock_checkout'));
		$view->set('entry_stock_subtract', $this->language->get('entry_stock_subtract'));
		$view->set('entry_guest_checkout', $this->language->get('entry_guest_checkout'));
		$view->set('entry_show_stock', $this->language->get('entry_show_stock'));
		$view->set('entry_show_stock_icon', $this->language->get('entry_show_stock_icon'));
		$view->set('entry_low_stock_warning', $this->language->get('entry_low_stock_warning'));
		$view->set('entry_vat', $this->language->get('entry_vat'));
		$view->set('entry_account', $this->language->get('entry_account'));
		$view->set('entry_checkout', $this->language->get('entry_checkout'));
		$view->set('entry_email', $this->language->get('entry_email'));
		$view->set('entry_email_send', $this->language->get('entry_email_send'));
		$view->set('entry_cache_query', $this->language->get('entry_cache_query'));
		$view->set('entry_compress_output', $this->language->get('entry_compress_output'));
		$view->set('entry_compress_level', $this->language->get('entry_compress_level'));
		$view->set('entry_download', $this->language->get('entry_download'));
		$view->set('entry_free_download', $this->language->get('entry_free_download'));
		$view->set('entry_download_status', $this->language->get('entry_download_status'));		
		$view->set('entry_image_resize', $this->language->get('entry_image_resize'));
		$view->set('entry_image_width', $this->language->get('entry_image_width'));
		$view->set('entry_image_height', $this->language->get('entry_image_height'));
		$view->set('entry_product_width', $this->language->get('entry_product_width'));
		$view->set('entry_product_height',$this->language->get('entry_product_height'));
		$view->set('entry_product_addtocart',$this->language->get('entry_product_addtocart'));
		$view->set('entry_additional_width',$this->language->get('entry_additional_width'));
		$view->set('entry_additional_height',$this->language->get('entry_additional_height'));
		$view->set('entry_alt_description',$this->language->get('entry_alt_description'));
		$view->set('entry_magnifier',$this->language->get('entry_magnifier'));
		$view->set('entry_magnifier_width',$this->language->get('entry_magnifier_width'));
		$view->set('entry_magnifier_height',$this->language->get('entry_magnifier_height'));
		$view->set('entry_address_format',$this->language->get('entry_address_format'));
		$view->set('entry_error_handler_status',$this->language->get('entry_error_handler_status'));
		$view->set('entry_error_email',$this->language->get('entry_error_email'));
		$view->set('entry_error_show_user',$this->language->get('entry_error_show_user'));
		$view->set('entry_error_show_developer',$this->language->get('entry_error_show_developer'));
		$view->set('entry_error_developer_ip',$this->language->get('entry_error_developer_ip'));
		$view->set('entry_error_email_status',$this->language->get('entry_error_email_status'));
		$view->set('entry_time_zone',$this->language->get('entry_time_zone'));
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
		$view->set('entry_dimension_decimal',$this->language->get('entry_dimension_decimal'));
		$view->set('entry_weight_decimal',$this->language->get('entry_weight_decimal'));   //Weight decimal
		$view->set('entry_rss_limit',$this->language->get('entry_rss_limit'));
		$view->set('entry_rss_status',$this->language->get('entry_rss_status'));
		$view->set('entry_rss_source',$this->language->get('entry_rss_source'));
		$view->set('quantity_selects', array('selectbox', 'textbox'));
		$view->set('entry_sitemap_status',$this->language->get('entry_sitemap_status'));
 		$view->set('entry_wm_text',$this->language->get('entry_wm_text'));
		$view->set('font_sizes', array('1', '2', '3', '4', '5'));
 		$view->set('entry_wm_fontsize',$this->language->get('entry_wm_fontsize'));
 		$view->set('entry_wm_fontcolor',$this->language->get('entry_wm_fontcolor'));
 		$view->set('entry_wm_transparency',$this->language->get('entry_wm_transparency'));
 		$view->set('entry_wm_thposition',$this->language->get('entry_wm_thposition'));
 		$view->set('entry_wm_tvposition',$this->language->get('entry_wm_tvposition'));
 		$view->set('entry_wm_thmargin',$this->language->get('entry_wm_thmargin'));
 		$view->set('entry_wm_tvmargin',$this->language->get('entry_wm_tvmargin'));
		$view->set('entry_wm_image',$this->language->get('entry_wm_image'));
 		$view->set('entry_wm_scale',$this->language->get('entry_wm_scale'));
 		$view->set('entry_wm_ihposition',$this->language->get('entry_wm_ihposition'));
 		$view->set('entry_wm_ivposition',$this->language->get('entry_wm_ivposition'));
 		$view->set('entry_wm_ihmargin',$this->language->get('entry_wm_ihmargin'));
 		$view->set('entry_wm_ivmargin',$this->language->get('entry_wm_ivmargin'));
		$view->set('entry_discount_options',$this->language->get('entry_discount_options'));
		$view->set('entry_session_expire',$this->language->get('entry_session_expire'));

 		$view->set('explanation_session_expire',$this->language->get('explanation_session_expire'));
 		$view->set('explanation_option_discount',$this->language->get('explanation_option_discount'));
		$view->set('explanation_stock_icon',$this->language->get('explanation_stock_icon'));
		$view->set('explanation_stock_warning',$this->language->get('explanation_stock_warning'));
		$view->set('explanation_wm_text',$this->language->get('explanation_wm_text'));
 		$view->set('explanation_wm_fontsize',$this->language->get('explanation_wm_fontsize'));
 		$view->set('explanation_wm_fontcolor',$this->language->get('explanation_wm_fontcolor'));
 		$view->set('explanation_wm_transparency',$this->language->get('explanation_wm_transparency'));
 		$view->set('explanation_wm_thposition',$this->language->get('explanation_wm_thposition'));
 		$view->set('explanation_wm_tvposition',$this->language->get('explanation_wm_tvposition'));
 		$view->set('explanation_wm_thmargin',$this->language->get('explanation_wm_thmargin'));
 		$view->set('explanation_wm_tvmargin',$this->language->get('explanation_wm_tvmargin'));
		$view->set('explanation_wm_scale',$this->language->get('explanation_wm_scale'));
 		$view->set('explanation_wm_ihposition',$this->language->get('explanation_wm_ihposition'));
 		$view->set('explanation_wm_ivposition',$this->language->get('explanation_wm_ivposition'));
 		$view->set('explanation_wm_ihmargin',$this->language->get('explanation_wm_ihmargin'));
 		$view->set('explanation_wm_ivmargin',$this->language->get('explanation_wm_ivmargin'));
		$view->set('explanation_wm_image',$this->language->get('explanation_wm_image'));
		$view->set('explanation_default_weight',$this->language->get('explanation_default_weight'));
		$view->set('explanation_address',$this->language->get('explanation_address'));
		$view->set('explanation_pr_download',$this->language->get('explanation_pr_download'));
		$view->set('explanation_pr_download_status',$this->language->get('explanation_pr_download_status'));
		$view->set('explanation_free_download',$this->language->get('explanation_free_download'));
		$view->set('explanation_sitemap',$this->language->get('explanation_sitemap'));
		$view->set('explanation_favicon',$this->language->get('explanation_favicon'));
		$view->set('explanation_mr_loaded',$this->language->get('explanation_mr_loaded'));
		$view->set('explanation_mr_not_loaded',$this->language->get('explanation_mr_not_loaded'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_shop', $this->language->get('tab_shop'));
		$view->set('tab_admin', $this->language->get('tab_admin'));
		$view->set('tab_local', $this->language->get('tab_local'));
		$view->set('tab_stock', $this->language->get('tab_stock'));
		$view->set('tab_option', $this->language->get('tab_option'));
		$view->set('tab_mail', $this->language->get('tab_mail'));
		$view->set('tab_cache', $this->language->get('tab_cache'));
		$view->set('tab_image', $this->language->get('tab_image'));
		$view->set('tab_download', $this->language->get('tab_download'));
		$view->set('tab_general', $this->language->get('tab_general'));
		$view->set('tab_watermark', $this->language->get('tab_watermark'));

		$view->set('error', @$this->error['message']);
		$view->set('error_store', @$this->error['store']);
		$view->set('error_owner', @$this->error['owner']);
		$view->set('error_address', @$this->error['address']);
		$view->set('error_telephone', @$this->error['telephone']);
		$view->set('error_color', @$this->error['color']);
		$view->set('error_wm_text', @$this->error['wm_text']);
		$view->set('error_wm_fontcolor', @$this->error['wm_fontcolor']);
		$view->set('error_wm_transparency', @$this->error['wm_trancparency']);
		$view->set('error_wm_scale', @$this->error['wm_scale']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('setting'));
		$view->set('cancel', $this->url->ssl('setting'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if(function_exists('apache_get_modules')){
			if(in_array('mod_rewrite', apache_get_modules())) {
				$mr_status = 1;
			} else {
				$mr_status = 0;
			}
		}
		$view->set('mr_status', $mr_status);

		$results = $this->modelSetting->get_settings();
		foreach ($results as $result) {
			$setting_info[$result['type']][$result['key']] = $result['value'];
		}

		if ($this->request->has('global_config_token', 'post')) {
			$view->set('global_config_token', $this->request->gethtml('global_config_token', 'post'));
		} else {
			$view->set('global_config_token', @$setting_info['global']['config_token']);
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
		
		if ($this->request->has('global_config_address_format', 'post')) {
			$view->set('global_config_address_format', $this->request->gethtml('global_config_address_format', 'post'));
		} else {
			$view->set('global_config_address_format', @$setting_info['global']['config_address_format']);
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
		
		$view->set('logos', $this->getLogos());
		if ($this->request->has('catalog_config_store_logo', 'post')) {  // Cataloge Logo
			$view->set('catalog_config_store_logo', $this->request->gethtml('catalog_config_store_logo', 'post'));
		} else {
			$view->set('catalog_config_store_logo', @$setting_info['catalog']['config_store_logo']);
		}
		
		if ($this->request->has('catalog_config_logo_left', 'post')) {
			$view->set('catalog_config_logo_left', $this->request->gethtml('catalog_config_logo_left', 'post'));
		} else {
			$view->set('catalog_config_logo_left', @$setting_info['catalog']['config_logo_left']);
		}
		
		if ($this->request->has('catalog_config_logo_top', 'post')) {
			$view->set('catalog_config_logo_top', $this->request->gethtml('catalog_config_logo_top', 'post'));
		} else {
			$view->set('catalog_config_logo_top', @$setting_info['catalog']['config_logo_top']);
		}
		
		if ($this->request->has('catalog_config_logo_width', 'post')) {
			$view->set('catalog_config_logo_width', $this->request->gethtml('catalog_config_logo_width', 'post'));
		} else {
			$view->set('catalog_config_logo_width', @$setting_info['catalog']['config_logo_width']);
		}
		
		if ($this->request->has('catalog_config_logo_height', 'post')) {
			$view->set('catalog_config_logo_height', $this->request->gethtml('catalog_config_logo_height', 'post'));
		} else {
			$view->set('catalog_config_logo_height', @$setting_info['catalog']['config_logo_height']);
		}
		
		if ($this->request->has('catalog_config_footer_logo', 'post')) {  // Cataloge Logo
			$view->set('catalog_config_footer_logo', $this->request->gethtml('catalog_config_footer_logo', 'post'));
		} else {
			$view->set('catalog_config_footer_logo', @$setting_info['catalog']['config_footer_logo']);
		}
		
		if ($this->request->has('catalog_footer_logo_left', 'post')) {
			$view->set('catalog_footer_logo_left', $this->request->gethtml('catalog_footer_logo_left', 'post'));
		} else {
			$view->set('catalog_footer_logo_left', @$setting_info['catalog']['footer_logo_left']);
		}
		
		if ($this->request->has('catalog_footer_logo_top', 'post')) {
			$view->set('catalog_footer_logo_top', $this->request->gethtml('catalog_footer_logo_top', 'post'));
		} else {
			$view->set('catalog_footer_logo_top', @$setting_info['catalog']['footer_logo_top']);
		}
		
		if ($this->request->has('catalog_footer_logo_width', 'post')) {
			$view->set('catalog_footer_logo_width', $this->request->gethtml('catalog_footer_logo_width', 'post'));
		} else {
			$view->set('catalog_footer_logo_width', @$setting_info['catalog']['footer_logo_width']);
		}
		
		if ($this->request->has('catalog_footer_logo_height', 'post')) {
			$view->set('catalog_footer_logo_height', $this->request->gethtml('catalog_footer_logo_height', 'post'));
		} else {
			$view->set('catalog_footer_logo_height', @$setting_info['catalog']['footer_logo_height']);
		}
		
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
		
		if ($this->request->has('global_config_tax_store', 'post')) {
			$view->set('global_config_tax_store', $this->request->gethtml('global_config_tax_store', 'post'));
		} else {
			$view->set('global_config_tax_store', @$setting_info['global']['config_tax_store']);
		}

		if ($this->request->has('global_invoice_number', 'post')) {
			$view->set('global_invoice_number', $this->request->gethtml('global_invoice_number', 'post'));
		} else {
			$view->set('global_invoice_number', @$setting_info['global']['invoice_number']);
		}
		
		if ($this->request->has('global_config_time_zone', 'post')) {
			$view->set('global_config_time_zone', $this->request->gethtml('global_config_time_zone', 'post'));
		} else {
			$view->set('global_config_time_zone', @$setting_info['global']['config_time_zone']);
		}
		
		if ($this->request->has('global_error_developer_ip', 'post')) {
			$view->set('global_error_developer_ip', $this->request->gethtml('global_error_developer_ip', 'post'));
		} else {
			$view->set('global_error_developer_ip', @$setting_info['global']['error_developer_ip']);
		}
		if ($this->request->has('global_error_show_user', 'post')) {
			$view->set('global_error_show_user', $this->request->gethtml('global_error_show_user', 'post'));
		} else {
			$view->set('global_error_show_user', @$setting_info['global']['error_show_user']);
		}
		if ($this->request->has('global_error_show_developer', 'post')) {
			$view->set('global_error_show_developer', $this->request->gethtml('global_error_show_developer', 'post'));
		} else {
			$view->set('global_error_show_developer', @$setting_info['global']['error_show_developer']);
		}
		if ($this->request->has('global_config_error_email', 'post')) {
			$view->set('global_config_error_email', $this->request->gethtml('global_config_error_email', 'post'));
		} else {
			$view->set('global_config_error_email', @$setting_info['global']['config_error_email']);
		}
		if ($this->request->has('global_error_email_status', 'post')) {
			$view->set('global_error_email_status', $this->request->gethtml('global_error_email_status', 'post'));
		} else {
			$view->set('global_error_email_status', @$setting_info['global']['error_email_status']);
		}
		if ($this->request->has('global_error_handler_status', 'post')) {
			$view->set('global_error_handler_status', $this->request->gethtml('global_error_handler_status', 'post'));
		} else {
			$view->set('global_error_handler_status', @$setting_info['global']['error_handler_status']);
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

		if ($this->request->has('global_config_session_expire', 'post')) {
			$view->set('global_config_session_expire', $this->request->gethtml('global_config_session_expire', 'post'));
		} else {
			$view->set('global_config_session_expire', @$setting_info['global']['config_session_expire']);
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
		
		if ($this->request->has('catalog_config_show_stock')) {
			$view->set('catalog_config_show_stock', $this->request->gethtml('catalog_config_show_stock'));
		} else {
			$view->set('catalog_config_show_stock', @$setting_info['catalog']['config_show_stock']);
		}
		
		if ($this->request->has('catalog_config_show_stock_icon')) {
			$view->set('catalog_config_show_stock_icon', $this->request->gethtml('catalog_config_show_stock_icon'));
		} else {
			$view->set('catalog_config_show_stock_icon', @$setting_info['catalog']['config_show_stock_icon']);
		}
		
		if ($this->request->has('catalog_config_low_stock_warning')) {
			$view->set('catalog_config_low_stock_warning', $this->request->gethtml('catalog_config_low_stock_warning'));
		} else {
			$view->set('catalog_config_low_stock_warning', @$setting_info['catalog']['config_low_stock_warning']);
		}
		
		if ($this->request->has('catalog_config_discount_options')) {
			$view->set('catalog_config_discount_options', $this->request->gethtml('catalog_config_discount_options'));
		} else {
			$view->set('catalog_config_discount_options', @$setting_info['catalog']['config_discount_options']);
		}
		
		if ($this->request->has('catalog_config_guest_checkout')) {
			$view->set('catalog_config_guest_checkout', $this->request->gethtml('catalog_config_guest_checkout'));
		} else {
			$view->set('catalog_config_guest_checkout', @$setting_info['catalog']['config_guest_checkout']);
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
		if ($this->request->has('catalog_alternate_description')) {
			$view->set('catalog_alternate_description', $this->request->gethtml('catalog_alternate_description'));
		} else {
			$view->set('catalog_alternate_description', @$setting_info['catalog']['alternate_description']);
		}
		if ($this->request->has('catalog_magnifier')) {
			$view->set('catalog_magnifier', $this->request->gethtml('catalog_magnifier'));
		} else {
			$view->set('catalog_magnifier', @$setting_info['catalog']['magnifier']);
		}
		if ($this->request->has('catalog_magnifier_width')) {
			$view->set('catalog_magnifier_width', $this->request->gethtml('catalog_magnifier_width'));
		} else {
			$view->set('catalog_magnifier_width', @$setting_info['catalog']['magnifier_width']);
		}
		if ($this->request->has('catalog_magnifier_height')) {
			$view->set('catalog_magnifier_height', $this->request->gethtml('catalog_magnifier_height'));
		} else {
			$view->set('catalog_magnifier_height', @$setting_info['catalog']['magnifier_height']);
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
			$view->set('catalog_product_image_display', $this->request->gethtml('catalog_product_image_display', 'post'));
		} else {
			$view->set('catalog_product_image_display', @$setting_info['catalog']['product_image_display']);
		}
		if ($this->request->has('catalog_content_image_display', 'post')) {
			$view->set('catalog_content_image_display', $this->request->gethtml('catalog_content_image_display', 'post'));
		} else {
			$view->set('catalog_content_image_display', @$setting_info['catalog']['content_image_display']);
		}
		if ($this->request->has('catalog_content_lines_single', 'post')) {
			$view->set('catalog_content_lines_single', $this->request->gethtml('catalog_content_lines_single', 'post'));
		} else {
			$view->set('catalog_content_lines_single', @$setting_info['catalog']['content_lines_single']);
		}
		if ($this->request->has('catalog_content_lines_multi', 'post')) {
			$view->set('catalog_content_lines_multi', $this->request->gethtml('catalog_content_lines_multi', 'post'));
		} else {
			$view->set('catalog_content_lines_multi', @$setting_info['catalog']['content_lines_multi']);
		}
		if ($this->request->has('catalog_content_lines_char', 'post')) {
			$view->set('catalog_content_lines_char', $this->request->gethtml('catalog_content_lines_char', 'post'));
		} else {
			$view->set('catalog_content_lines_char', @$setting_info['catalog']['content_lines_char']);
		}
		
		if ($this->request->has('catalog_search_rows')) {
			$view->set('catalog_search_rows', $this->request->gethtml('catalog_search_rows'));
		} else {
			$view->set('catalog_search_rows', @$setting_info['catalog']['search_rows']);
		}
		if ($this->request->has('catalog_category_rows')) {
			$view->set('catalog_category_rows', $this->request->gethtml('catalog_category_rows'));
		} else {
			$view->set('catalog_category_rows', @$setting_info['catalog']['category_rows']);
		}
		//End of New Block

		//Watermark
		$watermark_data = $this->modelWatermark->get_watermark_data($this->wm_method);

		if ($this->request->has('wm_text', 'post')) {
			$view->set('wm_text', $this->request->gethtml('wm_text', 'post'));
		} else {
			$view->set('wm_text', @$watermark_data['wm_text']);
		}
		if ($this->request->has('wm_font', 'post')) {
			$view->set('wm_font', $this->request->gethtml('wm_font', 'post'));
		} else {
			$view->set('wm_font', @$watermark_data['wm_font']);
		}	
		if ($this->request->has('wm_fontcolor', 'post')) {
			$view->set('wm_fontcolor', $this->request->gethtml('wm_fontcolor', 'post'));
		} else {
			$view->set('wm_fontcolor', @$watermark_data['wm_fontcolor']);
		}
		if ($this->request->has('wm_transparency', 'post')) {
			$view->set('wm_transparency', $this->request->gethtml('wm_transparency', 'post'));
		} else {
			$view->set('wm_transparency', @$watermark_data['wm_transparency']);
		}
		if ($this->request->has('wm_thposition', 'post')) {
			$view->set('wm_thposition', $this->request->gethtml('wm_thposition', 'post'));
		} else {
			$view->set('wm_thposition', @$watermark_data['wm_thposition']);
		}
		if ($this->request->has('wm_tvposition', 'post')) {
			$view->set('wm_tvposition', $this->request->gethtml('wm_tvposition', 'post'));
		} else {
			$view->set('wm_tvposition', @$watermark_data['wm_tvposition']);
		}
		if ($this->request->has('wm_thmargin', 'post')) {
			$view->set('wm_thmargin', $this->request->gethtml('wm_thmargin', 'post'));
		} else {
			$view->set('wm_thmargin', @$watermark_data['wm_thmargin']);
		}
		if ($this->request->has('wm_tvmargin', 'post')) {
			$view->set('wm_tvmargin', $this->request->gethtml('wm_tvmargin', 'post'));
		} else {
			$view->set('wm_tvmargin', @$watermark_data['wm_tvmargin']);
		}
		$view->set('images', $this->getImages());
		if ($this->request->has('wm_image', 'post')) {
			$view->set('wm_image', $this->request->gethtml('wm_image', 'post'));
		} else {
			$view->set('wm_image', @$watermark_data['wm_image']);
		}
		if ($this->request->has('wm_scale', 'post')) {
			$view->set('wm_scale', $this->request->gethtml('wm_scale', 'post'));
		} else {
			$view->set('wm_scale', @$watermark_data['wm_scale']);
		}
		if ($this->request->has('wm_ihposition', 'post')) {
			$view->set('wm_ihposition', $this->request->gethtml('wm_ihposition', 'post'));
		} else {
			$view->set('wm_ihposition', @$watermark_data['wm_ihposition']);
		}
		if ($this->request->has('wm_ivposition', 'post')) {
			$view->set('wm_ivposition', $this->request->gethtml('wm_ivposition', 'post'));
		} else {
			$view->set('wm_ivposition', @$watermark_data['wm_ivposition']);
		}
		if ($this->request->has('wm_ihmargin', 'post')) {
			$view->set('wm_ihmargin', $this->request->gethtml('wm_ihmargin', 'post'));
		} else {
			$view->set('wm_ihmargin', @$watermark_data['wm_ihmargin']);
		}
		if ($this->request->has('wm_ivmargin', 'post')) {
			$view->set('wm_ivmargin', $this->request->gethtml('wm_ivmargin', 'post'));
		} else {
			$view->set('wm_ivmargin', @$watermark_data['wm_ivmargin']);
		}
		//End of Watermark


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
			$default_currency = $this->request->gethtml('global_config_currency');
		} else {
			$default_currency = @$setting_info['global']['config_currency'];
		}
		$view->set('global_config_currency', $default_currency);
		$view->set('default_currency', $default_currency);
		
		if ($this->request->has('global_config_currency_surcharge')) {
			$view->set('global_config_currency_surcharge', $this->request->gethtml('global_config_currency_surcharge'));
		} else {
			$view->set('global_config_currency_surcharge', @$setting_info['global']['config_currency_surcharge']);
		}
		
		$view->set('currencies', $this->modelSetting->get_currencies());

		if ($this->request->has('global_config_weight_class_id')) {
			$view->set('global_config_weight_class_id', $this->request->gethtml('global_config_weight_class_id'));
		} else {
			$view->set('global_config_weight_class_id', @$setting_info['global']['config_weight_class_id']);
		}

		if ($this->request->has('global_config_weight_decimal')) {
			$view->set('global_config_weight_decimal', $this->request->gethtml('global_config_weight_decimal'));
		} else {
			$view->set('global_config_weight_decimal', @$setting_info['global']['config_weight_decimal']);
		}  //Weight decimal

		$view->set('weight_classes', $this->modelSetting->get_weight_classes());
		
		if ($this->request->has('global_config_barcode_encoding')) {
			$view->set('global_config_barcode_encoding', $this->request->gethtml('global_config_barcode_encoding'));
		} else {
			$view->set('global_config_barcode_encoding', @$setting_info['global']['config_barcode_encoding']);
		}
		if ($this->request->has('global_config_dimension_type_id')) {
			$view->set('global_config_dimension_type_id', $this->request->gethtml('global_config_dimension_type_id'));
		} else {
			$view->set('global_config_dimension_type_id', @$setting_info['global']['config_dimension_type_id']);
		}
		for ($i=1; $i < 4; $i++){
			if ($this->request->has('global_config_dimension_' . $i .'_id')) {
				$view->set('global_config_dimension_' . $i .'_id', $this->request->gethtml('global_config_dimension_' . $i .'_id'));
			} else {
				$view->set('global_config_dimension_' . $i .'_id', @$setting_info['global']['config_dimension_' . $i .'_id']);
			}
		}
		$results = $this->modelSetting->get_types();
		foreach ($results as $result) {
			$type_data[] = array(
				'type_id'   => $result['type_id'],
				'type_text' => $this->language->get('text_'. $result['type_name'])
			);
		}
		$view->set('types', $type_data);
		$dimensions = array();
		for ($i=1; $i < 4; $i++){
			$results = $this->modelSetting->get_dimension_classes($i);
			foreach ($results as $result){
				$dimensions[$i][] = array(
					'dimension_id'	=> $result['dimension_id'],
					'unit'			=> $result['unit'],
					'title'			=> $result['title']
				);
			}
		}
		$view->set('dimensions', $dimensions);
		
		if ($this->request->has('global_config_rss_limit')) {
			$view->set('global_config_rss_limit', $this->request->gethtml('global_config_rss_limit'));
		} else {
			$view->set('global_config_rss_limit', @$setting_info['global']['config_rss_limit']);
		}
		
		if ($this->request->has('global_config_rss_status')) {
			$view->set('global_config_rss_status', $this->request->gethtml('global_config_rss_status'));
		} else {
			$view->set('global_config_rss_status', @$setting_info['global']['config_rss_status']);
		}
		
		if ($this->request->has('global_config_rss_status')) {
			$view->set('global_config_rss_status', $this->request->gethtml('global_config_rss_status'));
		} else {
			$view->set('global_config_rss_status', @$setting_info['global']['config_rss_status']);
		}
		
		if ($this->request->has('global_config_rss_source')) {
			$view->set('global_config_rss_source', $this->request->gethtml('global_config_rss_source'));
		} else {
			$view->set('global_config_rss_source', @$setting_info['global']['config_rss_source']);
		}
		
		$rss_sources = array(
							'rss_featured' => $this->language->get('text_rss_featured'),
							'rss_latest'   => $this->language->get('text_rss_latest'),
							'rss_popular'  => $this->language->get('text_rss_popular'),
							'rss_specials' => $this->language->get('text_rss_specials'));
		$view->set('rss_sources', $rss_sources);
		
		if ($this->request->has('global_config_sitemap_status')) {
			$view->set('global_config_sitemap_status', $this->request->gethtml('global_config_sitemap_status'));
		} else {
			$view->set('global_config_sitemap_status', @$setting_info['global']['config_sitemap_status']);
		}

		$sitemap_path = HTTP_BASE . 'sitemap.php';
		$view->set('sitemap_path', $sitemap_path);

		$admin_favicon = DIR_BASE . 'admin/template/'. @$setting_info['admin']['config_template'] .'/image/favicon.ico';
		$view->set('admin_favicon', $admin_favicon);

		$catalog_favicon = DIR_BASE . 'image/favicon.ico';
		$view->set('catalog_favicon', $catalog_favicon);

		if ($this->request->has('catalog_captcha_contactus')) {
			$view->set('catalog_captcha_contactus', $this->request->gethtml('catalog_captcha_contactus'));
		} else {
			$view->set('catalog_captcha_contactus', @$setting_info['catalog']['captcha_contactus']);
		}
		if ($this->request->has('catalog_captcha_reg')) {
			$view->set('catalog_captcha_reg', $this->request->gethtml('catalog_captcha_reg'));
		} else {
			$view->set('catalog_captcha_reg', @$setting_info['catalog']['captcha_reg']);
		}
		if ($this->request->has('catalog_captcha_length')) {
			$view->set('catalog_captcha_length', $this->request->gethtml('catalog_captcha_length'));
		} else {
			$view->set('catalog_captcha_length', @$setting_info['catalog']['captcha_length']);
		}

		if ($this->request->has('global_config_dimension_decimal')) {
			$view->set('global_config_dimension_decimal', $this->request->gethtml('global_config_dimension_decimal'));
		} else {
			$view->set('global_config_dimension_decimal', @$setting_info['global']['config_dimension_decimal']);
		}

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
		if ($this->request->has('catalog_config_freedownload')) {
			$view->set('catalog_config_freedownload', $this->request->gethtml('catalog_config_freedownload'));
		} else {
			$view->set('catalog_config_freedownload', @$setting_info['catalog']['config_freedownload']);
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
	
	function updateRates(){
		$this->modelSetting->set_default_currency();
		set_time_limit(90);
		$start_time = microtime(true);
		$from = $this->request->gethtml('global_config_currency', 'post');
		$results = $this->modelSetting->get_codes();
		$base_rate = 1.00 + $this->request->gethtml('global_config_currency_surcharge', 'post');
		foreach ($results as $to) {	
			if ($to['status']){
				$rate = $this->currency->currency_converter($base_rate, $from, $to['code']);
				$rate = str_replace(',','.',$rate);
				if ($rate > 0){
					$this->modelSetting->update_rates($rate, $to['code']);
				}	
			}
			if((microtime(true)-$start_time)>88){
				$this->session->set('message', $this->language->get('error_time'));
				$this->cache->delete('currency');
				$this->response->redirect($this->url->ssl('currency'));
			}
		}
		$this->cache->delete('currency');
		
	}
	
	function getLogos(){
		$logos_data = array();
		$files = glob(DIR_IMAGE.'logo'.D_S.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='/\.('.implode('|',$this->logo_types).')$/';
			if (preg_match($pattern,$file)) {
				$filename = basename($file);
				$logos_data[] = array(
					'logo'	=> $filename
				);
			}
		}
		return $logos_data;
	}

	function getImages(){
		$images_data = array();
		$files = glob(DIR_IMAGE.'watermark'.D_S.'*.*');
		if (!$files) { return; }
		foreach ($files as $file) {
			$pattern='#^[^tmp\.](.*)(\.)('.implode('|',$this->wm_types).')$#';
			$filename = basename($file);
			if (preg_match($pattern,$filename)) {
				$images_data[] = array(
					'image'	=> $filename
				);
			}
		}
		return $images_data;
	}

	function viewFooterLogo(){
		if($this->request->gethtml('footer_logo')){
			$output = '<img src="' . HTTP_IMAGE . '/logo/' . $this->request->gethtml('footer_logo') . '"';
			$output .= 'alt="' . $this->language->get('text_flogo'). '" title="'. $this->language->get('text_flogo') .'">';
		} else {
			$output = '';
		}
		$this->response->set($output);
	}
	
	function viewLogo(){
		if($this->request->gethtml('store_logo')){
			$output = '<img src="' . HTTP_IMAGE . '/logo/' . $this->request->gethtml('store_logo') . '"';
			$output .= 'alt="' . $this->language->get('text_slogo'). '" title="'. $this->language->get('text_slogo') .'">';
		} else {
			$output = '';
		}
		$this->response->set($output);
	}

	function viewWmImage(){
		if($this->request->gethtml('wm_image')){
			$output = '<img src="' . HTTP_IMAGE . '/watermark/' . $this->request->gethtml('wm_image') . '"';
			$output .= 'alt="' . $this->language->get('text_watermark'). '" title="'. $this->language->get('text_watermark') .'">';
		} else {
			$output = '';
		}
		$this->response->set($output);
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
	if ($this->request->gethtml('wm_text', 'post') !='') {
		if (!$this->validate->strlen($this->request->gethtml('wm_text', 'post'),0,64)) {
			$this->error['wm_text'] = $this->language->get('error_wm_text');
		}
	}
	if ($this->request->gethtml('wm_fontcolor', 'post') !='') {
		if (!$this->validate->strlen($this->request->gethtml('wm_fontcolor', 'post'),6,6) || !$this->validate->is_hexcolor($this->request->gethtml('wm_fontcolor', 'post'))) {
			$this->error['wm_fontcolor'] = $this->language->get('error_wm_fontcolor');
		}
	}
	if ($this->request->gethtml('wm_transparency', 'post') !='') {
		if ($this->request->gethtml('wm_transparency', 'post') < 0 || $this->request->gethtml('wm_transparency', 'post') > 100) {
			$this->error['wm_trancparency'] = $this->language->get('error_wm_transparency');
		}
	}
	if ($this->request->gethtml('wm_scale', 'post') !='') {
		if ($this->request->gethtml('wm_scale', 'post') < 0 || $this->request->gethtml('wm_scale', 'post') > 100) {
			$this->error['wm_scale'] = $this->language->get('error_wm_scale');
		}
	}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function getToken(){
		$token = $this->request->gethtml('token');
		$output = '<input type="text" size="40"';
		$output .= ' readonly="readonly" name="global_config_token"';
		if ($this->user->hasPermission('modify', 'setting')) {
			$output .= ' value="' . md5($token) . '">';
		} else {
			$output .= ' value="' . $token . '".';
		}
		$this->response->set($output);
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
