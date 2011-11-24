<?php

// Installed?
if (filesize('config.php') == 0) { exit; }

require_once('library/application/string_modify.php');
define('VALID_ACCESS', TRUE);
define('APP','CATALOG');

// Include Config and Common
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
$sql="select * from setting where type = 'catalog' or type = 'global'";
$settings = $database->getRows($sql);

foreach ($settings as $setting) {
	$config->set($setting['key'], $setting['value']);
}
date_default_timezone_set($config->get('config_time_zone') ? $config->get('config_time_zone') : 'UTC');
$image =& $locator->get('image'); // Image
$request =& $locator->get('request'); // Request
$url =& $locator->get('url'); // URL
$language =& $locator->get('language'); // Language
$currency =& $locator->get('currency'); //Currency
$tax      =& $locator->get('tax'); // Tax
$weight =& $locator->get('weight');
$limit = $config->get('config_rss_limit') ? $config->get('config_rss_limit') : 20;
$rss_source = $config->get('config_rss_source') ? $config->get('config_rss_source') : 'rss_latest';

// Base URL
$catalog_url = $request->isSecure()?HTTPS_SERVER:HTTP_SERVER;
$image_url = $request->isSecure()?HTTPS_IMAGE:HTTP_IMAGE;

// Product Data
$product_data = array();
switch($rss_source){
	case 'rss_latest':
		$sql="select *, p.date_added as date_product_added from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '%s' and p.date_available < now() and p.status = '1' order by date_product_added desc limit " . $limit;
		break;
	case 'rss_featured':
		$sql="select *, p.date_added as date_product_added from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '%s' and p.date_available < now() and p.status = '1' and p.featured = '1' order by date_product_added desc limit " . $limit;
		break;
	case 'rss_specials':
		$sql="select *, p.date_added as date_product_added from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '%s' and p.date_available < now() and p.status = '1' and p.special_offer = '1' order by date_product_added desc limit " . $limit;
		break;
	case 'rss_popular':
		$sql="select *, p.date_added as date_product_added from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '%s' and p.date_available < now() and p.status = '1' order by viewed desc limit " . $limit;
		break;
}

$sql=sprintf($sql,(int)$language->getId());
$results = $database->getRows($sql);

$products=array();
foreach ($results as $result) {
	$manufacturer = $database->getRow("select * from manufacturer where manufacturer_id = '" . (int)$result['manufacturer_id'] . "'");
	$products[]=array(
	'name' => strip_tags($result['name']),
	'url' => $url->href('product', FALSE, array('product_id' => $result['product_id'])),
	'add_date' => date("D, d M Y H:i:s T", strtotime($result['date_product_added'])),
	'desc' => htmlentities(strip_tags(strippedstring($result['description'],256),'ENT_QUOTES')),
	'thumb' => $image->resize($result['filename'], 200, 200),
	'id' => $result['product_id'],
	'price' => $currency->format($tax->calculate($result['price'], $result['tax_class_id'], $config->get('config_tax'))),
	'model' => htmlentities($result['model']),
	'manufacturer' => $manufacturer['name'],
	'weight' => $weight->format($weight->convert($result['weight'],$result['weight_class_id'], $config->get('config_weight_class_id')),$config->get('config_weight_class_id'))
	);
}

header('Content-type: text/xml');
echo '<?xml version="1.0"?>';
?>
<rss version="2.0"
xmlns:g="http://base.google.com/ns/1.0">
<channel>
	<title><?php echo $config->get('config_store'); ?></title>
	<description><?php echo $config->get('config_store'); ?></description>
	<link><?php echo $catalog_url; ?></link>

<?php foreach ($products as $product) { ?>
	<item>
        <title><?php echo $product['name']; ?></title>
		<g:brand><?php echo $product['manufacturer']; ?></g:brand>
		<g:condition>new</g:condition>
        <description><?php echo $product['desc']; ?></description>
        <link><?php echo $product['url']; ?></link>
        <g:id><?php echo $product['id']; ?></g:id>
        <g:image_link><?php echo $product['thumb']; ?></g:image_link>
		<g:price><?php echo $product['price']; ?></g:price>
		<g:poduct_type><?php echo $product['model']; ?></g:poduct_type>
		<g:currency><?php echo $config->get('config_currency'); ?></g:currency>
	</item>
<?php } ?>
</channel>
</rss>