<?php //AlegroCart Backup
class ControllerBackup extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session  	=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->upload   	=& $locator->get('upload');
		$this->url      	=& $locator->get('url');
		$this->user    		=& $locator->get('user');
		$this->modelBackup = $model->get('model_admin_backup');
		
		$this->language->load('controller/backup.php');
	}
	function index() {		
		$this->template->set('title', $this->language->get('heading_title'));
				
		if ($this->request->isPost() && $this->upload->has('database') && $this->validate_upload() ) {
			$file = $this->upload->get('database');
			$this->modelBackup->import_file($file['tmp_name']);

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->href('backup'));
		}
		
		$view = $this->locator->create('template');
		
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		
		$view->set('text_backup', $this->language->get('text_backup'));
		$view->set('text_browse', $this->language->get('text_browse'));

		$view->set('entry_restore', $this->language->get('entry_restore'));
		$view->set('entry_backup', $this->language->get('entry_backup'));

		$view->set('explanation_entry_restore', $this->language->get('explanation_entry_restore'));
		$view->set('explanation_entry_backup', $this->language->get('explanation_entry_backup'));

		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_general', $this->language->get('tab_general'));
		
		$view->set('error', @$this->error['message']);
		
		$view->set('action', $this->url->ssl('backup'));

		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$rand = mt_rand();
		$this->session->set('cdx',md5($rand));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$view->set('cancel', $this->url->ssl('backup'));
		
		$view->set('download', $this->url->ssl('backup', 'download&validation='.$this->session->get('validation')));
				
		$this->template->set('content', $view->fetch('content/backup.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function download() {
		if ($this->validate_download()) {
			$this->response->setheader('Pragma', 'public');
			$this->response->setheader('Expires', '0');
			$this->response->setheader('Content-Description', 'File Transfer');
			$this->response->setheader('Content-Type', 'application/octet-stream');
			$this->response->setheader('Content-Disposition', 'attachment; filename=backup.sql');
			$this->response->setheader('Content-Transfer-Encoding', 'binary');
			$this->response->set($this->modelBackup->export_file());
			
			$this->response->output();
			die(); //no further processing is required
		} else {
			$this->response->redirect($this->url->ssl('backup'));
		}
	}
	
	function validate_download(){
		if($this->session->get('validation') == $this->request->get('validation')){
			if (!$this->user->hasPermission('modify', 'backup')) {
			$this->error['message'] = $this->language->get('error_permission');
			}
		} else {
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->error) {
			return TRUE;
		} else {
			$this->session->set('message',$this->error['message']);
			return FALSE;
		}		
	}
	function validate_upload() {
		if(($this->session->get('validation') == $this->request->sanitize($this->session->get('cdx'),'post')) && (strlen($this->session->get('validation')) > 10)){
			if (!$this->user->hasPermission('modify', 'backup')) {
			$this->error['message'] = $this->language->get('error_permission');
			}
		} else {
			$this->error['message'] = $this->language->get('error_referer');
			$this->session->set('message',$this->error['message']);
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}		
	}
}
?>