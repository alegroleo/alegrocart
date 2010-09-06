<?php   //Home AlegroCart
class ControllerHome extends Controller {  
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->currency 	=& $locator->get('currency');
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelHome = $model->get('model_admin_home');
		
		$this->language->load('controller/home.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		
    	$view = $this->locator->create('template');

    	$view->set('heading_title', $this->language->get('heading_title'));
    	$view->set('heading_description', $this->language->get('heading_description'));

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
    	$view->set('column_rating', $this->language->get('column_rating'));

    	$view->set('online', $this->url->ssl('report_online'));
        $user_info = $this->modelHome->get_people_online();
    	$view->set('users', $user_info['total']);

    	$view->set('customer', $this->url->ssl('customer'));
    	$customer_info = $this->modelHome->get_customers();
		$view->set('customers', $customer_info['total']);

    	$view->set('order', $this->url->ssl('order'));
    	$order_info = $this->modelHome->get_orders();
    	$view->set('orders', $order_info['total']);

    	$view->set('product', $this->url->ssl('product'));
    	$product_info = $this->modelHome->get_products();
    	$view->set('products', $product_info['total']);
 
    	$view->set('image', $this->url->ssl('image'));
    	$image_info = $this->modelHome->get_images();
    	$view->set('images', $image_info['total']);

    	$view->set('review', $this->url->ssl('review'));
    	$review_info = $this->modelHome->get_reviews();
    	$view->set('reviews', $review_info['total']);

    	$view->set('language', $this->url->ssl('language'));
    	$language_info = $this->modelHome->get_languages();
    	$view->set('languages', $language_info['total']);

    	$view->set('currency', $this->url->ssl('currency'));
    	$currency_info = $this->modelHome->get_currencies();
    	$view->set('currencies', $currency_info['total']);

    	$view->set('country', $this->url->ssl('country')); 
    	$country_info = $this->modelHome->get_countries();
    	$view->set('countries', $country_info['total']);

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
    	$view->set('latest_orders', $order_data);

    	$review_data = array();
    	$results = $this->modelHome->get_latest_reviews();
    	foreach ($results as $result) {
      		$review_data[] = array(
        		'product' => $result['product'],
        		'author'  => $result['author'],
        		'rating'  => $result['rating'],
        		'status'  => $result['status'],
        		'href'    => $this->url->ssl('review', 'update', array('review_id' => $result['review_id'])),
      		);
    	}
    	$view->set('latest_reviews', $review_data);

		$this->template->set('content', $view->fetch('content/home.tpl')); 
	
		$this->template->set($this->module->fetch());

	
		$this->response->set($this->template->fetch('layout.tpl'));  
  	}
}
?>
