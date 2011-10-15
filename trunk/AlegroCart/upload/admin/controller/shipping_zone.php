<?php //Shipping Zone AlegroCart
class ControllerShippingZone extends Controller {
	var $error = array();
	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		$this->language 	=& $locator->get('language');
		$this->module		=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->template 	=& $locator->get('template');
		$this->session  	=& $locator->get('session');
		$this->url      	=& $locator->get('url');
		$this->user     	=& $locator->get('user');
		$this->modelZone = $model->get('model_admin_shippingzone');
		
		$this->language->load('controller/shipping_zone.php');
	}
	function index() {  
		$this->template->set('title', $this->language->get('heading_title'));

		if ($this->request->isPost() && $this->request->has('geo_zone', 'post') && $this->validate()) {
			$this->modelZone->delete_zone();
			$this->modelZone->update_zone();
			$this->session->set('message', $this->language->get('text_message'));

			$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		}

		$view = $this->locator->create('template');

		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));		

		$view->set('text_none', $this->language->get('text_none'));
		$view->set('text_enabled', $this->language->get('text_enabled'));
		$view->set('text_disabled', $this->language->get('text_disabled'));
		$view->set('text_instruction',$this->language->get('text_instruction'));
		$view->set('text_zone_info', $this->language->get('text_zone_info'));
		
		$view->set('entry_module_status', $this->language->get('entry_module_status'));
		$view->set('entry_status', $this->language->get('entry_status'));
		$view->set('entry_cost', $this->language->get('entry_cost'));
		$view->set('entry_tax', $this->language->get('entry_tax'));
		$view->set('entry_sort_order', $this->language->get('entry_sort_order'));
		$view->set('entry_geo_zone', $this->language->get('entry_geo_zone'));
		$view->set('entry_free_amount', $this->language->get('entry_free_amount'));
		$view->set('entry_message', $this->language->get('entry_message'));
		
		$view->set('button_list', $this->language->get('button_list'));
		$view->set('button_insert', $this->language->get('button_insert'));
		$view->set('button_update', $this->language->get('button_update'));
		$view->set('button_delete', $this->language->get('button_delete'));
		$view->set('button_save', $this->language->get('button_save'));
		$view->set('button_cancel', $this->language->get('button_cancel'));
		$view->set('button_add', $this->language->get('button_add'));
		$view->set('button_remove', $this->language->get('button_remove'));
		$view->set('button_print', $this->language->get('button_print'));

		$view->set('tab_module', $this->language->get('tab_module'));
		$view->set('tab_data', $this->language->get('tab_data'));

		$view->set('error', @$this->error['message']);
		$view->set('action', $this->url->ssl('shipping_zone'));
		$view->set('list', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));
		$view->set('cancel', $this->url->ssl('extension', FALSE, array('type' => 'shipping')));	

		$this->session->set('cdx',md5(mt_rand()));
		$view->set('cdx', $this->session->get('cdx'));
		$this->session->set('validation', md5(time()));
		$view->set('validation', $this->session->get('validation'));
		
		if (!$this->request->isPost()) {
			$results = $this->modelZone->get_zones();
			foreach ($results as $result) {
				$setting_info[$result['type']][$result['key']] = $result['value'];
			}
		}
		
		$geo_zone_data = array();
		$results = $this->modelZone->get_geo_zones();
		foreach ($results as $result) {
			if (!$this->request->isPost()) {
				if(isset($setting_info['global']['zone_' . (int)$result['geo_zone_id'] . '_status'])){
					$geo_zone_data[] = array(
						'geo_zone_id' => $result['geo_zone_id'],
						'name'        => $result['name'],
						'description' => $result['description'],
						'cost'        => @$setting_info['global']['zone_' . (int)$result['geo_zone_id'] . '_cost'],
						'status'      => @$setting_info['global']['zone_' . (int)$result['geo_zone_id'] . '_status'],
						'free_amount' => @$setting_info['global']['zone_' . (int)$result['geo_zone_id'] . '_free_amount'],
						'message'	  => @$setting_info['global']['zone_' . (int)$result['geo_zone_id'] . '_message'],
					);
				}
			} else {
				$geo_zone_info = $this->request->gethtml('geo_zone', 'post');
			
				$geo_zone_data[] = array(
					'geo_zone_id' => $result['geo_zone_id'],
					'name'        => $result['name'],
					'description' => $result['description'],
					'cost'        => $geo_zone_info[$result['geo_zone_id']]['cost'],
					'free_amount' => $geo_zone_info[$result['geo_zone_id']]['free_amount'],
					'status'      => $geo_zone_info[$result['geo_zone_id']]['status'],
					'message'	  => $geo_zone_info[$result['geo_zone_id']]['message']
				);			
			}
		}
		
		$view->set('geo_zones', $geo_zone_data);
		
		
		if ($this->request->has('global_zone_tax_class_id', 'post')) {
			$view->set('global_zone_tax_class_id', $this->request->gethtml('global_zone_tax_class_id', 'post'));
		} else {
			$view->set('global_zone_tax_class_id', @$setting_info['global']['zone_tax_class_id']);
		}
		
		if ($this->request->has('global_zone_sort_order', 'post')) {
			$view->set('global_zone_sort_order', $this->request->gethtml('global_zone_sort_order', 'post'));
		} else {
			$view->set('global_zone_sort_order', @$setting_info['global']['zone_sort_order']);
		}
		
		if ($this->request->has('global_zone_status', 'post')) {
			$view->set('global_zone_status', $this->request->gethtml('global_zone_status', 'post'));
		} else {
			$view->set('global_zone_status', @$setting_info['global']['zone_status']);
		}	

		$view->set('tax_classes', $this->modelZone->get_tax_classes());

		$this->template->set('content', $view->fetch('content/shipping_zone.tpl'));

		$this->template->set($this->module->fetch());

		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function addzone(){
		$geozone_id = $this->request->gethtml('geozone_id');
		$geozone = $this->modelZone->get_geozone($geozone_id);
		$output = '<tr id="geozone' . $geozone_id . 'A">' . "\n";
		$output .= '<td width="160" class="set">' . $geozone['name'] . '</td>' . "\n";
		$output .= '<td><select name="geo_zone[' . $geozone_id . '][status]">' . "\n";
		$output .= '<option value="1">' . $this->language->get('text_enabled') . '</option>' . "\n";
		$output .= '<option value="0" selected>' . $this->language->get('text_disabled') . '</option>' . "\n";
		$output .= '</select></td>' . "\n";
		$output .= '<td><input size="8" type="text" name="geo_zone[' . $geozone_id . '][free_amount]" value="0.00"></td>' . "\n";
		$output .= '<td><input size="71" type="text" name="geo_zone[' . $geozone_id . '][cost]" value=""></td>' . "\n";
		$output .= '</tr>';
		$output .= '<tr id="geozone' . $geozone_id . 'B">' . "\n";
		$output .= '<td width="160" class="set">' . $geozone['description'] . '</td>' . "\n";
		$output .= '<td colspan="3"><input size="100" type="text" name="geo_zone[' . $geozone_id . '][message]" value=""></td>' . "\n";
		$output .= '<td><input type="button" class="button" value="' . $this->language->get('button_remove') . '" onclick="removeZone(' . "'geozone" . $geozone_id . "');" . '"></td>' . "\n";
		$output .= '</tr>';
		$output .= '<tr id="geozone' . $geozone_id . 'C">' . "\n"; 
		$output .= '<td colspan="5"><hr style="color: #EEEEEE;"></td>' . "\n"; 
		$output .= '</tr>';
		
		
		$this->response->set($output);
	}
	
	function getzones(){
		$geo_zones = $this->modelZone->get_geo_zones();
		$output = '<tr id="geo_select"><td colspan="2">' . "\n";
		$output .= '<select id="temp_select" onchange="addzone();">' . "\n";
		$output .= '<option value="">' . $this->language->get('text_select_geozone') . '</option>' . "\n";
		foreach ($geo_zones as $geozone){
			$output .= '<option value="' . $geozone['geo_zone_id'] . '">';
			$output .= $geozone['name'] . ' : ' . $geozone['description'] . '</option>' . "\n";
		}
		$output .='</select></td></tr>' . "\n";
		$this->response->set($output);
	}

	function validate() {
		if(($this->session->get('validation') != $this->request->sanitize($this->session->get('cdx'),'post')) || (strlen($this->session->get('validation')) < 10)){
			$this->error['message'] = $this->language->get('error_referer');
		}
		$this->session->delete('cdx');
		$this->session->delete('validation');
		if (!$this->user->hasPermission('modify', 'shipping_zone')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}

	function install() {
		if ($this->user->hasPermission('modify', 'shipping_zone')) {		
			$this->modelZone->delete_zone();
			$this->modelZone->install_zone();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));		
	}
	
	function uninstall() {
		if ($this->user->hasPermission('modify', 'shipping_zone')) {
			$this->modelZone->delete_zone();
			$this->session->set('message', $this->language->get('text_message'));
		} else {
			$this->session->set('error', $this->language->get('error_permission'));
		}	

		$this->response->redirect($this->url->ssl('extension', FALSE, array('type' => 'shipping')));
	}
}
?>