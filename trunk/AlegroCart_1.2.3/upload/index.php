<?php

// Installed?
if (filesize('config.php') == 0) { header('Location: install/index.php'); exit; }

define('APP','CATALOG');

// Include Config and Common
require('config.php');
require('common.php');

// Page Time
$time = (time() + microtime());

// Locator
require(DIR_LIBRARY . 'locator.php');
$locator = new Locator();

// Config
$config =& $locator->get('config');

// Database
$database =& $locator->get('database');

// Settings
$settings = $database->getRows("select * from (setting) where (type = 'catalog' or type = 'global')");

foreach ($settings as $setting) { $config->set($setting['key'], $setting['value']); }

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

// Nosript
$template->set('noscript', $language->get('noscript'));

// Request
$request =& $locator->get('request');

// Base URL
$template->set('base', $request->isSecure()?HTTPS_SERVER:HTTP_SERVER);

// Response
$response =& $locator->get('response');

// Controller
$controller =& $locator->get('controller');
	
// Router
$router =& $locator->get('router');

// Controller Directory
$controller->setDirectory(DIR_CONTROLLER);

// Default Controller
$controller->setDefault('home', 'index');

// Error Controller
$controller->setError('error', 'index');

// Maintenance Mode
$controller->addPreAction('maintenance', 'CheckMaintenance');

// Route Request
$router->route($request);

// Dispatch
$controller->dispatch($request);

// Output
$response->output();

// Parse Time
if ($config->get('config_parse_time')) echo($language->get('text_time', round((time() + microtime()) - $time, 4)));
?>