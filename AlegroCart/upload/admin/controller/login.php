<?php //Admin Login AlegroCart
class ControllerLogin extends Controller { 
	var $error = array();

	function __construct(&$locator){
		$this->locator 	=& $locator;
		$this->language =& $locator->get('language');
		$this->module   =& $locator->get('module');
		$this->request  =& $locator->get('request');
		$this->response =& $locator->get('response');
		$this->session  =& $locator->get('session');
		$this->template =& $locator->get('template');
		$this->url      =& $locator->get('url');
		$this->user     =& $locator->get('user');
		
		$this->language->load('controller/login.php');
	}

	function index() {
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->user->isLogged()) {
			$controller=$this->request->gethtml('controller');
			if ($controller == 'login' || empty($controller)) {
				$this->response->redirect($this->url->ssl('home'));
			} else { 
				$this->response->redirect($this->url->referer($this->url->ssl($controller)));
			}
		}

		if ($this->request->isPost() && $this->request->has('username', 'post') && $this->validate()) {
			$controller=$this->request->gethtml('controller');
			if ($controller == 'login' || empty($controller)) {
				$this->response->redirect($this->url->ssl('home'));
			} else {
				$this->response->redirect($this->url->referer($this->url->ssl($controller))); 
			}
		}

	$view = $this->locator->create('template');

	$view->set('heading_title', $this->language->get('heading_title'));
	$view->set('heading_description', $this->language->get('heading_description'));

	$view->set('entry_username', $this->language->get('entry_username'));
	$view->set('entry_password', $this->language->get('entry_password'));

	$view->set('button_login', $this->language->get('button_login'));

		$view->set('error', @$this->error['message']);
		$view->set('message', $this->session->get('message'));

		$view->set('action', $this->url->requested($this->url->ssl('home')));

		$rand = mt_rand();
		$this->session->set('cdx',md5($rand));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

		$view->set('username', $this->request->sanitize('username', 'post'));

		$this->template->set('content', $view->fetch('content/login.tpl'));

		$this->template->set($this->module->fetch());

	$this->response->set($this->template->fetch('layout.tpl'));
	}

	function validate() {

		if (!$this->user->login($this->request->sanitize('username', 'post'), $this->request->sanitize('password', 'post'))) {
				$this->error['message'] = $this->language->get('error_login');
		}
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function isLogged() {
		if (!$this->user->isLogged()) {
			return $this->forward('login', 'index');
		}
	}

}
?>
