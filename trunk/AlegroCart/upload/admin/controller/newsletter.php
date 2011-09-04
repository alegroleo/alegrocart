<?php // Newsletter AlegroCart
class ControllerNewsletter extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->config   	=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->mail     	=& $locator->get('mail');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelNewsletter = $model->get('model_admin_newsletter');
		
		$this->language->load('controller/newsletter.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function insert() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('subject', 'post') && $this->validateForm()) {
			$this->modelNewsletter->insert_newsletter();
			if ($this->request->get('send', 'post')) {
				$newsletter_id = $this->modelNewsletter->get_insert_id();
				$this->modelNewsletter->update_send($newsletter_id);
				$email = array();
				$results = $this->modelNewsletter->get_customers();
				foreach ($results as $result) {
					$email[] = $result['email'];
				}
				$from = $this->config->get('config_email_newsletter') ? $this->config->get('config_email_newsletter') : $this->config->get('config_email');
				if ($email) {
					$this->mail->setTo($from);
					$this->mail->setBcc($email);
					$this->mail->setFrom($from);
	    			$this->mail->setSender($this->config->get('config_store'));
	    			$this->mail->setSubject($this->request->get('subject', 'post'));
					$this->mail->setHtml($this->request->get('content', 'post'));	    	
	    			$this->mail->send();
				}
			}

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('newsletter'));
		}

		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function update() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('subject', 'post') && $this->validateForm()) {
			$this->modelNewsletter->update_newsletter();
			if ($this->request->get('send', 'post')) {
				$this->modelNewsletter->update_send($this->request->gethtml('newsletter_id'));
				$email = array();
				$results = $this->modelNewsletter->get_customers();
				foreach ($results as $result) {
					$email[] = $result['email'];
				}
				$from = $this->config->get('config_email_newsletter') ? $this->config->get('config_email_newsletter') : $this->config->get('config_email');
				if ($email) {
					$this->mail->setTo($from);
					$this->mail->setBcc($email);
					$this->mail->setFrom($from);
	    			$this->mail->setSender($this->config->get('config_store'));
	    			$this->mail->setSubject($this->request->get('subject', 'post'));
					$this->mail->setHtml($this->request->get('content', 'post'));
	    			$this->mail->send();
				}
			}

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('newsletter'));
		}

		$this->template->set('content', $this->getForm());
		$this->template->set($this->module->fetch()); 

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	function delete() {
		$this->template->set('title', $this->language->get('heading_title'));

		if (($this->request->gethtml('newsletter_id')) && ($this->validateDelete())) {
			$this->modelNewsletter->delete_newsletter();

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('newsletter'));
		}

		$this->template->set('content', $this->getList());
		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
		
	function getList() {
		$this->session->set('newsletter_validation', md5(time()));
		$cols = array();

		$cols[] = array(
			'name'  => $this->language->get('column_subject'),
			'sort'  => 'subject',
			'align' => 'left'
		);

		$cols[] = array(
			'name'  => $this->language->get('column_date_added'),
			'sort'  => 'date_added',
			'align' => 'left'
		);
		
		$cols[] = array(
			'name'  => $this->language->get('column_date_sent'),
			'sort'  => 'date_sent',
			'align' => 'left'
		);

    	$cols[] = array(
      		'name'  => $this->language->get('column_action'),
      		'align' => 'action'
    	);

		
		$results = $this->modelNewsletter->get_page();
		
		$rows = array();
		foreach ($results as $result) {
			$cell = array();
			$cell[] = array(
				'value' => $result['subject'],
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'align' => 'left'
			);
			$cell[] = array(
				'value' => $this->language->formatDate($this->language->get('date_format_short'), strtotime($result['date_sent'])),
				'align' => 'left'
			);
			$action = array();
      		
			$action[] = array(
        		'icon' => 'update.png',
				'text' => $this->language->get('button_update'),
				'href' => $this->url->ssl('newsletter', 'update', array('newsletter_id' => $result['newsletter_id']))
      		);
			if($this->session->get('enable_delete')){
				$action[] = array(
					'icon' => 'delete.png',
					'text' => $this->language->get('button_delete'),
					'href' => $this->url->ssl('newsletter', 'delete', array('newsletter_id' => $result['newsletter_id'],'newsletter_validation' =>$this->session->get('newsletter_validation')))
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

		$view->set('text_results', $this->modelNewsletter->get_text_results());

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
		
		$view->set('action', $this->url->ssl('newsletter', 'page'));
		$view->set('action_delete', $this->url->ssl('newsletter', 'enableDelete'));
 
		$view->set('search', $this->session->get('newsletter.search'));
		$view->set('sort', $this->session->get('newsletter.sort'));
		$view->set('order', $this->session->get('newsletter.order'));
		$view->set('page', $this->session->get('newsletter.page'));

		$view->set('cols', $cols);
		$view->set('rows', $rows);

		$view->set('list', $this->url->ssl('newsletter'));
		$view->set('insert', $this->url->ssl('newsletter', 'insert'));

		$view->set('pages', $this->modelNewsletter->get_pagination());

		return $view->fetch('content/list.tpl');
	}
		
	function getForm() {
		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		
		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));
				
		$view->set('entry_subject', $this->language->get('entry_subject'));
		$view->set('entry_content', $this->language->get('entry_content'));
		$view->set('entry_send', $this->language->get('entry_send'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('error', @$this->error['message']);
		$view->set('error_subject', @$this->error['subject']);
		$view->set('error_content', @$this->error['content']);
		
		$view->set('action', $this->url->ssl('newsletter', $this->request->gethtml('action'), array('newsletter_id' => $this->request->gethtml('newsletter_id'))));
		
		$view->set('list', $this->url->ssl('newsletter'));
		$view->set('insert', $this->url->ssl('newsletter', 'insert'));
		$view->set('cancel', $this->url->ssl('newsletter'));

		if ($this->request->gethtml('newsletter_id')) {
			$view->set('update', 'enable');
			$view->set('delete', $this->url->ssl('newsletter', 'delete', array('newsletter_id' => $this->request->gethtml('newsletter_id'),'newsletter_validation' =>$this->session->get('newsletter_validation'))));
		}
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		if (($this->request->gethtml('newsletter_id')) && (!$this->request->isPost())) {
			$newsletter_info = $this->modelNewsletter->get_newsletter();
		}

		if ($this->request->has('subject', 'post')) {
			$view->set('subject', $this->request->get('subject', 'post'));
		} else {
			$view->set('subject', @$newsletter_info['subject']);
		}
		 
		if ($this->request->has('content', 'post')) {
			$view->set('content', $this->request->get('content', 'post'));
		} else {
			$view->set('content', @$newsletter_info['content']);
		}
		
		if ($this->request->has('send', 'post')) {
			$view->set('send', $this->request->gethtml('send', 'post'));
		} else {
			$view->set('send', @$newsletter_info['send']);
		}

		return $view->fetch('content/newsletter.tpl');
	}
	
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		
		if (!$this->user->hasPermission('modify', 'newsletter')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
				
		if (!$this->request->get('subject', 'post')) {
			$this->error['subject'] = $this->language->get('error_subject');
		}
		
		if (!$this->request->get('content', 'post')) {
			$this->error['content'] = $this->language->get('error_content');
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
			$this->response->redirect($this->url->ssl('newsletter'));
		} else {
			$this->session->set('message', @$this->error['message']);
			$this->response->redirect($this->url->ssl('newsletter'));
		}
	}
	function validateEnableDelete(){
		if (!$this->user->hasPermission('modify', 'newsletter')) {
      		$this->error['message'] = $this->language->get('error_permission');  
    	}
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
	}
	
	function validateDelete() {
		if(($this->session->get('newsletter_validation') != $this->request->sanitize('newsletter_validation')) || (strlen($this->session->get('newsletter_validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('newsletter_validation');
		if (!$this->user->hasPermission('modify', 'newsletter')) {
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
	  		$this->session->set('newsletter.search', $this->request->gethtml('search', 'post'));
		}
		if (($this->request->has('page', 'post')) || ($this->request->has('search', 'post'))) {
	  		$this->session->set('newsletter.page', $this->request->gethtml('page', 'post'));
		} 
		if ($this->request->has('sort', 'post')) {
			$this->session->set('newsletter.order', (($this->session->get('newsletter.sort') == $this->request->gethtml('sort', 'post')) && ($this->session->get('newsletter.order') == 'asc') ? 'desc' : 'asc'));
		}
		if ($this->request->has('sort', 'post')) {
			$this->session->set('newsletter.sort', $this->request->gethtml('sort', 'post'));
		}

		$this->response->redirect($this->url->ssl('newsletter'));
  	}
}
?>