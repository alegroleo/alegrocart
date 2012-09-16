<?php

// Installed?
if (filesize('config.php') == 0) { exit; }
require_once('library/application/string_modify.php');
define('VALID_ACCESS', TRUE);
define('APP','CATALOG');

// Config
require('config.php');
require('common.php');

// Locator
require(DIR_LIBRARY . 'locator.php');
$locator = new Locator();

// Config
$config =& $locator->get('config');

// Database
$database =& $locator->get('database');
$database->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Settings
$sql="SELECT * FROM setting WHERE type = 'catalog' OR type = 'global'";
$settings = $database->getRows($sql);

foreach ($settings as $setting) {
	$config->set($setting['key'], $setting['value']);
}

$request =& $locator->get('request'); // Request
$url =& $locator->get('url'); // URL

if ($config->get('config_sitemap_status')) {

$base = htmlentities(HTTP_SERVER, ENT_QUOTES, 'UTF-8');

//Output XML
header('Content-type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

//Add the Home Page - http://my_domain/path/
echo '<url>' . "\n";
echo '<loc>' . $base . '</loc>' . "\n";
echo '<changefreq>weekly</changefreq>' . "\n";
echo '<priority>1.0</priority>' . "\n";
echo '</url>' . "\n";

if ($config->get('config_url_alias')) {

	//Add Products
	$sql = "SELECT `alias` FROM `url_alias` WHERE `query` LIKE 'controller=product&product_id=%'";
	$results = $database->getRows($sql);
	foreach($results as $result) {
	
	echo '<url>' . "\n";
	echo '<loc>' . $base . $result['alias'] . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>1.0</priority>' . "\n";
	echo '</url>' . "\n";
	}

	//Add Manufacturers, Categories and Subcategories
	$sql = "SELECT `alias` FROM `url_alias` WHERE `query` LIKE 'controller=manufacturer&manufacturer_id=%' OR `query` LIKE 'controller=category&path=%' ";
	$results = $database->getRows($sql);
	foreach($results as $result) {
	$priority = '0.' . rand(6,9);
	echo '<url>' . "\n";
	echo '<loc>' . $base . $result['alias'] . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>' . $priority . '</priority>' . "\n";
	echo '</url>' . "\n";
	}

	//Add Products in Manufacturers and Products in Categories or Subcategories 
	$sql = "SELECT `alias` FROM `url_alias` WHERE `query` LIKE 'controller=product&manufacturer_id=%' OR `query` LIKE 'controller=product&path=%' ";
	$results = $database->getRows($sql);
	foreach($results as $result) {
	$priority = '0.' . rand(8,9);
	echo '<url>' . "\n";
	echo '<loc>' . $base . $result['alias'] . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>' . $priority . '</priority>' . "\n";
	echo '</url>' . "\n";
	}

} else {

	//Add Products - ?controller=product&product_id=XX
	$sql = "SELECT `product_id` FROM `product` WHERE `status` = '1' AND `date_available` < now()";
	$results = $database->getRows($sql);
	foreach($results as $result) {
	
	echo '<url>' . "\n";
	echo '<loc>' . str_replace('&', '&amp;', $url->href('product', NULL, array('product_id' => $result['product_id']))) . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>1.0</priority>' . "\n";
	echo '</url>' . "\n";
	}

	//Add Categories and Subcategories - ?controller=category&path=XX and ?controller=category&path=XX_YY
	$sql = "SELECT `path`, `category_id` FROM `category`";
	$results = $database->getRows($sql);

	foreach($results as $result) {
	$priority = '0.' . rand(6,9);
	echo '<url>' . "\n";
	echo '<loc>' . str_replace('&', '&amp;', $url->href('category', NULL, array('path' => $result['path']))) . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>' . $priority . '</priority>' . "\n";
	echo '</url>' . "\n";

		//Add Products in this Category or Subcategory - ?controller=product&path=XX&product_id=ZZ and ?controller=product&path=XX_YY&product_id=ZZ
		$sql2 = "SELECT `product_to_category`.`product_id` FROM `product_to_category` INNER JOIN `product` ON `product_to_category`.`product_id`=`product`.`product_id` WHERE `product_to_category`.`category_id`=".$result['path']." AND `product`.`status` = '1' AND `product`.`date_available` < now()";
		$products = $database->getRows($sql2);

		foreach($products as $product) {
		$priority = '0.' . rand(8,9);
		echo '<url>' . "\n";
		echo '<loc>' . str_replace('&', '&amp;', $url->href('product', NULL, array('path' => $result['path'],'product_id' => $product['product_id']))) . '</loc>' . "\n";
		echo '<changefreq>weekly</changefreq>' . "\n";
		echo '<priority>' . $priority . '</priority>' . "\n";
		echo '</url>' . "\n";
		}
	}

	//Add Manufacturers - ?controller=manufacturer&manufacturer_id=XX
	$sql = "SELECT `manufacturer_id` FROM `manufacturer`";
	$results = $database->getRows($sql);

	foreach($results as $result) {
	$priority = '0.' . rand(6,9);
	echo '<url>' . "\n";
	echo '<loc>' . str_replace('&', '&amp;', $url->href('manufacturer', NULL, array('manufacturer_id' => $result['manufacturer_id']))) . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>' . $priority . '</priority>' . "\n";
	echo '</url>' . "\n";
	}

	//Add Products in Manufacturers - ?controller=product&manufacturer_id=XX&product_id=YY
	$sql = "SELECT `product_id`, `manufacturer_id` FROM `product` WHERE `status` = '1' AND `date_available` < now() AND `manufacturer_id` != '0'";
	$results = $database->getRows($sql);
	
	foreach($results as $result) {
	$priority = '0.' . rand(8,9);
	echo '<url>' . "\n";
	echo '<loc>' . str_replace('&', '&amp;', $url->href('product', NULL, array('manufacturer_id' => $result['manufacturer_id'],'product_id' => $result['product_id']))) . '</loc>' . "\n";
	echo '<changefreq>weekly</changefreq>' . "\n";
	echo '<priority>' . $priority . '</priority>' . "\n";
	echo '</url>' . "\n";
	}
}

echo '</urlset>';
}
?>
