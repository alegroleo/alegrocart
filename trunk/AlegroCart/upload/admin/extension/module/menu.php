<?php
class ModuleMenu extends Controller { 
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');

		if (($user->isLogged()) && ($config->get('menu_status'))) {
	  		$language->load('extension/module/menu.php');
       	
	  		$view = $this->locator->create('template');

      		$view->set('text_system', $language->get('text_system'));
    	  	$view->set('text_configuration', $language->get('text_configuration'));
      		$view->set('text_users', $language->get('text_users'));
      		$view->set('text_localisation', $language->get('text_localisation'));
     	 	$view->set('text_catalog', $language->get('text_catalog'));
     	 	$view->set('text_extension', $language->get('text_extension'));
     		$view->set('text_customers', $language->get('text_customers'));
      		$view->set('text_reports', $language->get('text_reports'));
      		$view->set('text_home', $language->get('text_home'));
      		$view->set('text_shop', $language->get('text_catalog'));
     		$view->set('text_setting', $language->get('text_setting'));
      		$view->set('text_user', $language->get('text_user'));
      		$view->set('text_user_group', $language->get('text_user_group'));
      		$view->set('text_language', $language->get('text_language'));
      		$view->set('text_currency', $language->get('text_currency'));
      		$view->set('text_order_status', $language->get('text_order_status'));
      		$view->set('text_country', $language->get('text_country'));
      		$view->set('text_zone', $language->get('text_zone'));
      		$view->set('text_geo_zone', $language->get('text_geo_zone'));
      		$view->set('text_tax_class', $language->get('text_tax_class'));
      		$view->set('text_weight_class', $language->get('text_weight_class'));
			$view->set('text_dimension_class', $language->get('text_dimension_class'));
      		$view->set('text_url_alias', $language->get('text_url_alias'));
      		$view->set('text_backup', $language->get('text_backup'));
			$view->set('text_server_info', $language->get('text_server_info'));
      		$view->set('text_logout', $language->get('text_logout'));
      		$view->set('text_category', $language->get('text_category'));
      		$view->set('text_product', $language->get('text_product'));
      		$view->set('text_option', $language->get('text_option'));
			$view->set('text_manufacturer', $language->get('text_manufacturer'));
      		$view->set('text_image', $language->get('text_image'));
			$view->set('text_image_display', $language->get('text_image_display'));
			$view->set('text_minov', $language->get('text_minov'));
      		$view->set('text_download', $language->get('text_download'));
      		$view->set('text_review', $language->get('text_review'));
      		$view->set('text_information', $language->get('text_information'));
      		$view->set('text_module', $language->get('text_module'));
			$view->set('text_shipping', $language->get('text_shipping'));
      		$view->set('text_payment', $language->get('text_payment'));
      		$view->set('text_calculate', $language->get('text_calculate'));
      		$view->set('text_customer', $language->get('text_customer'));
      		$view->set('text_order', $language->get('text_order'));
			$view->set('text_coupon', $language->get('text_coupon'));
			$view->set('text_mail', $language->get('text_mail'));
			$view->set('text_newsletter', $language->get('text_newsletter'));
      		$view->set('text_online', $language->get('text_online'));
      		$view->set('text_sale', $language->get('text_sale'));
      		$view->set('text_viewed', $language->get('text_viewed'));
      		$view->set('text_purchased', $language->get('text_purchased'));
			$view->set('text_logs', $language->get('text_logs'));
			
			$view->set('text_maintenance', $language->get('text_maintenance'));
			$view->set('text_homepage', $language->get('text_homepage'));
			$view->set('text_tpl_manager',$language->get('text_tpl_manager'));
			$view->set('text_products_with_options',$language->get('text_products_with_options'));
			$view->set('homepage', $url->rawssl('homepage'));
			$view->set('template_manager', $url->rawssl('template_manager'));
			$view->set('maintenance', $url->rawssl('maintenance')); 
    	  	$view->set('home', $url->rawssl('home')); 
      		$view->set('shop', HTTP_CATALOG);
      		$view->set('setting', $url->rawssl('setting'));
      		$view->set('user', $url->rawssl('user'));
      		$view->set('usergroup', $url->rawssl('usergroup'));
      		$view->set('language', $url->rawssl('language'));
      		$view->set('currency', $url->rawssl('currency'));
      		$view->set('order_status', $url->rawssl('order_status'));
      		$view->set('country', $url->rawssl('country'));
      		$view->set('zone', $url->rawssl('zone'));
      		$view->set('geo_zone', $url->rawssl('geo_zone'));
      		$view->set('tax_class', $url->rawssl('tax_class'));
      		$view->set('weight_class', $url->rawssl('weight_class'));
			$view->set('dimension_class', $url->rawssl('dimension_class'));
      		$view->set('url_alias', $url->rawssl('url_alias'));
			$view->set('backup', $url->rawssl('backup'));
      		$view->set('server_info', $url->rawssl('server_info'));
      		$view->set('logout', $url->rawssl('logout'));
      		$view->set('category', $url->rawssl('category'));
      		$view->set('product', $url->rawssl('product'));
			$view->set('products_with_options', $url->rawssl('products_with_options'));
     		$view->set('option', $url->rawssl('option'));
      		$view->set('manufacturer', $url->rawssl('manufacturer'));
			$view->set('image', $url->rawssl('image'));
			$view->set('image_display', $url->rawssl('image_display'));
			$view->set('minov', $url->rawssl('minov'));
      		$view->set('download', $url->rawssl('download'));
     	 	$view->set('review', $url->rawssl('review'));
    	  	$view->set('information', $url->rawssl('information'));
      		$view->set('information', $url->rawssl('information'));
      		$view->set('module', $url->rawssl('extension', false, array('type' => 'module')));
			$view->set('shipping', $url->rawssl('extension', false, array('type' => 'shipping')));
      		$view->set('payment', $url->rawssl('extension', false, array('type' => 'payment')));
      		$view->set('calculate', $url->rawssl('extension', false, array('type' => 'calculate')));
      		$view->set('customer', $url->rawssl('customer'));
      		$view->set('order', $url->rawssl('order'));
			$view->set('coupon', $url->rawssl('coupon'));
			$view->set('mail', $url->rawssl('mail'));
			$view->set('newsletter', $url->rawssl('newsletter'));
      		$view->set('online', $url->rawssl('report_online'));
      		$view->set('sale', $url->rawssl('report_sale'));
      		$view->set('viewed', $url->rawssl('report_viewed'));
      		$view->set('purchased', $url->rawssl('report_purchased'));
			$view->set('logs', $url->rawssl('report_logs'));

      		return $view->fetch('module/menu.tpl');
		}
  	}
}
?>
