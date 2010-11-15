<?php //Review AlegroCart
class ControllerReview extends Controller {
	function __construct(&$locator){ // Template Manager
		$this->locator		=& $locator;
		$model				=& $locator->get('model');
		$this->config  		=& $locator->get('config');
		$this->module   	=& $locator->get('module');
		$this->template 	=& $locator->get('template');
		$this->modelCore 	= $model->get('model_core');
		$this->modelReview  = $model->get('model_review');
		$this->tpl_manager = $this->modelCore->get_tpl_manager('review'); // Template Manager
		$this->locations = $this->modelCore->get_tpl_locations();// Template Manager
		$this->tpl_columns = $this->modelCore->get_columns();// Template Manager
	}
	function index() {  
		$currency =& $this->locator->get('currency');
		$language =& $this->locator->get('language');
		$image    =& $this->locator->get('image');
		$request  =& $this->locator->get('request');
		$response =& $this->locator->get('response');
		$session  =& $this->locator->get('session');
		$tax      =& $this->locator->get('tax');
		$url      =& $this->locator->get('url');
		$head_def =& $this->locator->get('HeaderDefinition');
		
		//pagination
        $session->set('review.page', $request->has('page')?(int)$request->get('page'):1);

		$language->load('controller/review.php');

		$results = $this->modelReview->get_reviews($session->get('review.page'));
 
    	if ($results) {
      		$view = $this->locator->create('template');
			$tax_included = $this->config->get('config_tax_store');
			$view->set('tax_included', $tax_included);

      		$product_info = $this->modelReview->get_product($request->gethtml('product_id'));
	  
	  		$this->template->set('title', $language->get('heading_title', $product_info['name']));

      		$view->set('heading_title', $language->get('heading_title', $product_info['name']));

      		$view->set('price', $currency->format($tax->calculate($product_info['price'], $product_info['tax_class_id'], $tax_included)));
			
			$view->set('special_price', $product_info['special_price']>0 ? $currency->format($tax->calculate($product_info['special_price'], $product_info['tax_class_id'], $tax_included)): "");
      		$view->set('text_results', $this->modelReview->get_text_results());
      		$view->set('text_review_by', $language->get('text_review_by'));
      		$view->set('text_date_added', $language->get('text_date_added'));
      		$view->set('text_rating', $language->get('text_rating'));

      		$view->set('entry_page', $language->get('entry_page'));

      		$view->set('action', $url->href('review', FALSE, array('product_id' => $request->gethtml('product_id'))));

      		$review_data = array();

      		foreach ($results as $result) {
        		$review_data[] = array(
          			'href'       => $url->href('review_info', FALSE, array('product_id' => $result['product_id'], 'review_id' => $result['review_id'])),
          			'name'       => $result['name'],
          			'thumb'      => $image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
          			'text'       => trim(substr(strip_tags($result['text']), 0, 150)) . '...',
          			'rating'     => $result['rating'],
          			'out_of'     => $language->get('text_out_of', $result['rating']),
          			'author'     => $result['author'],
          			'date_added' => $language->formatDate($language->get('date_format_long'), strtotime($result['date_added']))
        		);
      		}

      		$view->set('reviews', $review_data);

      		$view->set('page', $session->get('review.page'));
      		$view->set('pages', $this->modelReview->get_pagination());
			$view->set('total_pages', $this->modelReview->get_pages());
			$view->set('previous' , $language->get('previous_page'));
			$view->set('next' , $language->get('next_page'));
			$view->set('first_page', $language->get('first_page'));
			$view->set('last_page', $language->get('last_page'));
			$view->set('head_def',$head_def);
			$this->template->set('head_def',$head_def);
	  		$this->template->set('content', $view->fetch('content/review.tpl'));
    	} else {
      		$this->template->set('title', $language->get('text_empty'));
 
      		$view = $this->locator->create('template');
      		$view->set('heading_title', $language->get('text_empty'));
      		$view->set('text_error', $language->get('text_empty'));
      		$view->set('button_continue', $language->get('button_continue'));
      		$view->set('continue', $url->href('home'));
			$view->set('head_def',$head_def);
			$this->template->set('head_def',$head_def);
	  		$this->template->set('content', $view->fetch('content/error.tpl'));
    	}
		
		$this->load_modules();  // Template Manager
		$this->set_tpl_modules(); // Template Manager
		$this->template->set($this->module->fetch());
		$response->set($this->template->fetch('layout.tpl'));
  	}
	
	function load_modules(){ // Template Manager
		$modules = $this->modelCore->merge_modules($this->get_modules_extra());
		foreach ($this->locations as $location){
			if($modules[$location['location']]){
				foreach($modules[$location['location']] as $module){
					$this->template->set($this->module->load($module));
				}
			}
		}
	}
	function get_modules_extra(){// Template Manager (Default Modules specific to current controller)
		foreach($this->locations as $location){
			$modules_extra[$location['location']] = array();
		}
		$modules_extra['column'] = array('manufacturer', 'popular');
		$modules_extra['columnright'] = array('specials');
		return $modules_extra;
	}

	function set_tpl_modules(){ // Template Manager
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
}
?>
