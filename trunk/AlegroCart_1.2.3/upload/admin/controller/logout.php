<?php      
class ControllerLogout extends Controller {   
	function index() { 
		$response =& $this->locator->get('response');
		$url      =& $this->locator->get('url'); 
		$user     =& $this->locator->get('user');
	 
    	$user->logout();

		$response->redirect($url->ssl('login'));
  	}
}  
?>