<?php //Mail AlegroCart
class ControllerMail extends Controller {
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
		$this->modelMail = $model->get('model_admin_mail');
		
		$this->language->load('controller/mail.php');
	}
	function index() {
		$this->template->set('title', $this->language->get('heading_title'));
		
		if ($this->request->isPost() && $this->request->has('to', 'post') && $this->validateForm()) {
			$email = array();
			
			switch ($this->request->gethtml('to', 'post')) {
				case 'newsletter':
					$results = $this->modelMail->get_email_newsletter();
					foreach ($results as $result) {
						$email[] = $result['email'];
					}				
					break;
				case 'customer':
					$results = $this->modelMail->get_email_customers();
					foreach ($results as $result) {
						$email[] = $result['email'];
					}						
					break;
				default: 
					$result = $this->modelMail->get_customer_email();
					$email = $result['email'];
					break;
			}
			$from = $this->config->get('config_email_mail') ? $this->config->get('config_email_mail') : $this->config->get('config_email');
			if ($email) {
				$this->mail->setTo($from);
				$this->mail->setBcc($email);
				$this->mail->setFrom($from);
	    		$this->mail->setSender($this->config->get('config_store'));
	    		$this->mail->setSubject($this->request->get('subject', 'post'));
				$this->mail->setHtml($this->request->get('content', 'post'));
	    		$this->mail->send();
			}

			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->href('mail'));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_newsletter', $this->language->get('text_newsletter'));
		$view->set('text_customer', $this->language->get('text_customer'));
		
		$view->set('entry_to', $this->language->get('entry_to'));
		$view->set('entry_subject', $this->language->get('entry_subject'));
		$view->set('entry_content', $this->language->get('entry_content'));
		
		$view->set('button_send', $this->language->get('button_send'));
		
		$view->set('error', @$this->error['message']);
		$view->set('error_to', @$this->error['to']);
		$view->set('error_subject', @$this->error['subject']);
		$view->set('error_content', @$this->error['content']);
		
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('action', $this->url->ssl('mail'));
		
		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		$customer_data = array();
		$results = $this->modelMail->get_customers();
		foreach ($results as $result) {
			$customer_data[] = array(
				'customer_id' => $result['customer_id'],
				'name'        => $result['firstname'] . ' ' . $result['lastname'] . ' (' . $result['email'] . ')'
			);
		}	
		$view->set('customers', $customer_data);
		
		$view->set('to', $this->request->gethtml('to', 'post'));
		$view->set('subject', $this->request->gethtml('subject', 'post'));
		$view->set('content', $this->request->gethtml('content', 'post'));

		$this->template->set('content', $view->fetch('content/mail.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function validateForm() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		
		if (!$this->user->hasPermission('modify', 'mail')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
				
		if (!$this->request->gethtml('subject', 'post')) {
			$this->error['subject'] = $this->language->get('error_subject');
		}

		if (!$this->request->gethtml('content', 'post')) {
			$this->error['content'] = $this->language->get('error_content');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
}
?>