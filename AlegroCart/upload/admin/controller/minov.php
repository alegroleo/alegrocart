<?php // Minimum Order Value AlegroCart
class ControllerMinov extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 			=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request 	 	=& $locator->get('request');
		$this->response	 	=& $locator->get('response');
		$this->session 	 	=& $locator->get('session');
		$this->user     	=& $locator->get('user'); 
		$this->template 	=& $locator->get('template');
		$this->url     		=& $locator->get('url');
		$this->modelMinov 	= $model->get('model_admin_minov');
		$this->language->load('controller/minov.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->isPost()) && ($this->validate())) {
		$this->modelMinov->delete_minov();
		$this->modelMinov->update_minov();
		$this->session->set('message', $this->language->get('text_message'));
		$this->response->redirect($this->url->ssl('minov'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));

		$view->set('entry_minov', $this->language->get('entry_minov'));
		$view->set('entry_minov_status', $this->language->get('entry_minov_status'));
		$view->set('explanation_entry_minov', $this->language->get('explanation_entry_minov'));
		$view->set('explanation_entry_minov_status', $this->language->get('explanation_entry_minov_status'));
 
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));
		
		$this->session->delete('message');
		$view->set('action', $this->url->ssl('minov'));
		$view->set('cancel', $this->url->ssl('minov'));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (!$this->request->isPost()) {
			$results = $this->modelMinov->get_minov();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}

		if ($this->request->has('global_minov_status', 'post')) {
			$view->set('global_minov_status', $this->request->gethtml('global_minov_status', 'post'));
		} else {
			$view->set('global_minov_status', @$setting_info['global']['minov_status']);
		}

		if ($this->request->has('global_minov_value', 'post')) {
			$view->set('global_minov_value', $this->request->gethtml('global_minov_value', 'post'));
		} else {
			$view->set('global_minov_value', @$setting_info['global']['minov_value']); 
		} 

		$this->template->set('content', $view->fetch('content/minov.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
    	if (!$this->user->hasPermission('modify', 'minov')) {
      		$this->error['message'] = $this->language->get('error_permission');
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
}
?>