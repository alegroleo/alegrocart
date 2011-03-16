<?php

// REPORT ALL ERRORS
error_reporting(E_ALL);

//error_reporting(-1); Use for development
 
// CODE VERSION
define('CODE_VERSION','1.2.4');

// PATH
define('PATH_CATALOG','catalog');
define('PATH_ADMIN','admin');
define('PATH_IMAGE','image');
define('PATH_FLASH','flash');
define('PATH_LIBRARY','library');
define('PATH_CACHE','cache');
define('PATH_DOWNLOAD','download');
define('PATH_MODEL','model');
define('PATH_CONTROLLER','controller');
define('PATH_EXTENSION','extension');
define('PATH_LANGUAGE','language');
define('PATH_TEMPLATE','template');
define('PATH_STYLES','styles');
define('PATH_INSTALL','install');
define('D_S',DIRECTORY_SEPARATOR);
define('PATH_COMMON','common');

// APP
if (!defined('APP')) { define('APP','CATALOG'); }

// DIR
if (!defined('DIR_CATALOG')) define('DIR_CATALOG',DIR_BASE.PATH_CATALOG.D_S);
if (!defined('DIR_ADMIN')) define('DIR_ADMIN',DIR_BASE.PATH_ADMIN.D_S);
if (!defined('DIR_LIBRARY')) define('DIR_LIBRARY', DIR_BASE.PATH_LIBRARY.D_S);
if (!defined('DIR_CACHE')) define('DIR_CACHE', DIR_BASE.PATH_CACHE.D_S);
if (!defined('DIR_DOWNLOAD')) define('DIR_DOWNLOAD', DIR_BASE.PATH_DOWNLOAD.D_S);
if (!defined('DIR_IMAGE')) define('DIR_IMAGE', DIR_BASE.PATH_IMAGE.D_S);
if (!defined('DIR_FLASH')) define('DIR_FLASH', DIR_BASE.PATH_IMAGE.D_S.PATH_FLASH.D_S);
if (!defined('DIR_APP')) define('DIR_APP', constant('DIR_'.APP));
if (!defined('DIR_MODEL')) define('DIR_MODEL', DIR_APP.PATH_MODEL.D_S);
if (!defined('DIR_CONTROLLER')) define('DIR_CONTROLLER', DIR_APP.PATH_CONTROLLER.D_S);
if (!defined('DIR_LANGUAGE')) define('DIR_LANGUAGE', DIR_APP.PATH_LANGUAGE.D_S);
if (!defined('DIR_EXTENSION')) define('DIR_EXTENSION', DIR_APP.PATH_EXTENSION.D_S);
if (!defined('DIR_TEMPLATE')) define('DIR_TEMPLATE', DIR_APP.PATH_TEMPLATE.D_S);
if (!defined('DIR_CATALOG_STYLES')) define('DIR_CATALOG_STYLES', DIR_CATALOG.PATH_STYLES.D_S);    //New for Version 1.2
if (!defined('DIR_CATALOG_TEMPLATE')) define('DIR_CATALOG_TEMPLATE', DIR_CATALOG.PATH_TEMPLATE.D_S);
if (!defined('DIR_COMMON')) define('DIR_COMMON', DIR_BASE.PATH_LIBRARY.D_S.PATH_COMMON.D_S);

// HTTP
if (!defined('HTTP_CATALOG')) define('HTTP_CATALOG', HTTP_BASE);
if (!defined('HTTP_ADMIN')) define('HTTP_ADMIN', HTTP_BASE.PATH_ADMIN.'/');
if (!defined('HTTP_SERVER')) define('HTTP_SERVER', constant('HTTP_'.APP));
if (!defined('HTTP_IMAGE')) define('HTTP_IMAGE', HTTP_BASE.PATH_IMAGE.'/');
if (!defined('HTTP_FLASH')) define('HTTP_FLASH', HTTP_BASE.PATH_IMAGE.'/'.PATH_FLASH.'/');  //New

// HTTPS
if (!defined('HTTPS_BASE')) define('HTTPS_BASE', '');
if (!defined('HTTPS_CATALOG')) define('HTTPS_CATALOG', HTTPS_BASE);
if (!defined('HTTPS_ADMIN')) define('HTTPS_ADMIN', HTTPS_BASE?HTTPS_BASE.PATH_ADMIN.'/':'');
if (!defined('HTTPS_SERVER')) define('HTTPS_SERVER', constant('HTTPS_'.APP));
if (!defined('HTTPS_IMAGE')) define('HTTPS_IMAGE', HTTPS_BASE?HTTPS_BASE.PATH_IMAGE.'/':'');

// SUPER ADMIN
if (!defined('SUPER_ADMIN')) define('SUPER_ADMIN',1);

// PHP DEFINES
define('PHP_EXT','.'.pathinfo(__FILE__,PATHINFO_EXTENSION));
define('PHP_INDEX','index'.PHP_EXT);

// COMMON INCLUSION (PHP_COMPAT)
$files=glob(DIR_COMMON.'*'.PHP_EXT);
if ($files) {
	foreach ($files as $file) {
		if (basename($file) != PHP_INDEX) { include($file); }
	}
}

?>