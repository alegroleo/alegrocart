<?php //AdminModelSetting AlegroCart
class Model_Admin_Setting extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_setting(){
		$this->database->query("delete from setting where `group` = 'config'");
	}
	function update_setting(){
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_store', `value` = '" . $this->request->gethtml('global_config_store', 'post') . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_owner', `value` = '" . $this->request->gethtml('global_config_owner', 'post') . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_address', `value` = '" . $this->request->gethtml('global_config_address', 'post'). "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_telephone', `value` = '" . $this->request->gethtml('global_config_telephone', 'post') . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_fax', `value` = '" . $this->request->gethtml('global_config_fax', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_template', `value` = '" . $this->request->gethtml('catalog_config_template', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_max_rows', `value` = '" . $this->request->gethtml('catalog_config_max_rows', 'post') . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_url_alias', `value` = '" . $this->request->gethtml('global_config_url_alias', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_parse_time', `value` = '" . $this->request->gethtml('catalog_config_parse_time', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_ssl', `value` = '" . $this->request->gethtml('catalog_config_ssl', 'post') . "'");
		$this->database->query("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_template', `value` = '" . $this->request->gethtml('admin_config_template', 'post') . "'");
		$this->database->query("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_max_rows', `value` = '" . $this->request->gethtml('admin_config_max_rows', 'post') . "'");
		$this->database->query("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_parse_time', `value` = '" . $this->request->gethtml('admin_config_parse_time', 'post')  . "'");
		$this->database->query("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_ssl', `value` = '" . $this->request->gethtml('admin_config_ssl', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_country_id', `value` = '" . $this->request->gethtml('global_config_country_id', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_zone_id', `value` = '" . $this->request->gethtml('global_config_zone_id', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_language', `value` = '" . $this->request->gethtml('catalog_config_language', 'post')  . "'");
		$this->database->query("insert into setting set type = 'admin', `group` = 'config', `key` = 'config_language', `value` = '" . $this->request->gethtml('admin_config_language', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_currency', `value` = '" . $this->request->gethtml('global_config_currency', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_weight_class_id', `value` = '" . $this->request->gethtml('global_config_weight_class_id', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_tax', `value` = '" . $this->request->gethtml('global_config_tax', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_order_status_id', `value` = '" . $this->request->gethtml('global_config_order_status_id', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_stock_check', `value` = '" . $this->request->gethtml('catalog_config_stock_check', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_stock_checkout', `value` = '" . $this->request->gethtml('catalog_config_stock_checkout', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_stock_subtract', `value` = '" . $this->request->gethtml('catalog_config_stock_subtract', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_vat', `value` = '" . $this->request->gethtml('catalog_config_vat', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_account_id', `value` = '" . $this->request->gethtml('catalog_config_account_id', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_checkout_id', `value` = '" . $this->request->gethtml('catalog_config_checkout_id', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email', `value` = '" . $this->request->gethtml('global_config_email', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_send', `value` = '" . $this->request->gethtml('global_config_email_send', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_orders', `value` = '" . $this->request->gethtml('global_config_email_orders', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_accounts', `value` = '" . $this->request->gethtml('global_config_email_accounts', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_newsletter', `value` = '" . $this->request->gethtml('global_config_email_newsletter', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_mail', `value` = '" . $this->request->gethtml('global_config_email_mail', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_email_contact', `value` = '" . $this->request->gethtml('global_config_email_contact', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_cache_query', `value` = '" . $this->request->gethtml('global_config_cache_query', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_compress_output', `value` = '" . $this->request->gethtml('global_config_compress_output', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_compress_level', `value` = '" . $this->request->gethtml('global_config_compress_level', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_image_resize', `value` = '" . $this->request->gethtml('global_config_image_resize', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_image_width', `value` = '" . $this->request->gethtml('global_config_image_width', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_image_height', `value` = '" . $this->request->gethtml('global_config_image_height', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_download', `value` = '" . $this->request->gethtml('catalog_config_download', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_download_status', `value` = '" . $this->request->gethtml('catalog_config_download_status', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_image_width', `value` = '" . $this->request->gethtml('catalog_product_image_width', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_image_height', `value` = '" . $this->request->gethtml('catalog_product_image_height', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_addtocart', `value` = '" . $this->request->gethtml('catalog_product_addtocart', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'additional_image_width', `value` = '" . $this->request->gethtml('catalog_additional_image_width', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'additional_image_height', `value` = '" . $this->request->gethtml('catalog_additional_image_height', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_image_width', `value` = '" . $this->request->gethtml('catalog_category_image_width', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_image_height', `value` = '" . $this->request->gethtml('catalog_category_image_height', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_addtocart', `value` = '" . $this->request->gethtml('catalog_category_addtocart', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_image_width', `value` = '" . $this->request->gethtml('catalog_search_image_width', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_image_height', `value` = '" . $this->request->gethtml('catalog_search_image_height', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_addtocart', `value` = '" . $this->request->gethtml('catalog_search_addtocart', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_limit', `value` = '" . $this->request->gethtml('catalog_search_limit', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_options_select', `value` = '" . $this->request->gethtml('catalog_product_options_select', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'category_options_select', `value` = '" . $this->request->gethtml('catalog_category_options_select', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'search_options_select', `value` = '" . $this->request->gethtml('catalog_search_options_select', 'post')  . "'");
		
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'addtocart_quantity_max', `value` = '" . $this->request->gethtml('catalog_addtocart_quantity_max', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'addtocart_quantity_box', `value` = '" . $this->request->gethtml('catalog_addtocart_quantity_box', 'post')  . "'");
		
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_styles', `value` = '" . $this->request->gethtml('catalog_config_styles', 'post')  . "'");
		$this->database->query("insert into setting set type = 'global', `group` = 'config', `key` = 'config_seo', `value` = '" . $this->request->gethtml('global_config_seo', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_colors', `value` = '" . $this->request->gethtml('catalog_config_colors', 'post')  . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'config_columns', `value` = '" . $this->request->gethtml('catalog_config_columns', 'post')  . "'");
		//New
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'product_image_display', `value` = '" . $this->request->get('catalog_product_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'content_image_display', `value` = '" . $this->request->get('catalog_content_image_display', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'content_lines_single', `value` = '" . $this->request->get('catalog_content_lines_single', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'content_lines_multi', `value` = '" . $this->request->get('catalog_content_lines_multi', 'post') . "'");
		$this->database->query("insert into setting set type = 'catalog', `group` = 'config', `key` = 'content_lines_char', `value` = '" . $this->request->get('catalog_content_lines_char', 'post') . "'");
		
		//$this->database->query();
	}
	function get_settings(){
		$results = $this->database->getRows("select * from setting where `group` = 'config'");
		return $results;
	}
	function get_informations(){
		$results = $this->database->cache('information-' . (int)$this->language->getId(), "select * from information i left join information_description id on (i.information_id = id.information_id) where id.language_id = '" . (int)$this->language->getId() . "' order by i.sort_order");
		return $results;
	}
	function get_countries(){
		$results = $this->database->cache('country', "select country_id, name from country order by name");
		return $results;
	}
	function get_zones(){
		$results = $this->database->cache('zone', "select * from zone order by country_id, name");
		return $results;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function get_currencies(){
		$results = $this->database->cache('currency', "select * from currency");
		return $results;
	}
	function get_weight_classes(){
		$results = $this->database->cache('weight_class-' . (int)$this->language->getId(), "select weight_class_id, title from weight_class where language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_order_statuses(){
		$results = $this->database->cache('order_status-' . (int)$this->language->getId(), "select order_status_id, name from order_status where language_id = '" . (int)$this->language->getId() . "' order by name");
		return $results;
	}
	function get_country_zones(){
		$results = $this->database->cache('zone-' . (int)$this->request->gethtml('country_id'), "select zone_id, name from zone where country_id = '" . (int)$this->request->gethtml('country_id') . "' order by name");
		return $results;
	}
}
?>