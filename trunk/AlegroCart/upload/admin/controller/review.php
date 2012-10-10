<?php // Review AlegroCart
class ControllerReview extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->config   	=& $locator->get('config');
		$this->image    	=& $locator->get('image');   
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user'); 
		$this->validate 	=& $locator->get('validate');
		$this->modelReview = $model->get('model_admin_review');
		
		$this->language->load('controller/review.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	} 

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('author', 'post') && $this->validateForm()) {
			$this->modelReview->insert_review();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('review'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('author', 'post') && $this->validateForm()) {
			$this->modelReview->update_review();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('review'));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('review_id')) && ($this->validateDelete())) {
			$this->modelReview->delete_review();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('review'));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function changeStatus() { 
		
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeStatus()) {

			$this->modelReview->change_review_status($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
		}
	
	}

	function getList() {
		$this->session->set('review_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_product'),
			'sort'  => 'pd.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_author'),
			'sort'  => 'r.author',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating1'),
			'sort'  => 'r.rating1',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating2'),
			'sort'  => 'r.rating2',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating3'),
			'sort'  => 'r.rating3',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rating4'),
			'sort'  => 'r.rating4',
			'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_status'),
			'sort'  => 'r.status',
			'align' => 'center'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelReview->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['author'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['rating1'],
				'align' => 'center'
			);
			$cell[] = array(
				'value' => $result['rating2'],
				'align' => 'center'
			);
			$cell[] = array(
				'value' => $result['rating3'],
				'align' => 'center'
			);
			$cell[] = array(
				'value' => $result['rating4'],
				'align' => 'center'
			);

			if ($this->validateChangeStatus()) {
			$cell[] = array(
				'status'  => $result['status'],
				'text' => $this->language->get('button_status'),
				'align' => 'center',
				'status_id' => $result['review_id'],
				'status_controller' => 'review'
			);

			} else {

			$cell[] = array(
				'icon'  => ($result['status'] ? 'enabled.png' : 'disabled.png'),
				'align' => 'center'
			);
			}

			

			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('review', 'update', array('review_id' => $result['review_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('review', 'delete', array('review_id' => $result['review_id'],'review_validation' =>$this->session->get('review_validation')))
				);
			}

      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'action'
      		);
			
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_results', $this->modelReview->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));
 
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_status', $this->language->get('button_status'));

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('review', 'page'));
		$view->set('action_delete', $this->url->ssl('review', 'enableDelete'));

		$view->set('search', $this->session->get('review.search'));
		$view->set('sort', $this->session->get('review.sort'));
		$view->set('order', $this->session->get('review.order'));
		$view->set('page', $this->session->get('review.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('review'));
		$view->set('insert', $this->url->ssl('review', 'insert'));

		$view->set('pages', $this->modelReview->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));

		$view->set('entry_product', $this->language->get('entry_product'));
		$view->set('entry_author', $this->language->get('entry_author'));
		$view->set('entry_rating1', $this->language->get('entry_rating1'));
		$view->set('entry_rating2', $this->language->get('entry_rating2'));
		$view->set('entry_rating3', $this->language->get('entry_rating3'));
		$view->set('entry_rating4', $this->language->get('entry_rating4'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_text', $this->language->get('entry_text'));
		$view->set('entry_good', $this->language->get('entry_good'));
		$view->set('entry_bad', $this->language->get('entry_bad'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_author', @$this->error['author']);
		$view->set('error_text', @$this->error['text']);

		$view->set('action', $this->url->ssl('review', $this->request->gethtml('action'), array('review_id' => $this->request->gethtml('review_id'))));

		$view->set('list', $this->url->ssl('review'));
		$view->set('insert', $this->url->ssl('review', 'insert'));
		$view->set('cancel', $this->url->ssl('review'));

		if ($this->request->gethtml('review_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('review', 'delete', array('review_id' => (int)$this->request->gethtml('review_id'),'review_validation' =>$this->session->get('review_validation'))));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('review_id')) && (!$this->request->isPost())) {
			$review_info = $this->modelReview->get_review();
		}

		if ($this->request->has('product_id', 'post')) {
			$view->set('product_id', $this->request->gethtml('product_id', 'post'));
		} else {
			$view->set('product_id', @$review_info['product_id']);
		}

		$product_data = array();
		$results=$this->modelReview->get_products();
		foreach ($results as $result) {
		$product_data[] = array(
        		'product_id' => $result['product_id'],
			'previewimage' => $this->image->resize($result['filename'], $this->config->get('config_image_width'), $this->config->get('config_image_height')),
        		'name'        => $result['name']
			);
		}
    		$view->set('products', $product_data);

		if ($this->request->has('author', 'post')) {
			$view->set('author', $this->request->gethtml('author', 'post'));
		} else {
			$view->set('author', @$review_info['author']);
		}

		if ($this->request->has('text', 'post')) {
			$view->set('text', $this->request->gethtml('text', 'post'));
		} else {
			$view->set('text', @$review_info['text']);
		}

		if ($this->request->has('rating1', 'post')) {
			$view->set('rating1', $this->request->gethtml('rating1', 'post'));
		} else {
			$view->set('rating1', @$review_info['rating1']);
		}

		if ($this->request->has('rating2', 'post')) {
			$view->set('rating2', $this->request->gethtml('rating2', 'post'));
		} else {
			$view->set('rating2', @$review_info['rating2']);
		}

		if ($this->request->has('rating3', 'post')) {
			$view->set('rating3', $this->request->gethtml('rating3', 'post'));
		} else {
			$view->set('rating3', @$review_info['rating3']);
		}

		if ($this->request->has('rating4', 'post')) {
			$view->set('rating4', $this->request->gethtml('rating4', 'post'));
		} else {
			$view->set('rating4', @$review_info['rating4']);
		}
		
		if ($this->request->has('status', 'post')) {
			$view->set('status', $this->request->gethtml('status', 'post'));
		} else {
			$view->set('status', @$review_info['status']);
		}

		return $view->fetch('content/review.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'review')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

        if (!$this->validate->strlen($this->request->gethtml('author', 'post'),1,64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

        if (!$this->validate->strlen($this->request->gethtml('text', 'post'),1,1000)) {
			$this->error['text'] = $this->language->get('error_text');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function enableDelete(){
		$this->template->set('title', $this->language->get('heading_title'));
		if($this->validateEnableDelete()){
			if($this->session->get('enable_delete')){
				$this->session->delete('enable_delete');
			} else {
				$this->session->set('enable_delete', TRUE);
			}
			$this->response->redirect($this->url->ssl('review'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('review'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'review')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('review_validation') != $this->request->sanitize('review_validation')) || (strlen($this->session->get('review_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('review_validation');
		if (!$this->user->hasPermission('modify', 'review')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validateChangeStatus(){
		if (!$this->user->hasPermission('modify', 'review')) {
	      		return FALSE;
	    	}  else {
			return TRUE;
		}
	}

	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('review.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('review.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('review.order', (($this->session->get('review.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('review.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('review.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('review'));
	}	
}
?>
