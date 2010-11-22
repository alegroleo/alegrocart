<?php // Admin Generate SEO AlegroCart 
class ControllerGenerateUrlAlias extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->generate_seo =& $locator->get('generateseo');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->user    	 	=& $locator->get('user');
		$this->validate 	=& $locator->get('validate');
		$this->modelAdminSEO = $model->get('model_admin_seo');
		$this->language->load('controller/generate_url_alias.php');
	}

	function index() {

		if($this->validate_seo()){
			$this->generate();
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('url_alias'));
		}
	}

	function generate() {
		$this->modelAdminSEO->delete();

		$this->home();
		$this->accounts();
		$this->checkout();
		$this->information();
		$this->categories();
		$this->product_to_category();
		$this->product();
		$this->manufacturer();
		$this->manufacturer_to_product();
		$this->review();
		$this->product_to_review();

		$this->cache->delete('url');
		$this->session->set('message', $this->language->get('text_message'));
		$this->response->redirect($this->url->ssl('url_alias'));
	}
	function review(){
		$results = $this->modelAdminSEO->review_product_id();
		if($results){
			foreach($results as $result){
				$language_id = (int)$this->language->getId();
				$product_name = $this->modelAdminSEO->get_product_name($result['product_id'], $language_id);
				$alias = '';
				$query_path = 'controller=review&product_id='.$result['product_id'];
				$alias = 'review/'.$this->generate_seo->clean_alias($product_name['product_name']).'.html';
				$this->generate_seo->_insert_url_alias($query_path, $alias);
				$query_path = 'controller=review_write&product_id='.$result['product_id'];
				$alias = 'write-review/'.$this->generate_seo->clean_alias($product_name['product_name']).'.html';
				$this->generate_seo->_insert_url_alias($query_path, $alias);
			}
		}
	}
	function product_to_review(){
		$products = $this->modelAdminSEO->review_product_id();
		if($products){
			foreach($products as $product){
				$language_id = (int)$this->language->getId();
				$product_name = $this->modelAdminSEO->get_product_name($product['product_id'], $language_id);
				$reviews = $this->modelAdminSEO->get_reviews($product['product_id']);
				foreach($reviews as $review){
					$alias = '';
					$query_path = 'controller=review_info&product_id='.$product['product_id'].'&review_id='.$review['review_id'];
					$alias = $this->generate_seo->clean_alias($product_name['product_name']). '/';
					$alias .= 'review-'.$review['review_id'].'.html';
					$this->generate_seo->_insert_url_alias($query_path, $alias);
				}
			}
		}
	}
	function manufacturer(){
		$sql = "select manufacturer_id, name from manufacturer";
		$this->generate_seo->_generate_url_alias($sql, 'controller=manufacturer&manufacturer_id={0}', array('manufacturer_id'), array('name'));
	}
	function manufacturer_to_product(){
		$results = $this->modelAdminSEO->get_manufacturers();
		foreach ($results as $result){
			$alias = '';
			$language_id = (int)$this->language->getId();
			$product_name = $this->modelAdminSEO->get_product_name($result['product_id'],$language_id);
			$query_path = 'controller=product&manufacturer_id=' . $result['manufacturer_id'] . '&product_id=' . $result['product_id'];
			$alias = $this->generate_seo->clean_alias($result['name']). '/';
			$alias .= $this->generate_seo->clean_alias($product_name['product_name']);
			$alias .= '.html';
			$this->generate_seo->_insert_url_alias($query_path, $alias);
		}
	}
	function product(){
		$results = $this->modelAdminSEO->get_product_names($this->language->getId());
        $alias= '';
        foreach ($results as $result)
        {
            $alias = '';
            $alias .= $this->generate_seo->clean_alias($result['product_name']);
            $query_path = 'controller=product&product_id=' . $result['product_id'];
            $alias .= '.html';
            $this->generate_seo->_insert_url_alias($query_path, $alias);
        }
	}
	function product_to_category(){ 
        $results = $this->modelAdminSEO->category_product($this->language->getId());
        $alias= '';
        foreach ($results as $result){
            $alias = ''; 
            $language_id = (int)$this->language->getId(); 
            $categories = explode('_', $result['path']);
            foreach ($categories as $category_id){
				$cat_name = $this->modelAdminSEO->category_name($category_id, $language_id);
                $alias .= $this->generate_seo->clean_alias($cat_name['name']);
                $alias .= '/';
            }
			$prod_name = $this->modelAdminSEO->get_product_name($result['product_id'],$language_id);
            $query_path = 'controller=product&path=' . $result['path'] . '&product_id=' . $result['product_id'];
            $alias .= $this->generate_seo->clean_alias($prod_name['product_name']);
            $alias .= '.html';
            $this->generate_seo->_insert_url_alias($query_path, $alias);
        }
	}
	function categories(){
        $results = $this->modelAdminSEO->get_gategories((int)$this->language->getId());
        $alias= '';
        foreach ($results as $result){
            $alias = '';
            $language_id = (int)$this->language->getId();
            $categories = explode('_', $result['path']);
            foreach ($categories as $category_id){
                $cat_name = $this->modelAdminSEO->category_name($category_id, $language_id);
                $alias .= $this->generate_seo->clean_alias($cat_name['name']);
                $alias .= '/';
            }
            $query_path = 'controller=category&path=' . $result['path'];
			$alias = rtrim($alias, '/');
			$alias .= '.html';
            $this->generate_seo->_insert_url_alias($query_path, $alias);
        }
	}
	function information(){
		$sql = "select information_id, title from information_description";
		$this->generate_seo->_generate_url_alias($sql, 'controller=information&information_id={0}', array('information_id'), array('title'));	
	}
	function checkout(){
		$this->generate_seo->_insert_url_alias('controller=cart', $this->language->get('cart_url'));
		$this->generate_seo->_insert_url_alias('controller=checkout_shipping', $this->language->get('checkout_url'));
		$this->generate_seo->_insert_url_alias('controller=checkout_address&action=shipping', $this->language->get('change_shipping_url'));
		$this->generate_seo->_insert_url_alias('controller=checkout_payment', $this->language->get('payment_url'));
		$this->generate_seo->_insert_url_alias('controller=checkout_address&action=payment', $this->language->get('change_payment_url'));
		$this->generate_seo->_insert_url_alias('controller=checkout_confirm', $this->language->get('confirm_url'));
		$this->generate_seo->_insert_url_alias('controller=checkout_success', $this->language->get('checkout_success_url'));
	}
	function home(){
		$this->generate_seo->_insert_url_alias('controller=home', $this->language->get('index_url'));
		$this->generate_seo->_insert_url_alias('controller=contact', $this->language->get('contact_url'));
		$this->generate_seo->_insert_url_alias('controller=sitemap', $this->language->get('sitemap_url'));
		$this->generate_seo->_insert_url_alias('controller=search', $this->language->get('search_url'));
	}
	function accounts(){
		$this->generate_seo->_insert_url_alias('controller=account', $this->language->get('account_url'));
		$this->generate_seo->_insert_url_alias('controller=account_login', $this->language->get('login_url'));
		$this->generate_seo->_insert_url_alias('controller=account_logout', $this->language->get('logout_url'));
		$this->generate_seo->_insert_url_alias('controller=account_create', $this->language->get('create_url'));
		$this->generate_seo->_insert_url_alias('controller=account_success', $this->language->get('acct_success_url'));
		$this->generate_seo->_insert_url_alias('controller=account_download', $this->language->get('downloads_url'));
		$this->generate_seo->_insert_url_alias('controller=account_edit', $this->language->get('acct_edit_url'));
		$this->generate_seo->_insert_url_alias('controller=account_forgotten', $this->language->get('forgotten_url'));
		$this->generate_seo->_insert_url_alias('controller=account_password', $this->language->get('password_url'));
		$this->generate_seo->_insert_url_alias('controller=account_address', $this->language->get('address_url'));
		$this->generate_seo->_insert_url_alias('controller=account_history', $this->language->get('history_url'));	
	}
	function validate_seo() {
		if (!$this->user->hasPermission('modify', 'url_alias')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->config->get('config_url_alias')){
		    $this->error['message'] = $this->language->get('error_url_alias');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>