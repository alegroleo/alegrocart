<?php 
class Url { 
	var $data = array();

	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		if ($this->config->get('config_url_alias') && (!preg_match('#/admin/#i',$_SERVER['PHP_SELF']))) {
	  		$results = $this->database->cache('url', "select * from url_alias");
	
	  		foreach ($results as $result) {
	    		$this->data[htmlspecialchars_decode($result['query'])] = htmlspecialchars_decode($result['alias']);
	  		} 	  
    	}
  	}

	function requested($url) {
		if (isset($_SERVER['REQUEST_URI'])) { return htmlspecialchars($_SERVER['REQUEST_URI']); }
		else { return $url; }
	}
	
	function validate_referer(){
		if(strstr($this->referer(''),$this->get_server())){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function referer($url) {
		if (isset($_SERVER['HTTP_REFERER'])) { return htmlspecialchars($_SERVER['HTTP_REFERER']); }
		else { return $url; }
	}

	function current_page(){
		if(isset($_SERVER['HTTP_HOST'])){
		  if((@$_SERVER['HTTPS']) && (defined('HTTPS_SERVER')) && (HTTPS_SERVER)) {
				return htmlspecialchars("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			} else {
				return htmlspecialchars("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
		}
	}
	
	function raw($controller, $action = NULL, $query = array()) {
		return $this->create(HTTP_SERVER, $controller, $action, $query);
  	}

	function href($controller, $action = NULL, $query = array()) {
		return htmlspecialchars($this->create(HTTP_SERVER, $controller, $action, $query));
  	}

  	function ssl($controller, $action = NULL, $query = array()) {
		$server=$this->get_server();
		return htmlspecialchars($this->create($server, $controller, $action, $query));
  	}

	function rawssl($controller, $action = NULL, $query = array()) {
		$server=$this->get_server();
		return $this->create($server, $controller, $action, $query);
  	}

	function get_server() {
		if (($this->config->get('config_ssl')) && (defined('HTTPS_SERVER')) && (HTTPS_SERVER)) {
	  		$server = HTTPS_SERVER;
		} else {
	  		$server = HTTP_SERVER;
		}
		return $server;
	}

	function create($server, $controller, $action = NULL, $query = array()) {
    	$qs = 'controller=' . $controller;

    	if ($action) { $query['action']=$action; }

		foreach ($query as $key => $value) {
	  		if ($value) { $qs .= '&' . $key . '=' . $value; }
		}

		if (isset($this->data[$qs])) {
			$link=$this->data[$qs];
		} else {
	  		$link = '?' . $qs;
			//Remastered URLs Out
			$link=$this->Remaster($link);
		}

		return $server.$link;
	}

	function Remaster($link) {
		if ((!preg_match('#/admin/#i',$_SERVER['PHP_SELF'])) && ($this->config->get('config_url_alias'))) {
			$query_string=parse_url($link,PHP_URL_QUERY); //get query string
			$link=str_replace('?'.$query_string,'',$link); //strip query string
			$query_string=str_replace('controller=','',$query_string); //first one is the controller
			$query_string=str_replace('&','/',$query_string); //key
			$query_string=str_replace('=','/',$query_string); //value
			$link.=$query_string; //sorts out trailing slashing
		}
		return $link;
	}
	function get_controller($referer, $controllers){
		if(stristr($referer,'https')){
			$path = substr($referer,strlen(HTTPS_BASE));
		} else {
			$path = substr($referer,strlen(HTTP_BASE));
		}
		if ($this->config->get('config_url_alias')){
			$alias = $this->getAlias($path);
			if($alias){
				return $this->check_controller($alias, $controllers);

			} else {
				$alias = $this->Rework($path);
				return $this->check_controller($alias, $controllers);
			}
		} else {
			return $this->check_controller($path, $controllers);
		}
		return FALSE;
	}
	function check_controller($path, $controllers){
		foreach($controllers as $controller){
			if(strstr($path, $controller)){
				return TRUE;
			}
		}
		return FALSE;
	}
	function Rework($path) {
		$query=(strstr($path,'/'))?explode('/', $path):$path;
		$path='controller=';
		if (is_array($query)) {
			$path.=array_shift($query);
			$node='&';
			foreach ($query as $q) {
				$path .= $node.$q;
				$node=($node == '&')?'=':'&';
			}
		} else { 
			$path .= $query;
		}
		$path = trim($path, '/');
		return $path;
	}
	function getAlias($path){
		$page = strpos($path,'/page') ? substr($path,strpos($path,'/page/')) : '';
		$page = $page ? '&page=' . (int)str_replace('/page/','',$page): '' ;
		$sql  = "select * from url_alias where alias = '?'";
		if ($page){
			$result = $this->database->getRow($this->database->parse($sql, substr($path,0,strpos($path,'/page/'))));
		} else {
			$result = $this->database->getRow($this->database->parse($sql,$path));
		}
		if ($result) return htmlspecialchars_decode($result['query']).$page;
	}
}
?>
