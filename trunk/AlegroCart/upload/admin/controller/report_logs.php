<?php // Report Logs AlegroCart
class ControllerReportLogs extends Controller {
 	function __construct(&$locator){
		$this->locator 		=& $locator;
		$model 				=& $locator->get('model');
		
		$this->ccvalidation =& $locator->get('ccvalidation');
		$this->config  		=& $locator->get('config');
		$this->language 	=& $locator->get('language');
		$this->module   	=& $locator->get('module');
		$this->request  	=& $locator->get('request');
		$this->response 	=& $locator->get('response');
		$this->session 		=& $locator->get('session');
		$this->template 	=& $locator->get('template');
		$this->url      	=& $locator->get('url');
		$this->modelReportLogs = $model->get('model_admin_report_logs');
		
		$this->language->load('controller/report_logs.php');
	}
	function index() { 
		$this->template->set('title', $this->language->get('heading_title'));
		
		$view = $this->locator->create('template');
		$view->set('heading_title', $this->language->get('heading_title'));
		$view->set('heading_description', $this->language->get('heading_description'));
		$view->set('text_yes', $this->language->get('text_yes'));
		$view->set('text_no', $this->language->get('text_no'));
		$view->set('text_dycrypt_exp', $this->language->get('text_dycrypt_exp'));

		$view->set('text_select_dir', $this->language->get('text_select_dir'));
		$view->set('entry_dir', $this->language->get('entry_dir'));
		$view->set('entry_decrytion', $this->language->get('entry_decrytion'));
		$view->set('button_submit',$this->language->get('button_submit'));
		
		$view->set('action', $this->url->ssl('report_logs'));
		
		$view->set('decrytion', $this->request->gethtml('decrytion', 'post'));
		$view->set('log_directory', $this->request->gethtml('directory', 'post'));
		
		$view->set('continue', $this->url->ssl('report_logs'));
		
		$log_dirs = glob(DIR_LOGS.'*',GLOB_ONLYDIR);
		foreach($log_dirs as $log_dir){
			$log_directories[] = array(
				'directory' => str_replace(DIR_LOGS, '', $log_dir),
				'dir_path'  => $log_dir
			);
		}
		$view->set('log_directories', $log_directories);
		
		if($this->request->has('directory', 'post')){
			$view->set('log_files', $this->get_logs($this->request->gethtml('directory', 'post')));
		}
		
		$view->set('log_file', $this->get_file());
		$log_print = $this->request->gethtml('log_print', 'post');
		$view->set('log_print', $log_print);
		
		$this->template->set('content', $view->fetch('content/report_logs.tpl'));
		if(!$log_print){
			$this->template->set($this->module->fetch());
		}
		
		$this->response->set($this->template->fetch('layout.tpl'));
	}
	
	function get_file(){
		$file = '';
		if($this->request->gethtml('file_path', 'post')){
			$file = file_get_contents($this->request->gethtml('file_path', 'post'));
		}
		if($this->request->gethtml('decrytion', 'post')){
			$file = $this->ccvalidation->deCrypt($file, $this->config->get('config_token'));
		}
		if($file){
			$file = str_replace(array("\r\n", "\r", "\n"),'<br>', $file);
		}
		return $file;
	}
	
	function get_files(){
		if($this->request->gethtml('directory')){
			$output = $this->get_logs($this->request->gethtml('directory'));
		} else {
			$output = '';
		}
		$this->response->set($output);
	}
	
	function get_logs($directory){
		$path = DIR_LOGS.$directory.D_S;
		$files = glob($path . '*.txt');
		if (!$files) { return; }
		foreach($files as $file) {
			$files_data[] = array(
				'log'		=> str_replace($path, '',$file),
				'log_path'	=> $file
			);
		}
		$output = '<tr>' . "\n";
		$output .= '<td style="width:185px;" class="set">' . $this->language->get('entry_file') . '</td>' . "\n";
		$output .= '<td><select id="file_name" name="file_path">' . "\n";
		$output .= '<option value="">'. $this->language->get('text_select_file').'</option>' . "\n";
		foreach($files_data as $file_data){
			$output .= '<option value="' . $file_data['log_path'] . '"';
			if($file_data['log_path'] == $this->request->gethtml('file_path', 'post')){
				$output .= ' selected';
			}
			$output .= '>'.$file_data['log'].'</option>' . "\n";
		}
		$output .= '</select></td></tr>' . "\n";
		return $output;
	}

}
?>