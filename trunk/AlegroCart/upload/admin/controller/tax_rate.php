<?php // TaxRate AlegroCart
class ControllerTaxRate extends Controller {
	var $error = array();
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
		$this->modelTaxRate = $model->get('model_admin_tax_rate');
		
		$this->language->load('controller/tax_rate.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('geo_zone_id', 'post') && $this->validateForm()) {
			$this->modelTaxRate->insert_taxrate();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('geo_zone_id', 'post') && $this->validateForm()) {
			$this->modelTaxRate->update_taxrate();
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		}
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('tax_rate_id')) && ($this->validateDelete())) {
			$this->modelTaxRate->delete_taxrate();
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		}
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function getList() {
		$this->session->set('tax_rate_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_priority'),
			'sort'  => 'tr.priority',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_geo_zone'),
			'sort'  => 'gz.name',
			'align' => 'left'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_rate'),
			'sort'  => 'tr.rate',
			'align' => 'left'
		);
    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);
		
		$results = $this->modelTaxRate->get_page();
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['priority'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => round($result['rate'], 2) . '%',
				'align' => 'left'
			);
			$query = array(
				'tax_rate_id'  => $result['tax_rate_id'],
				'tax_class_id' => $this->request->gethtml('tax_class_id')
			);
			$action = array();
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('tax_rate', 'update', $query)
      		);
			if($this->session->get('enable_delete')){
				$query = array(
					'tax_rate_id'  => $result['tax_rate_id'],
					'tax_class_id' => $this->request->gethtml('tax_class_id'),
					'tax_rate_validation' =>$this->session->get('tax_rate_validation')
				);
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('tax_rate', 'delete', $query)
				);
			}
      		$cell[] = array(
        		'action' => $action,
        		'align'  => 'action'
      		);
			$rows[] = array('cell' => $cell);
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title').'<em>'.$this->modelTaxRate->get_taxclass_name($this->request->gethtml('tax_class_id')).'</em>');
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_previous', $this->language->get('text_previous'));
		$view->set('text_results', $this->modelTaxRate->get_text_results());

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

		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('tax_rate', 'page', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('action_delete', $this->url->ssl('tax_rate', 'enableDelete', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		
		$view->set('previous', $this->url->ssl('tax_class', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));

		$view->set('search', $this->session->get('tax_rate.search')); 
		$view->set('sort', $this->session->get('tax_rate.sort'));
		$view->set('order', $this->session->get('tax_rate.order'));
		$view->set('page', $this->session->get('tax_rate.' . $this->request->gethtml('tax_class_id') . '.page'));
		
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('insert', $this->url->ssl('tax_rate', 'insert', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));

		$view->set('pages', $this->modelTaxRate->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_priority', $this->language->get('entry_priority'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_rate', $this->language->get('entry_rate'));
		$view->set('entry_description', $this->language->get('entry_description'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_priority', @$this->error['priority']);
		$view->set('error_rate', @$this->error['rate']);
		$view->set('error_description', @$this->error['description']);

		$query = array(
			'tax_rate_id'  => $this->request->gethtml('tax_rate_id'),
			'tax_class_id' => $this->request->gethtml('tax_class_id')
		);

		$view->set('action', $this->url->ssl('tax_rate', $this->request->gethtml('action'), $query));
		$view->set('list', $this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('insert', $this->url->ssl('tax_rate', 'insert', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('cancel', $this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));

		if ($this->request->gethtml('tax_rate_id')) {
			$query = array(
				'tax_rate_id'  => $this->request->gethtml('tax_rate_id'),
				'tax_class_id' => $this->request->gethtml('tax_class_id'),
				'tax_rate_validation' =>$this->session->get('tax_rate_validation')
			);
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('tax_rate', 'delete', $query));
		}

		$view->set('tax_name', $this->modelTaxRate->get_taxclass_name($this->request->gethtml('tax_class_id')));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('tax_rate_id')) && (!$this->request->isPost())) {
			$tax_rate_info = $this->modelTaxRate->get_taxrate();
		}

		if ($this->request->has('geo_zone_id', 'post')) {
			$view->set('geo_zone_id', $this->request->gethtml('geo_zone_id', 'post'));
		} else {
			$view->set('geo_zone_id', @$tax_rate_info['geo_zone_id']);
		}

		$view->set('geo_zones', $this->modelTaxRate->get_geozones());

		if ($this->request->has('priority', 'post')) {
			$view->set('priority', $this->request->gethtml('priority', 'post'));
		} else {
			$view->set('priority', @$tax_rate_info['priority']);
		}

		if ($this->request->has('rate', 'post')) {
			$view->set('rate', $this->request->gethtml('rate', 'post'));
		} else {
			$view->set('rate', @$tax_rate_info['rate']);
		}

		if ($this->request->has('description', 'post')) {
			$view->set('description', $this->request->gethtml('description', 'post'));
		} else {
			$view->set('description', @$tax_rate_info['description']);
		}

		return $view->fetch('content/tax_rate.tpl');
	}

	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'tax_rate')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		if (!$this->request->gethtml('priority', 'post')) {
			$this->error['priority'] = $this->language->get('error_priority');
		}
		if (!$this->request->gethtml('rate', 'post')) {
			$this->error['rate'] = $this->language->get('error_rate');
		}
        if (!$this->validate->strlen($this->request->gethtml('description', 'post'),1,255)) {
			$this->error['description'] = $this->language->get('error_description');
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
			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));//**
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));//**
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'tax_rate')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}

	function validateDelete() {
		if(($this->session->get('tax_rate_validation') != $this->request->sanitize('tax_rate_validation')) || (strlen($this->session->get('tax_rate_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('tax_rate_validation');
		if (!$this->user->hasPermission('modify', 'tax_rate')) {
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
			$this->session->set('tax_rate.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('tax_rate.' . $this->request->gethtml('tax_class_id') . '.page', $this->request->gethtml('page', 'post'));			
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('tax_rate.order', (($this->session->get('tax_rate.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('tax_rate.order') == 'asc')) ? 'desc' : 'asc');
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('tax_rate.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
	}		
}
?>
