<?php
class ModuleMenu extends Controller {
	var $menu_info = array();
	var $image_path = "javascript/ACMenu/image/";
	function fetch() {
		$config   =& $this->locator->get('config');
		$language =& $this->locator->get('language');
		$session  =& $this->locator->get('session');
		$url      =& $this->locator->get('url');
		$user     =& $this->locator->get('user');

		if (($user->isLogged()) && ($config->get('menu_status'))) {
			$language->load('extension/module/menu.php');

			$view = $this->locator->create('template');

			// new code
			$menu = array('system', 'configuration', 'catalog', 'commerce', 'extension', 'customers', 'reports');
			$submenu1[$menu[0]]= array('home', 'shop', 'maintenance', 'backup', 'server_info', 'logout');
			$submenu1[$menu[1]]= array('setting', 'users', 'localisation', 'url_alias', 'homepage', 'last_homepage', 'template_manager', 'last_template_manager', 'image_display', 'last_image_display', 'minov');
			$submenu2[$submenu1[$menu[1]][1]] = array('user', 'usergroup');
			$submenu2[$submenu1[$menu[1]][2]] = array('language', 'currency', 'order_status', 'country' ,'zone', 'geo_zone', 'tax_class', 'weight_class', 'dimension_class');
			$submenu1[$menu[2]]= array('category', 'last_category', 'product', 'last_product', 'products_with_options', 'last_products_with_options', 'option', 'last_option', 'last_option_value', 'image', 'last_image', 'watermark', 'download', 'last_download', 'review', 'last_review', 'information', 'last_information');
			$submenu1[$menu[3]]= array('manufacturer', 'last_manufacturer', 'vendor', 'last_vendor');
			$submenu1[$menu[4]]= array('module', 'last_module', 'shipping', 'last_shipping', 'payment', 'last_payment', 'calculate', 'last_calculate');
			$submenu1[$menu[5]]= array('customer', 'last_customer', 'order', 'last_order', 'order_edit', 'coupon', 'last_coupon', 'mail', 'newsletter', 'last_newsletter');
			$submenu1[$menu[6]]= array('report_online', 'report_sale', 'report_viewed', 'report_purchased', 'report_logs');

			foreach($menu as $key => $name){
				$text = $language->get('text_' . $name);
				$href = '';
				$new_tab = false;
				$last = false;
				$this->create_menu(NULL, $name, $text, $href, 0, $new_tab, $last);
				if(isset($submenu1[$name])){
					foreach($submenu1[$name] as $key1 => $s1name){
						if(strpos($s1name, 'last_') === 0) {
							if ($session->has($s1name)){
								$text =  $session->get('name_'.$s1name);
								$last = true;
							} else {
								continue;
							}
						} else {
							$text =  $language->get('text_' . $s1name);
							$last = false;
						}
						if(isset($submenu2[$s1name])){
							$href= NULL;
							$new_tab= false;
						} else if($name == 'system' && $s1name == 'shop'){
							$href = HTTP_CATALOG;
							$new_tab = true;
						} else if($name == 'extension') {
							$href = $last ? $url->ssl($session->get($s1name)) : $url->rawssl($name, false, array('type' => $s1name));
							$new_tab = false;
						} else {
							$href = $last ? $session->get($s1name) : $url->rawssl($s1name);
							$new_tab = false;
						}
						$this->create_menu($name, $s1name, $text, $href, 1, $new_tab, $last);
						if(isset($submenu2[$s1name])){
							foreach($submenu2[$s1name] as $s2name){
								$text = $language->get('text_' . $s2name);
								$href = $url->rawssl($s2name);
								$this->create_menu($s1name, $s2name, $text, $href, 2, $new_tab, $last);
							}
						}
					}
				}
			}

			$view->set('menus', $this->menu_info);
		return $view->fetch('module/menu.tpl');
		}
	}
	function create_menu($parent, $menu_key, $name, $href, $level, $new_tab, $last){
		if($level == 0){
			$type = "block";
			$status = "enabled";
			$image = '';
		} else {
			$type = "none";
			$status = "disabled";
			$image = $last ? '' : $this->image_path . $menu_key . '.png';
		}

		$this->menu_info[] = array(
			'parent'	=> $parent,
			'name'		=> $name,
			'href'		=> $href,
			'level'		=> $level,
			'image'		=> $image,
			'status' 	=> $status,
			'state' 	=> '',
			'type'   	=> $type,
			'class'		=> $last ? 'last menu_lvl_' . $level : 'menu_lvl_' . $level,
			'id'		=> 'menu_level_' . $level,
			'new_tab'	=> $new_tab,
			'last'		=> $last
		);
	}
}
?>
