<?php //AdminModelServerInfo AlegroCart
class Model_Admin_Server_Info extends Model {
	
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
	}
	function get_server_info(){
		return $this->database->server_info();
	}
}
?>
