<?php // Information AlegroCart
class ControllerInformation extends Controller {

	public $error = array();

	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->cache		=& $locator->get('cache');
		$this->language		=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->session		=& $locator->get('session');
		$this->template		=& $locator->get('template');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user');
		$this->validate		=& $locator->get('validate');
		$this->modelInformation = $model->get('model_admin_information');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('information');

		$this->language->load('controller/information.php');
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

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm()) {
			$this->modelInformation->insert_information();
			$insert_id = $this->modelInformation->get_insert_id();
			$this->modelInformation->insert_description($insert_id);
			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				if ($key == $this->language->getId()) {
					$name_last = $value['title'];
					if (strlen($name_last) > 26) {
						$name_last = substr($name_last , 0, 23) . '...';
					}
					$this->session->set('name_last_information', $name_last);
					$this->session->set('last_information', $this->url->ssl('information', 'update', array('information_id' => $insert_id)));
				}
			}
			$this->session->set('last_information_id', $insert_id);
			$this->cache->delete('information');
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('information'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());

		$this->session->delete('name_last_information');
		$this->session->delete('last_information');

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('language', 'post') && $this->validateForm() && $this->validateModification()) {
			$this->modelInformation->update_information();
			$this->modelInformation->delete_description();
			$this->modelInformation->insert_description((int)$this->request->gethtml('information_id'));
			$this->cache->delete('information');
			$this->session->set('message', $this->language->get('text_message'));

			if ($this->request->has('update_form', 'post')) {
				$this->response->redirect($this->url->ssl('information', 'update', array('information_id' => $this->request->gethtml('information_id', 'post'))));
			} else {
				$this->response->redirect($this->url->ssl('information'));
			}

		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
 
	protected function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('information_id')) && ($this->validateDelete())) {
			$this->modelInformation->delete_information();
			$this->modelInformation->delete_description();
			$this->cache->delete('information');
			$this->session->set('message', $this->language->get('text_message'));
			$this->session->delete('name_last_information');
			$this->session->delete('last_information');
			$this->response->redirect($this->url->ssl('information'));
		}
		$this->template->set('head_def',$this->head_def);
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function changeStatus() {
		if (($this->request->has('stat_id')) && ($this->request->has('stat')) && $this->validateChangeVisibility()) {
			$this->modelInformation->change_information_visibility($this->request->gethtml('stat'), $this->request->gethtml('stat_id'));
			$this->cache->delete('information');
		}
	
	}

	private function getList() {
		$this->session->set('information_validation', md5(time()));
		$cols = array();
		$cols[] = array(
			'name'  => $this->language->get('column_title'),
			'sort'  => 'id.title',
			'align' => 'left'
		);
		$cols[] = array(
		'name'  => $this->language->get('column_visibility'),
		'sort'  => 'i.information_hide',
		'align' => 'center'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_sort_order'),
			'sort'  => 'i.sort_order',
			'align' => 'right'
		);
		$cols[] = array(
			'name'  => $this->language->get('column_action'),
			'align' => 'action'
		);

		$results = $this->modelInformation->get_page();

		$rows = array();
		foreach ($results as $result) {
			$last = $result['information_id'] == $this->session->get('last_information_id') ? 'last_visited': '';
			$cell = array();
			$cell[] = array(
				'value' => $result['title'],
				'align' => 'left',
				'last' => $last
			);

		if ($this->validateChangeVisibility()) {
			$cell[] = array(
				'status'  => $result['information_hide'],
				'text' => $this->language->get('button_visibility'),
				'align' => 'center',
				'status_id' => $result['information_id'],
				'status_controller' => 'information'
			);
			} else {
				$cell[] = array(
					'icon'  => ($result['information_hide'] ? 'enabled.png' : 'disabled.png'),
					'align' => 'center'
				);
			}

			$cell[] = array(
				'value' => $result['sort_order'],
				'align' => 'right',
				'last' => $last
			);
			
			$action = array();
			$action[] = array(
				'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('information', 'update', array('information_id' => $result['information_id']))
				);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('information', 'delete', array('information_id' => $result['information_id'],'information_validation' =>$this->session->get('information_validation')))
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
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_results', $this->modelInformation->get_text_results());
		$view->set('text_asc', $this->language->get('text_asc'));
		$view->set('text_desc', $this->language->get('text_desc'));

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
		$view->set('controller', 'information');
		$view->set('text_confirm_delete', $this->language->get('text_confirm_delete'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		 
		$view->set('action', $this->url->ssl('information', 'page'));
		$view->set('action_delete', $this->url->ssl('information', 'enableDelete'));

		$view->set('search', $this->session->get('information.search'));
		$view->set('sort', $this->session->get('information.sort'));
		$view->set('order', $this->session->get('information.order'));
		$view->set('page', $this->session->get('information.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('insert', $this->url->ssl('information', 'insert'));
		$view->set('last', $this->url->getLast('information'));
		$view->set('pages', $this->modelInformation->get_pagination());

		return $view->fetch('content/list.tpl');
	}

	private function getForm() {
		$view = $this->locator->create('template');
		$view->set('head_def',$this->head_def);
		$view->set('heading_title', $this->language->get('heading_form_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('entry_title', $this->language->get('entry_title'));
		$view->set('entry_description', $this->language->get('entry_description'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_hide', $this->language->get('entry_hide'));

		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));

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
		$view->set('tab_data', $this->language->get('tab_data'));

		$view->set('explanation_sort_order', $this->language->get('explanation_sort_order'));
		$view->set('explanation_hide', $this->language->get('explanation_hide'));

		$view->set('error_update', $this->language->get('error_update'));
		$view->set('error', @$this->error['message']);
		$view->set('error_title', @$this->error['title']);
		$view->set('error_description', @$this->error['description']);
		$view->set('error_mod_description', @$this->error['mod_description']);
		$view->set('error_hide', @$this->error['hide']);
		$view->set('error_sort_order', @$this->error['sort_order']);

		if(!@$this->error['message']){
			$view->set('error', @$this->error['warning']);
		}

		$view->set('action', $this->url->ssl('information', $this->request->gethtml('action'), array('information_id' => $this->request->gethtml('information_id'))));

		$view->set('insert', $this->url->ssl('information', 'insert'));
		$view->set('cancel', $this->url->ssl('information'));
		$view->set('last', $this->url->getLast('information'));

		if ($this->request->gethtml('information_id')) {
			$view->set('update', 'update');
			$view->set('delete', $this->url->ssl('information', 'delete', array('information_id' => $this->request->gethtml('information_id'),'information_validation' =>$this->session->get('information_validation'))));
		}

		$view->set('tab', $this->session->has('information_tab') && $this->session->get('information_id') == $this->request->gethtml('information_id') ? $this->session->get('information_tab') : 0);
		$view->set('tabmini', $this->session->has('information_tabmini') && $this->session->get('information_id') == $this->request->gethtml('information_id') ? $this->session->get('information_tabmini') : 0);

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('information_id', $this->request->gethtml('information_id'));

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$this->session->set('last_information_id', $this->request->gethtml('information_id'));

		$information_data = array();
		$results = $this->modelInformation->get_languages();
		foreach ($results as $result) {
			if($result['language_status'] =='1'){
				if (($this->request->gethtml('information_id')) && (!$this->request->isPost())) {
					$information_description_info = $this->modelInformation->get_description($result['language_id']);
					$this->session->set('information_description_date_modified', $information_description_info['date_modified']);
				} else {
					$information_description_info = $this->request->gethtml('language', 'post');
				}

				$information_data[] = array(
					'language_id' => $result['language_id'],
					'language'    => $result['name'],
					'title'       => (isset($information_description_info[$result['language_id']]) ? $information_description_info[$result['language_id']]['title'] : @$information_description_info['title']),
		    			'description' => (isset($information_description_info[$result['language_id']]) ? $information_description_info[$result['language_id']]['description'] : @$information_description_info['description'])
				);

				if ($result['language_id'] == $this->language->getId()) {
					if (isset($information_description_info[$result['language_id']])) {
						if ($information_description_info[$result['language_id']]['title'] != NULL) {
							$name_last = $information_description_info[$result['language_id']]['title'];
						} else {
							$name_last = $this->session->get('name_last_information');
						}
					} else {
						$name_last = @$information_description_info['title'];
					}

					if (strlen($name_last) > 26) {
						$name_last = substr($name_last , 0, 23) . '...';
					}
					$this->session->set('name_last_information', $name_last);
					$this->session->set('last_information', $this->url->ssl('information', 'update', array('information_id' => $this->request->gethtml('information_id'))));
				}
			}
		}

		$view->set('informations', $information_data);

		if (($this->request->gethtml('information_id')) && (!$this->request->isPost())) {
			$information_info = $this->modelInformation->get_information();
			$this->session->set('information_date_modified',$information_info['date_modified']);
		}

		if ($this->request->has('information_hide', 'post')) {
			$view->set('information_hide', $this->request->gethtml('information_hide', 'post'));
		} else {
			$view->set('information_hide', @$information_info['information_hide']);
		}

		if ($this->request->has('sort_order', 'post')) {
			$view->set('sort_order', $this->request->gethtml('sort_order', 'post'));
		} else {
			$view->set('sort_order', @$information_info['sort_order']);
		}

		return $view->fetch('content/information.tpl');
	}

	private function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'information')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		foreach ($this->request->gethtml('language', 'post') as $key => $value) {
			if (!$this->validate->strlen($value['title'],1,32)) {
				$this->error['title'][$key] = $this->language->get('error_title');
			}
			if (!$this->validate->strlen($value['description'],1)) {
				$this->error['description'][$key] = $this->language->get('error_description');
			}
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

		if ($information_info = $this->modelInformation->get_information()) {
			if ($information_info['date_modified'] != $this->session->get('information_date_modified')) {
				$information_info_log = $this->modelInformation->get_modified_log($information_info['date_modified']);

				if ($information_info_log['information_hide'] != $this->request->gethtml('information_hide', 'post')) {
					$this->error['hide'] = $this->language->get('error_modified', $information_info_log['information_hide'] ? $this->language->get('text_yes'): $this->language->get('text_no'));
				}

				if ($information_info_log['sort_order'] != $this->request->gethtml('sort_order', 'post')) {
					$this->error['sort_order'] = $this->language->get('error_modified', $information_info_log['sort_order']);
				}
				$this->session->set('information_date_modified', $information_info_log['date_modified']);
			}

			foreach ($this->request->gethtml('language', 'post') as $key => $value) {
				if ($information_data = $this->modelInformation->get_description($key)) {
					if ($information_data['date_modified'] != $this->session->get('information_description_date_modified')) {
						$information_data_log = $this->modelInformation->get_description_modified_log($key, $information_data['date_modified']);
						if ($information_data_log['title'] != $value['title']) {
							$this->error['title'][$key] = $this->language->get('error_modified', $information_data_log['title']);
						}
						if (htmlspecialchars($information_data_log['description']) != $value['description']) {
							$this->error['description'][$key] = $this->language->get('error_modified', '');
							$this->error['mod_description'][$key] = $information_data_log['description'];
						}
					}
				}
				$this->session->set('information_description_date_modified', $information_data['date_modified']);
			}
		} else {
			$information_info_log = $this->modelInformation->get_deleted_log();
			$this->session->set('message', $this->language->get('error_deleted', $information_info_log['modifier']));
			$this->response->redirect($this->url->ssl('information'));
		}

		if (@$this->error){
			$this->error['warning'] = $this->language->get('error_modifier', isset($information_info_log['modifier']) ? $information_info_log['modifier'] : $information_data_log['modifier']);
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
			$this->response->redirect($this->url->ssl('information'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('information'));
		}
	}

	private function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'information')) {
			$this->error['message'] = $this->language->get('error_permission');  
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if(($this->session->get('information_validation') != $this->request->sanitize('information_validation')) || (strlen($this->session->get('information_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('information_validation');
		if (!$this->user->hasPermission('modify', 'information')) {
			$this->error['message'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateChangeVisibility(){
		if (!$this->user->hasPermission('modify', 'information')) {
			return FALSE;
		} else {
			return TRUE;
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
		$this->request  =& $this->locator->get('request');
		$this->response =& $this->locator->get('response');
		$this->url      =& $this->locator->get('url');
		$this->session  =& $this->locator->get('session');

		if ($this->request->has('search', 'post')) {
			$this->session->set('information.search', $this->request->gethtml('search', 'post'));
		}

		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
			$this->session->set('information.page', $this->request->gethtml('page', 'post'));
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('information.order', (($this->session->get('information.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('information.order') == 'asc')) ? 'desc' : 'asc');
		}

		if ($this->request->has('sort', 'post')) {
			$this->session->set('information.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('information'));
	}

	protected function tab() {
		if ($this->request->isPost()) {
			if ($this->request->has('activeTab', 'post')) {
				$this->session->set('information_tab', $this->request->sanitize('activeTab', 'post'));
				$this->session->set('information_id', $this->request->sanitize('id', 'post'));
				if ($this->request->has('activeTabmini', 'post')) {
					$this->session->set('information_tabmini', $this->request->sanitize('activeTabmini', 'post'));
				}
				$output = array('status' => true);
			} else {
				$output = array('status' => false);
			}
			echo json_encode($output);
		}
	}
}
?>
