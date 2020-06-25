<?php //Mail AlegroCart
class ControllerMail extends Controller {
	public $error = array();
	public function __construct(&$locator){
		$this->locator		=& $locator;
		$model			=& $locator->get('model');
		$this->config		=& $locator->get('config');
		$this->language		=& $locator->get('language');
		$this->mail		=& $locator->get('mail');
		$this->module		=& $locator->get('module');
		$this->request		=& $locator->get('request');
		$this->response		=& $locator->get('response');
		$this->template		=& $locator->get('template');
		$this->session		=& $locator->get('session');
		$this->url		=& $locator->get('url');
		$this->user		=& $locator->get('user');
		$this->modelMail	= $model->get('model_admin_mail');
		$this->head_def		=& $locator->get('HeaderDefinition');
		$this->adminController	= $this->template->set_controller('mail');

		$this->language->load('controller/mail.php');
	}
	protected function index() {
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
		$view->set('head_def',$this->head_def); 
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_print', $this->language->get('button_print'));
		$view->set('button_help', $this->language->get('button_help'));
		$view->set('button_send', $this->language->get('button_send'));
		$view->set('button_last', $this->language->get('button_last'));

		$view->set('help', $this->session->get('help'));
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));

		$view->set('text_newsletter', $this->language->get('text_newsletter'));
		$view->set('text_customer', $this->language->get('text_customer'));
		
		$view->set('entry_to', $this->language->get('entry_to'));
		$view->set('entry_subject', $this->language->get('entry_subject'));
		$view->set('entry_content', $this->language->get('entry_content'));
		
		$view->set('error', @$this->error['message']);
		$view->set('error_to', @$this->error['to']);
		$view->set('error_subject', @$this->error['subject']);
		$view->set('error_content', @$this->error['content']);
		
		$view->set('message', $this->session->get('message'));
		$this->session->delete('message');
		$view->set('action', $this->url->ssl('mail'));
		$view->set('last', $this->url->getLast('mail'));

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

		if ($this->request->has('content', 'post')) {
			$view->set('content', $this->request->gethtml('content', 'post'));
		} else {
			$view->set('content', $this->createSignature());
		}
		$this->template->set('content', $view->fetch('content/mail.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}

	protected function help(){
		if($this->session->get('help')){
			$this->session->delete('help');
		} else {
			$this->session->set('help', TRUE);
		}
	}

	private function validateForm() {
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

	private function createSignature() {
		$signature ='';
		if($this->user->getFullName()) {
			$signature .= '<p></p><br><p style="font-size: 15px; text-transform: uppercase; "><font face="Arial" size="1"><b>'.$this->user->getFullName().'</b><br>'.$this->user->getPosition().'</font></p>';
		}
		$signature .= '<p style="text-transform: capitalize;"><b><font face="Arial" size="1">'.$this->config->get('config_owner').'</font></b><br><font face="Arial" size="1">'.nl2br($this->config->get('config_address')).'</font></p>';
		$signature .= '<p><font face="Arial" size="1">';
		if($this->user->getTelephone()) {
			$signature .= $this->language->get('text_telephone').$this->user->getTelephone().'<br>';
		}
		if($this->user->getMobile()) {
			$signature .= $this->language->get('text_mobile').$this->user->getMobile().'<br>';
		}
		if($this->user->getFax()) {
			$signature .= $this->language->get('text_fax').$this->user->getFax();
		}
		$signature .='</font></p>';

		return $signature;
	}
}
?>
