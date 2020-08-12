<?php // TaxRate AlegroCart
class ControllerTaxRate extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->config		=& $locator->get('config');
		$this->currency		=& $locator->get('currency');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user'); 
		$this->validate		=& $locator->get('validate');
		$this->modelTaxRate	= $model->get('model_admin_tax_rate');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('tax_rate');

		$this->language->load('controller/tax_rate.php');
	}

	protected function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('geo_zone_id', 'post') && $this->validateForm()) {
			$this->modelTaxRate->insert_taxrate();
			$insert_id = $this->modelTaxRate->get_last_id();
			$this->session->set('last_tax_rate_id', $insert_id);
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('geo_zone_id', 'post') && $this->validateForm() && $this->validateModification()) {
			$this->modelTaxRate->update_taxrate();
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('tax_rate', 'update', array('tax_rate_id' => $this->request->gethtml('tax_rate_id'), 'tax_class_id' => $this->request->gethtml('tax_class_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('tax_rate_id')) && ($this->validateDelete())) {
			$this->modelTaxRate->delete_taxrate();
			$this->session->set('message', $this->language->get('text_message'));
			
			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	private function getList() {
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
			'name'  => $this->language->get('column_description'),
			'sort'  => 'tr.description',
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
			$last = $result['tax_rate_id'] == $this->session->get('last_tax_rate_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['priority'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['name'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => $result['description'],
				'align' => 'left',
				'last' => $last
			);
			$cell[] = array(
				'value' => round($result['rate'], 2) . '%',
				'align' => 'left',
				'last' => $last
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
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title').'<em>'.$this->modelTaxRate->get_taxclass_name($this->request->gethtml('tax_class_id')).'</em>');
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_previous', $this->language->get('text_previous'));
		$view->set('text_results', $this->modelTaxRate->get_text_results());

		$view->set('entry_page', $this->language->get('entry_page'));
		$view->set('entry_search', $this->language->get('entry_search'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_enable_delete', $this->language->get('button_enable_delete'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('controller', 'tax_rate');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		
		$view->set('action', $this->url->ssl('tax_rate', 'page', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('action_delete', $this->url->ssl('tax_rate', 'enableDelete', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('last', $this->url->getLast('tax_rate'));

		$view->set('previous', $this->url->ssl('tax_class', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));

		$view->set('search', $this->session->get('tax_rate.search')); 
		$view->set('sort', $this->session->get('tax_rate.sort'));
		$view->set('order', $this->session->get('tax_rate.order'));
		$view->set('page', $this->session->get('tax_rate.' . $this->request->gethtml('tax_class_id') . '.page'));
		
		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('tax_rate', 'insert', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));

		$view->set('pages', $this->modelTaxRate->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_priority', $this->language->get('entry_priority'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_rate', $this->language->get('entry_rate'));
		$view->set('entry_description', $this->language->get('entry_description'));

		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('error_geo_zone', @$this->error['geo_zone']);
		$view->set('error_priority', @$this->error['priority']);
		$view->set('error_rate', @$this->error['rate']);
		$view->set('error_description', @$this->error['description']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$query = array(
			'tax_rate_id'  => $this->request->gethtml('tax_rate_id'),
			'tax_class_id' => $this->request->gethtml('tax_class_id')
		);

		$view->set('action', $this->url->ssl('tax_rate', $this->request->gethtml('action'), $query));
		$view->set('insert', $this->url->ssl('tax_rate', 'insert', array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('cancel', $this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		$view->set('last', $this->url->getLast('tax_rate'));

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

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('tax_class_id', $this->request->gethtml('tax_class_id'));
		$view->set('tax_rate_id', $this->request->gethtml('tax_rate_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_tax_rate_id', $this->request->gethtml('tax_rate_id'));

		if (($this->request->gethtml('tax_rate_id')) && (!$this->request->isPost())) {
			$tax_rate_info = $this->modelTaxRate->get_taxrate();
			$this->session->set('tax_rate_date_modified', $tax_rate_info['date_modified']);
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

	private function validateForm() {
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

		if (@$this->error && !@$this->error['message']){
			$this->error['warning'] = $this->language->get('error_warning');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateModification() {
		if ($tax_rate_data = $this->modelTaxRate->get_taxrate()) {
			if ($tax_rate_data['date_modified'] != $this->session->get('tax_rate_date_modified')) {
				$tax_rate_data_log = $this->modelTaxRate->get_modified_log($tax_rate_data['date_modified']);

				if ($tax_rate_data_log['geo_zone_id'] != $this->request->gethtml('geo_zone_id', 'post')) {
					$this->error['geo_zone'] = $this->language->get('error_modified', $this->modelTaxRate->get_geozone_name($tax_rate_data_log['geo_zone_id']));
				}

				if ($tax_rate_data_log['description'] != $this->request->gethtml('description', 'post')) {
					$this->error['description'] = $this->language->get('error_modified', $tax_rate_data_log['description']);
				}

				if ($tax_rate_data_log['rate'] != $this->request->gethtml('rate', 'post')) {
					$this->error['rate'] = $this->language->get('error_modified', $tax_rate_data_log['rate']);
				}

				if ($tax_rate_data_log['priority'] != $this->request->gethtml('priority', 'post')) {
					$this->error['priority'] = $this->language->get('error_modified', $tax_rate_data_log['priority']);
				}

				$this->session->set('tax_rate_date_modified', $tax_rate_data_log['date_modified']);
			}
		} else {
			$tax_rate_data_log = $this->modelTaxRate->get_deleted_log();
			$this->session->set('message', $this->language->get('error_deleted', $tax_rate_data_log['modifier']));
			$this->response->redirect($this->url->ssl('tax_rate', FALSE, array('tax_class_id' => $this->request->gethtml('tax_class_id'))));
		}
		if (@$this->error){
			$this->error['warning'] = $this->language->get('error_modifier', $tax_rate_data_log['modifier']);
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function enableDelete(){
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

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'tax_rate')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
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

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	protected function page() {
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
