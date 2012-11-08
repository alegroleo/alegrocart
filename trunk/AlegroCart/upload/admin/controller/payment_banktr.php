<?php // Payment BankTransfer AlegroCart
class ControllerPaymentbanktr extends Controller {
    var $error = array();
    function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 			=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelBTR = $model->get('model_admin_paymentbanktr');
		
		$this->language->load('controller/payment_banktr.php');
	}
    function index() { 
        $this->template->set('title', $this->language->get('heading_title'));
 
        if (($this->request->isPost()) && ($this->validate())) {
			$results = $this->modelBTR->get_BTR();
			$this->modelBTR->delete_BTR();
            foreach ($results as $result) {
                    $combo = $result['type'] . "_" . $result['key'];
                    $this->modelBTR->update_BTR($result['type'], $result['key'], $combo);
            }
           
            $this->session->set('message', $this->language->get('text_message'));

            $this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
        }
        
        $view = $this->locator->create('template');
        
        $view->set('heading_title', $this->language->get('heading_title'));
	$view->set('heading_payment', $this->language->get('heading_payment'));
        $view->set('heading_description', $this->language->get('heading_description'));        

        $view->set('text_enabled', $this->language->get('text_enabled'));
        $view->set('text_disabled', $this->language->get('text_disabled'));
        $view->set('text_all_zones', $this->language->get('text_all_zones'));
        $view->set('text_none', $this->language->get('text_none'));
        
        $view->set('button_list', $this->language->get('button_list'));
        $view->set('button_insert', $this->language->get('button_insert'));
        $view->set('button_update', $this->language->get('button_update'));
        $view->set('button_delete', $this->language->get('button_delete'));
        $view->set('button_save', $this->language->get('button_save'));
        $view->set('button_cancel', $this->language->get('button_cancel'));

        $view->set('tab_general', $this->language->get('tab_general'));

        $view->set('error', @$this->error['message']);
        
        $view->set('action', $this->url->ssl('payment_banktr'));
        $view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'payment')));
        $view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'payment')));    

        $this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));

        $results = $this->modelBTR->get_BTR();
        foreach ($results as $result) {
            $setting_info[$result['type']][$result['key']] = $result['value'];
            $combo = $result['type'] . "_" . $result['key'];
            if ($this->request->has($combo, 'post')) {
                $view->set($combo, $this->request->gethtml($combo, 'post'));
            } else {
                $view->set($combo, htmlspecialchars_decode(@$setting_info[$result['type']][$result['key']]));
            }
            
            $view->set('entry_' . $result['key'], $this->language->get('entry_' . $result['key']));
            $view->set('explanation_' . $result['key'], $this->language->get('explanation_' . $result['key']));
        }

        $view->set('geo_zones', $this->modelBTR->get_geo_zones());

        $this->template->set('content', $view->fetch('content/payment_banktr.tpl'));

        $this->template->set($this->module->fetch());

        $this->response->set($this->template->fetch('layout.tpl'));
    }
    
    function validate() {
        if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
        if (!$this->user->hasPermission('modify', 'payment_banktr')) {
            $this->error['message'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }    
    }
    
    function install() {
        if ($this->user->hasPermission('modify', 'payment_banktr')) {
            $this->modelBTR->delete_BTR();
            $this->modelBTR->install_BTR();
            $this->session->set('message', $this->language->get('text_message'));
        } else {
            $this->session->set('error', $this->language->get('error_permission'));
        }    

        $this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
    }
    
    function uninstall() {
        if ($this->user->hasPermission('modify', 'payment_banktr')) {
            $this->modelBTR->delete_BTR();
            $this->session->set('message', $this->language->get('text_message'));
        } else {
            $this->session->set('error', $this->language->get('error_permission'));
        }    
 
        $this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'payment')));
    }
}
?>
