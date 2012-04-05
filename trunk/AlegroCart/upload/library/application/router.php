<?php
class Router {
	var $routes = array();
	
	function __construct(&$locator) {
        $this->database =& $locator->get('database'); 
	}
	
	function setRoutes($routes) {
		$this->routes = $routes;
	}
	
	function route(&$request) {
		$path = $this->getPath();
		$path = explode('&', $path);
		$pathsize=sizeof($path);
		for($i=0;$i < $pathsize;$i++) {
			$route = explode('=',$path[$i]);
			if (!empty($route[0]) && !empty($route[1])) $request->set($route[0], $route[1]);
		}
	}

	function getPath() {
		$path='';
        if (isset($_SERVER['REQUEST_URI'])) {
	        $path=$_SERVER['REQUEST_URI'];
			$base=(strpos($path, '.php') !== FALSE)?$_SERVER['SCRIPT_NAME']:dirname($_SERVER['SCRIPT_NAME']);
	        if (strlen($base) > 1) $path=substr($path, strlen($base));
        }
		//Remove the ? from the query string %013
		$path=trim($path, '?');
		if(strstr($path,'http')){$path = substr($path,0,strpos($path, 'http'));} // Remove bogus urls from query string
		//$path = preg_replace('~(../|\r\n|\r|\n)~','',$path);
		$path = str_replace('../','',$path);
		if (substr($path,0,1) == '/') {
			//Remove query string, we don't need it for routing
			$query_string=parse_url($path,PHP_URL_QUERY);
			$path=str_replace('?'.$query_string,'',$path);

			$path=trim($path, '/');

			//URL Alias
			$alias=$this->getAlias($path);
			if ($alias) return $alias;

			//Remastered URLs In
			$path=$this->Remaster($path);
		}

		return $path;
	}

	function getAlias($path) {
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

	function Remaster($path) {
		$query=(strstr($path,'/'))?explode('/', $path):$path;
		$path='controller=';
		if (is_array($query)) {
			//shift the first one as the controller
			$path.=array_shift($query);
			//rebuild the query string from the keys and values
			//can't use http_build_query() here, not correct array format.
			$node='&';
			foreach ($query as $q) {
				$path .= $node.$q;
				$node=($node == '&')?'=':'&';
			}
		}
		//no array to process, must just be the controller
		else { $path .= $query; }
		$path = trim($path, '/');
		return $path;
	}
}
?>
