<?php

define('E_MAX_FOWARDS','Error: Maximum number (4) of forwards has been exceeded!');
define('REGEX_CLASS','/[^a-z0-9]/i');

class Controller {
	var $model;
	var $directory;
	var $default;
	var $error;
	var $pre_action = array();

	function __construct(&$locator) {
		$this->locator =& $locator;
		$this->model =& $this->locator->get('model');
	}
	
	function setDirectory($directory) {
		$this->directory = $directory;
	}
		
	function setDefault($class, $method) {
		$this->default = $this->forward($class, $method);
	}
	
	function setError($class, $method) {
		$this->error = $this->forward($class, $method);
	}
	
	function addPreAction($class, $method) {
		$this->pre_action[] = $this->forward($class, $method);
	}
	
	function forward($class, $method) {
		$action = array(
			'class'  => $class,
			'method' => $method
		);
		return $action;
  	}
	
  	function dispatch(&$request) {
		$action = $this->requestHandler($request);
		$i = 0;
		while ($action) {
			if ($i > 4) { exit(E_MAX_FOWARDS); }
			$i++;
			foreach ($this->pre_action as $pre_action) {
				$result = $this->execute($pre_action);	
				if ($result) {
					$action = $result;
					break;
				}
			}
			$action = $this->execute($action);
		}
  	}
	
	function execute($action) {
		$file = $this->directory.basename($action['class']).'.php';
		if (file_exists($file)) {
			require_once($file);
			$controller = 'Controller'.preg_replace(REGEX_CLASS, NULL, $action['class']);
			$class = new $controller($this->locator);
			if (method_exists($class, $action['method'])) {
				return $class->{$action['method']}($this->locator);
			}
		}
		if ($this->error) { return $this->error; }
	}
	
	function requestHandler(&$request) {
	    return $this->forward($this->getClass($request), $this->getMethod($request));
	}

	function getClass(&$request) {
	    if ($request->has('controller')) { $class = $request->gethtml('controller'); }
		else { $class=$this->default['class']; }
		return $class;
	}

	function getMethod(&$request) {
	    if ($request->has('action')) { $method = $request->gethtml('action'); }
		else { $method=$this->default['method']; }
		return $method;
	}
}
?>