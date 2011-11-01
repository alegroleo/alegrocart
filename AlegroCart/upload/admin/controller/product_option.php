<?php //Admin Product to Option AlegroCart
class ControllerProductOption extends Controller {
	var $error = array();
	var $option_status = FALSE;
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->cache    	=& $locator->get('cache');
		$this->config   	=& $locator->get('config');
		$this->currency 	=& $locator->get('currency');
		$this->image    	=& $locator->get('image');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelOption = $model->get('model_admin_productoption');
		$this->modelProduct = $model->get('model_admin_product');
		
		$this->language->load('controller/product_option.php');
	}
	
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());
		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('product_id') && $this->validateForm()) {
			$this->modelOption->insert_option();
			$this->refreshOptions();
			$this->cache->delete('product');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
		}

		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));
		if ($this->request->isPost() && $this->request->has('product_id') && $this->validateForm()) {
			$this->modelOption->update_option();
			$this->refreshOptions();
			$this->cache->delete('product');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));
		if (($this->request->gethtml('product_id')) && ($this->validateDelete())) {
			$this->modelOption->delete_option();
			$this->refreshOptions();
			$this->cache->delete('product');
			$this->session->set('message', $this->language->get('text_message'));
			$this->response->redirect($this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
		}

		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
		$this->session->set('product_option_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_option'),
			'sort'  => 'o.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_value'),
			'sort'  => 'ov.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_prefix'),
			'sort'  => 'p2o.prefix',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_price'),
			'sort'  => 'p2o.price',
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_weight'),
			'sort'  => 'p2o.option_weight',
			'align' => 'right'
		);
		$cols[] = array(
             'name'  => $this->language->get('column_weight_class'),
             'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_sort_order'),
			'sort'  => 'p2o.sort_order',
			'align' => 'right'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		$results = $this->modelOption->get_page();

		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['option'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['option_value'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['prefix'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['price'],
				'align' => 'right'
			);
			$cell[] = array(
				'value' => $result['option_weight'],
				'align' => 'right'
			);
			$cell[] = array(
               'value' => $this->modelProduct->get_weight_class($result['option_weightclass_id']),
               'align' => 'right'
            );
			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right'
			);
			$query = array(
				'product_to_option_id' => $result['product_to_option_id'],
				'product_id'           => $this->request->gethtml('product_id')
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('product_option', 'update', $query)
      		);
			
			if($this->session->get('enable_delete')){
				$query = array(
				'product_to_option_id' => $result['product_to_option_id'],
				'product_id'           => $this->request->gethtml('product_id'),
				'product_option_validation' =>$this->session->get('product_option_validation')
			);
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('product_option', 'delete', $query)
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

		$view->set('text_previous', $this->language->get('text_previous'));
		$view->set('text_results', $this->modelOption->get_text_results());
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

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('product_option', 'page', array('product_id' => $this->request->gethtml('product_id'))));
		$view->set('action_delete', $this->url->ssl('product_option', 'enableDelete', array('product_id' => $this->request->gethtml('product_id'))));

		$view->set('previous', $this->url->ssl('product', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
 
		$view->set('search', $this->session->get('product_option.search'));
		$view->set('sort', $this->session->get('product_option.sort'));
		$view->set('order', $this->session->get('product_option.order'));
		$view->set('page', $this->session->get('product_option.' . $this->request->gethtml('product_id') . '.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
		$view->set('insert', $this->url->ssl('product_option', 'insert', array('product_id' => $this->request->gethtml('product_id'))));

    	$view->set('pages', $this->modelOption->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_plus', $this->language->get('text_plus'));
		$view->set('text_minus', $this->language->get('text_minus'));

		$view->set('entry_option', $this->language->get('entry_option'));
		$view->set('entry_prefix', $this->language->get('entry_prefix'));
		$view->set('entry_price', $this->language->get('entry_price'));
		$view->set('entry_option_weight', $this->language->get('entry_option_weight'));
		$view->set('entry_weight_class', $this->language->get('entry_weight_class'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);

		$query = array(
			'product_to_option_id' => $this->request->gethtml('product_to_option_id'),
			'product_id'           => $this->request->gethtml('product_id')
		);

		$view->set('action', $this->url->ssl('product_option', $this->request->gethtml('action'), $query));

		$view->set('list', $this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));

		$view->set('insert', $this->url->ssl('product_option', 'insert', array('product_id' => $this->request->gethtml('product_id'))));
		$view->set('cancel', $this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));

		if ($this->request->gethtml('product_to_option_id')) {
			$query = array(
				'product_to_option_id' => $this->request->gethtml('product_to_option_id'),
				'product_id'           => $this->request->gethtml('product_id'),
				'product_option_validation' =>$this->session->get('product_option_validation')
			);
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('product_option', 'delete', $query));
		}
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$results = $this->modelProduct->get_options($this->request->gethtml('product_id'));
		$products_options = array();
		foreach($results as $result){
			$products_options[] = $result['option_id'];
		}
		$option_count = count($products_options);
		
		$option_data = array();
		$options = $this->modelOption->get_options();
		foreach ($options as $option) {
			if($option_count < 3 || in_array($option['option_id'], $products_options)){
				$option_value_data = array();
				$option_values = $this->modelOption->get_option_values($option['option_id']);
				foreach ($option_values as $option_value) {
					$option_value_data[] = array(
						'option_value_id' => $option_value['option_id'] . ':' . $option_value['option_value_id'],
						'name'            => $option_value['name'],
					);
				}

				$option_data[] = array(
					'option_id' => $option['option_id'],
					'name'      => $option['name'],
					'value'     => $option_value_data
				);
			}
		}

		$view->set('options', $option_data);

		if (($this->request->gethtml('product_to_option_id')) && (!$this->request->isPost())) {
			$product_option_info = $this->modelOption->get_product_option();
		}

		if ($this->request->has('prefix', 'post')) {
			$view->set('prefix', $this->request->gethtml('prefix', 'post'));
		} else {
			$view->set('prefix', @$product_option_info['prefix']);
		}

		if ($this->request->has('price', 'post')) {
			$view->set('price', $this->request->gethtml('price', 'post'));
		} else {
			$view->set('price', @$product_option_info['price']);
		}
		
		if ($this->request->has('option_weight', 'post')) {
			$view->set('option_weight', $this->request->gethtml('option_weight', 'post'));
		} else {
			$view->set('option_weight', @$product_option_info['option_weight']);
		}
		
		if ($this->request->has('option_weightclass_id', 'post')) {
			$view->set('option_weightclass_id', $this->request->gethtml('option_weightclass_id', 'post'));
		} else {
			$view->set('option_weightclass_id', @$product_option_info['option_weightclass_id']);
		}
		
		$view->set('weight_classes', $this->modelProduct->get_weight_classes());

		if ($this->request->has('sort_order', 'post')) {
			$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$product_option_info['sort_order']);
		}

		if ($this->request->has('option', 'post')) {
			$view->set('option_value_id', $this->request->gethtml('option', 'post'));
		} else {
			$view->set('option_value_id', @$product_option_info['option_id'] . ':' . @$product_option_info['option_value_id']);
		}

		return $view->fetch('content/product_option.tpl');
	}
	
	private function refreshOptions(){
		$product_options = $this->get_option_values();
		$this->modelProduct->delete_options($this->request->gethtml('product_id'));
		foreach($product_options as $product_option){
			$this->modelOption->insert_product_options($product_option);
		}
	}
	
	private function get_options(){
		$results = $this->modelProduct->get_options($this->request->gethtml('product_id'));
		$options = array();
		foreach($results as $result){
			$options[] = array(
				'option_id'	=> $result['option_id']
			);
		}
		$this->option_status = $options ? TRUE : FALSE;
		return $options;
	}
	private function get_product_options(){
		$results = $this->modelProduct->get_product_options();
		$product_options = array();
		foreach($results as $result){
			$product_options[$result['product_option']] = array(
				'product_id'		=> $result['product_id'],
				'product_option'	=> $result['product_option'],
				'quantity'			=> $result['quantity'],
				'barcode'			=> $result['barcode'],
				'image_id'			=> $result['image_id'],
				'dimension_id'		=> $result['dimension_id'],
				'dimension_value'	=> $result['dimension_value'],
				'model_number' 		=> $result['model_number']
			);
		}
		return $product_options;
	}
	private function get_option_values(){
		$product_id =$this->request->gethtml('product_id');
		$results = $this->modelProduct->get_option_values();
		$option_values = array();
		$product_options = $this->get_product_options();
		foreach($results as $result){
			$option_values[$result['option_id']][$result['option_value_id']] = array(
			    'product_to_option_id' => $result['product_to_option_id'],
				'option_value_id'	=> $result['option_value_id'],
				'option_id'			=> $result['option_id'],
				'name'				=> $result['name']
			);
		}
		$options = $this->get_options();
		$num_options = count($options);
		$i = 1;
		if ($num_options >= 1){
			foreach($option_values[$options[0]['option_id']] as $first_level){

				if($num_options == 1){
					$option_key = $this->request->gethtml('product_id') . ':' . $first_level['product_to_option_id'];
					$option_list[$option_key] = array(
						'product_id'		=> $product_id,
						'product_option' 	=> $option_key,	
						'quantity'			=> array_key_exists($option_key, $product_options) ? $product_options[$option_key]['quantity'] : 0,
						'barcode'		=> array_key_exists($option_key, $product_options) ? $product_options[$option_key]['barcode'] : '',
						'image_id'			=> array_key_exists($option_key, $product_options) ? $product_options[$option_key]['image_id'] : 0,
						'dimension_id'		=> array_key_exists($option_key, $product_options) ? $product_options[$option_key]['dimension_id'] : 0,
						'dimension_value'	=> array_key_exists($option_key, $product_options) ? $product_options[$option_key]['dimension_value'] : '0:0:0',
						'model_number'   	=> array_key_exists($option_key, $product_options) ? $product_options[$option_key]['model_number'] : ''
					);
				} else {
					foreach($option_values[$options[1]['option_id']] as $second_level){
						if($num_options == 2){
							$option_key = $this->request->gethtml('product_id') . ':' . $first_level['product_to_option_id'] . '.' . $second_level['product_to_option_id'];
							$match = $this->compare_key($product_options, $option_key);
							$option_list[$option_key] = array(
								'product_id'		=> $product_id,
								'product_option' 	=> $option_key,	
								'quantity'			=> $match ? $product_options[$match]['quantity'] : 0,
								'barcode'		=> $match ? $product_options[$match]['barcode'] : '',
								'image_id'			=> $match ? $product_options[$match]['image_id'] : 0,
								'dimension_id'		=> $match ? $product_options[$match]['dimension_id'] : 0,
								'dimension_value'	=> $match ? $product_options[$match]['dimension_value'] : '0:0:0',
								'model_number'   	=> $match ? $product_options[$match]['model_number'] : ''
							);
							
						} else {
							foreach($option_values[$options[2]['option_id']] as $third_level){
								$option_key = $this->request->gethtml('product_id') . ':' . $first_level['product_to_option_id'] . '.' . $second_level['product_to_option_id'] . '.' . $third_level['product_to_option_id'];
								$match = $this->compare_key($product_options, $option_key);
								$option_list[$option_key] = array(
									'product_id'		=> $product_id,
									'product_option' 	=> $option_key,	
									'quantity'			=> $match ? $product_options[$match]['quantity'] : 0,
									'barcode'		=> $match ? $product_options[$match]['barcode'] : '',
									'image_id'			=> $match ? $product_options[$match]['image_id'] : 0,
									'dimension_id'		=> $match ? $product_options[$match]['dimension_id'] : 0,
									'dimension_value'	=> $match ? $product_options[$match]['dimension_value'] : '0:0:0',
									'model_number'   	=> $match ? $product_options[$match]['model_number'] : ''
								);
							}
						}
					}		
				}
			}
		} else {
			return FALSE;
		}
		return $option_list;
	}
	private function compare_key($product_options, $option_key){
		$options = explode('.', substr($option_key,strpos($option_key, ':' ) + 1));
		foreach($product_options as $product_option){
			$product_keys = explode('.', substr($product_option['product_option'],strpos($product_option['product_option'], ':' ) + 1));
			foreach($product_keys as $product_key){
				if(!in_array($product_key, $options)){
					$match = FALSE;
				   break;
				} else {
					$match = TRUE;
				}
			}
			if ($match == TRUE){
				return $product_option['product_option'];
			}
		}
		return FALSE;
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'product_option')) {
			$this->error['message'] = $this->language->get('error_permission');
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
			$this->response->redirect($this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
		}
	}
	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'product_option')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
	private function validateDelete() {
		if(($this->session->get('product_option_validation') != $this->request->sanitize('product_option_validation')) || (strlen($this->session->get('product_option_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('product_option_validation');
		if (!$this->user->hasPermission('modify', 'product_option')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
		
	function page() {
		if ($this->request->has('search', 'post')) {
			$this->session->set('product_option.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('product_option.' . $this->request->gethtml('product_id') . '.page', $this->request->gethtml('page', 'post'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('product_option.order', (($this->session->get('product_option.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('product_option.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('product_option.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('product_option', FALSE, array('product_id' => $this->request->gethtml('product_id'))));
	}	
}
?>