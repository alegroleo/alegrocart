<?php
class User {
	var $data        = array();
  	var $permissions = array();

  	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->request  =& $locator->get('request');
		$this->session  =& $locator->get('session');
					
    	if ($this->session->has('user_id')) {
			$this->data = $this->database->getRow("select * from user where user_id = '" . (int)$this->session->get('user_id') . "'");
			
			if ($this->data) {  
	  			$sql = "update user set ip = '?' where user_id = '?'";
      			$this->database->query($this->database->parse($sql, $_SERVER['REMOTE_ADDR'], $this->session->get('user_id')));

      			$user_group = $this->database->getRow("select distinct ug.permission from user u left join user_group ug on u.user_group_id = ug.user_group_id where u.user_id = '" . (int)$this->session->get('user_id') . "'");

	  			foreach (unserialize($user_group['permission']) as $key => $value) {
	    			$this->permissions[$key] = $value;
	  			}
			} else {
				$this->logout();
			}
    	}
  	}
		
  	function login($username, $password) {
		$sql       = "select * from user where username = '?' and password = '?'";
    	$user_info = $this->database->getRow($this->database->parse($sql, $username, md5($password)));

    	if ($user_info) {
	  		$this->session->set('user_id', $user_info['user_id']);
	  
      		$this->data = $user_info;

      		$user_group = $this->database->getRow("select distinct ug.permission from user u left join user_group ug on u.user_group_id = ug.user_group_id where u.user_id = '" . (int)$user_info['user_id'] . "'");

	  		foreach (unserialize($user_group['permission']) as $key => $value) {
	    		$this->permissions[$key] = $value;
	  		}
				
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}

  	function logout() {
		$this->session->delete('user_id');
	
		$this->data = array();
  	}

  	function hasPermission($key, $value) {
		if ($this->isSuperAdmin()) { return TRUE; }
    	if (isset($this->permissions[$key])) {
			if (in_array('*', $this->permissions[$key])) { return TRUE; }
	  		return in_array($value, $this->permissions[$key]);
		} else {
	  		return FALSE;
		}
  	}

	function isSuperAdmin($id=false) {
		if (!$id && $this->getId()) { $id=$this->getId(); }
		if (defined('SUPER_ADMIN') && $id == SUPER_ADMIN) {
			return TRUE;
		}
	}
  
  	function isLogged() {
    	return !empty($this->data);
  	}
  
  	function getId() {
    	return (isset($this->data['user_id']) ? $this->data['user_id'] : NULL);
  	}
	
  	function getUserName() {
    	return (isset($this->data['username']) ? $this->data['username'] : NULL);
  	}	
}
?>