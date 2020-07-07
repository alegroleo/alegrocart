<?php   //Home AlegroCart
class ControllerHome extends Controller {  
	public $error = array();
	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->response		=& $locator->get('response');
		$this->template		=& $locator->get('template');
		$this->session		=& $locator->get('session');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user');
		$this->modelHome	= $model->get('model_admin_home');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('home');

		$this->language->load('controller/home.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));

		$view = $this->locator->create('template');

		if (is_dir(DIR_BASE . PATH_INSTALL)) {
				$this->error['install_dir'] = $this->language->get('error_install_dir');
			} else {
				$this->error['install_dir'] = '';
			}

		if (strtolower(substr(PHP_OS, 0, 5)) == "linux") { 
			if (substr(decoct(fileperms(DIR_BASE . 'config.php')), 3) != 644) { 
				$this->error['config'] = $this->language->get('error_permission_config');
			}

			if (substr(decoct(fileperms(DIR_BASE . '.htaccess')), 3) != 644) { 
				$this->error['htaccess'] = $this->language->get('error_permission_htaccess');
			}

			if (substr(decoct(fileperms(DIR_BASE . 'robots.txt')), 3) != 644) { 
				$this->error['robots'] = $this->language->get('error_permission_robots');
			}
		}

		$view->set('error_install_dir', @$this->error['install_dir']);
		$view->set('error_config', @$this->error['config']);
		$view->set('error_htaccess', @$this->error['htaccess']);
		$view->set('error_robots', @$this->error['robots']);
		$view->set('error_page_load', !$this->config->get('config_page_load') ? $this->language->get('error_page_load') : NULL);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('help', $this->session->get('help'));

		$view->set('text_stats', $this->language->get('text_stats'));
		$view->set('text_online', $this->language->get('text_online'));
		$view->set('text_customer', $this->language->get('text_customer'));
		$view->set('text_order', $this->language->get('text_order'));
		$view->set('text_product', $this->language->get('text_product'));
		$view->set('text_image', $this->language->get('text_image'));
		$view->set('text_review', $this->language->get('text_review'));
		$view->set('text_language', $this->language->get('text_language'));
		$view->set('text_currency', $this->language->get('text_currency'));
		$view->set('text_country', $this->language->get('text_country'));
		$view->set('text_latest_orders', $this->language->get('text_latest_orders'));
		$view->set('text_latest_reviews', $this->language->get('text_latest_reviews'));

		$view->set('column_order_id', $this->language->get('column_order_id'));
		$view->set('column_customer', $this->language->get('column_customer'));
		$view->set('column_status', $this->language->get('column_status'));
		$view->set('column_date_added', $this->language->get('column_date_added'));
		$view->set('column_total', $this->language->get('column_total'));
		$view->set('column_product', $this->language->get('column_product'));
		$view->set('column_author', $this->language->get('column_author'));
		$view->set('column_avgrating', $this->language->get('column_avgrating'));

		$view->set('online', $this->url->ssl('report_online'));
		$user_info = $this->modelHome->get_people_online();
		$view->set('users', $user_info['total']);

		$view->set('customer', $this->url->ssl('customer'));
		$customer_info = $this->modelHome->get_customers();
		$view->set('customers', $customer_info['total']);
		$view->set('new_customers', $customer_info['new']);
		$view->set('explanation_customer', $this->language->get('explanation_customer'));

		$view->set('order', $this->url->ssl('order'));
		$order_info = $this->modelHome->get_orders();
		$view->set('orders', $order_info['total']);
		$view->set('new_orders', $order_info['new']);
		$view->set('explanation_order', $this->language->get('explanation_order', $this->modelHome->get_defaultStatus()));

		$view->set('product', $this->url->ssl('product'));
		$product_info = $this->modelHome->get_products();
		$view->set('products', $product_info['total']);
		$view->set('active_products', $product_info['active']);
		$view->set('explanation_product', $this->language->get('explanation_product'));

		$view->set('image', $this->url->ssl('image'));
		$image_info = $this->modelHome->get_images();
		$view->set('images', $image_info['total']);

		$view->set('review', $this->url->ssl('review'));
		$review_info = $this->modelHome->get_reviews();
		$view->set('reviews', $review_info['total']);
		$view->set('active_reviews', $review_info['active']);
		$view->set('explanation_review', $this->language->get('explanation_review'));

		$view->set('language', $this->url->ssl('language'));
		$language_info = $this->modelHome->get_languages();
		$view->set('languages', $language_info['total']);
		$view->set('active_languages', $language_info['active']);
		$view->set('explanation_language', $this->language->get('explanation_language'));

		$view->set('currency', $this->url->ssl('currency'));
		$currency_info = $this->modelHome->get_currencies();
		$view->set('currencies', $currency_info['total']);
		$view->set('active_currencies', $currency_info['active']);
		$view->set('explanation_currency', $this->language->get('explanation_currency'));

		$view->set('country', $this->url->ssl('country')); 
		$country_info = $this->modelHome->get_countries();
		$view->set('countries', $country_info['total']);
		$view->set('active_countries', $country_info['active']);
		$view->set('explanation_country', $this->language->get('explanation_country'));

		$order_data = array();
		$results = $this->modelHome->get_latest_orders();
		foreach ($results as $result) {
			$order_data[] = array(
				'order_id'   => $result['order_id'],
				'customer'   => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'href'       => $this->url->ssl('order', 'update', array('order_id' => $result['order_id']))
			);
		}
		if($results){ $this->session->set('order_validation', md5(time()));}
		$view->set('latest_orders', $order_data);

		$review_data = array();
		$results = $this->modelHome->get_latest_reviews();
		foreach ($results as $result) {
			$review_data[] = array(
				'product' => $result['product'],
				'author'  => $result['author'],
				'avgrating'  => number_format((($result['rating1'] + $result['rating2'] + $result['rating3'] + $result['rating4'])/4),1),
				'status'  => $result['status'],
				'href'    => $this->url->ssl('review', 'update', array('review_id' => $result['review_id']))
			);
		}
		if($results){ $this->session->set('review_validation', md5(time()));}
		$view->set('latest_reviews', $review_data);

		$view->set('head_def',$this->head_def); 

		$this->template->set('content', $view->fetch('content/home.tpl')); 

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}
}
?>
