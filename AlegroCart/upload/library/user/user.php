<?php
class User {

	private $data		= array();
	private $permissions	= array();

	public function __construct(&$locator) {
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

					$this->database->query("set @modifier_id='" . (int)$this->session->get('user_id') . "'");
					$this->database->query("set @modifier_title='user'");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($username, $password) {
		$sql		= "select * from user where username = '?' and password = '?'";
		$user_info	= $this->database->getRow($this->database->parse($sql, $username, md5($password)));

		if ($user_info) {
			$this->session->set('user_id', $user_info['user_id']);
			$this->data = $user_info;

			$user_group = $this->database->getRow("select distinct ug.permission from user u left join user_group ug on u.user_group_id = ug.user_group_id where u.user_id = '" . (int)$user_info['user_id'] . "'");

			foreach (unserialize($user_group['permission']) as $key => $value) {
				$this->permissions[$key] = $value;
			}

			$sql2 = "INSERT INTO login SET user_id = '?', ip = '?', date_added = now()";
			$this->database->query($this->database->parse($sql2, $user_info['user_id'] , isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''));

			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function logout() {
		$this->session->delete('user_id');
		$this->data = array();
	}

	public function countUser(){
		$sql = "SELECT COUNT(session_id) AS total FROM `session` WHERE `expire` > '?'";
		$result = $this->database->getRow($this->database->parse($sql, time()));
		return $result['total'];
	}

	public function hasPermission($key, $value) {
		if ($this->isSuperAdmin()) { return TRUE; }
		if (isset($this->permissions[$key])) {
			if (in_array('*', $this->permissions[$key])) { return TRUE; }
			return in_array($value, $this->permissions[$key]);
		} else {
			return FALSE;
		}
	}

	public function isSuperAdmin($id=false) {
		if (!$id && $this->getId()) { $id=$this->getId(); }
		if (defined('SUPER_ADMIN') && $id == SUPER_ADMIN) {
			return TRUE;
		}
	}

	public function isLogged() {
		return !empty($this->data);
	}

	public function getId() {
		return (isset($this->data['user_id']) ? $this->data['user_id'] : NULL);
	}

	public function getUserName() {
		return (isset($this->data['username']) ? $this->data['username'] : NULL);
	}

	public function getFullName($order = 'FL', $comma = false) {
		$firstname = isset($this->data['firstname']) ? $this->data['firstname'] : NULL;
		$lastname = isset($this->data['lastname']) ? $this->data['lastname'] : NULL;
		$separator = !$comma ? ' ' : ', ';
		return ($order = 'FL' ? $firstname.$separator.$lastname : $lastname.$separator.$firstname);
	}

	public function getEmail() {
		return (isset($this->data['email']) ? $this->data['email'] : NULL);
	}

	public function getTelephone() {
		return (isset($this->data['telephone']) ? $this->data['telephone'] : NULL);
	}

	public function getMobile() {
		return (isset($this->data['mobile']) ? $this->data['mobile'] : NULL);
	}

	public function getFax() {
		return (isset($this->data['fax']) ? $this->data['fax'] : NULL);
	}

	public function getMonogram() {
		return (isset($this->data['monogram']) ? $this->data['monogram'] : NULL);
	}

	public function getPosition() {
		return (isset($this->data['position']) ? $this->data['position'] : NULL);
	}
}
?>
