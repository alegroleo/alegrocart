<?php

// Installed?
if (filesize('../config.php') == 0) { header('Location: ../install/index.php'); exit; }
define('VALID_ACCESS', TRUE);
define('APP','ADMIN');

// Include Config and Common
require('../config.php');
require('../common.php');

// Page Time
$time = (time() + microtime(true)); //force microtime to return a float instead of a string

// Locator
require(DIR_LIBRARY . 'locator.php');
$locator = new Locator();

// Config
$config =& $locator->get('config');

// Database
$database =& $locator->get('database');

// Settings
$settings = $database->getRows("select * from (setting) where (type = 'admin' or type = 'global')");
foreach ($settings as $setting) { $config->set($setting['key'], $setting['value']); }
date_default_timezone_set($config->get('config_time_zone') ? $config->get('config_time_zone') : 'UTC');
if($config->get('error_handler_status')){
	$error_handler = & $locator->get('errorhandler');
	set_error_handler(array(&$error_handler, "handler"));
}

// Upgrade check
$version=defined('CODE_VERSION')?CODE_VERSION:0;
$upgrade = $database->getRows("select * from setting where `type` = 'global' and `group` = 'version' and `key` = 'version' and `value` = '$version'");
if (!$upgrade) { header('Location: ../install/upgrade.php'); exit; }

$session =& $locator->get('session');

// Language
$language =& $locator->get('language');

// Template
$template =& $locator->get('template');

// Character Set
$template->set('charset', $language->get('charset'));

// Text Direction
$template->set('direction', $language->get('direction'));

// Language Code
$template->set('code', $language->get('code'));

// Request
$request =& $locator->get('request');
if((!isset($_SERVER["HTTPS"])  || $_SERVER["HTTPS"] != "on") && $config->get('config_ssl')){
			header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		}
// Base URL
$template->set('base', $request->isSecure()?HTTPS_SERVER:HTTP_SERVER);

// Response
$response =& $locator->get('response');

// Controller
$controller =& $locator->get('controller');

// Controller Directory
$controller->setDirectory(DIR_CONTROLLER);

// Default Controller
$controller->setDefault('home', 'index');

// Error Controller
$controller->setError('error', 'index');

// Login
$controller->addPreAction('login', 'isLogged');

// Permission
$controller->addPreAction('permission', 'hasPermission');

// Dispatch
$controller->dispatch($request);

// Output
$response->output();

// Parse Time
if ($config->get('config_parse_time')){
	echo($language->get('text_time', round((time() + microtime(true)) - $time, 4)));
}
if ($config->get('config_query_count')) {
	echo($language->get('text_query_count', $database->countQueries()));
}
if ($config->get('config_query_log')) {
	$database->log_queries();
}
?>
