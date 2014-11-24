<?php //ModelProducts AlegroCart
class Model_Products extends Model {
	function __construct(&$locator) {
		$this->database =& $locator->get('database');
		$this->dimension=& $locator->get('dimension');
		$this->image    =& $locator->get('image');
		$this->language =& $locator->get('language');
		$this->tax      =& $locator->get('tax');
		$this->currency =& $locator->get('currency');
		$this->config   =& $locator->get('config');
		$this->weight	=& $locator->get('weight');
		$this->barcode  =& $locator->get('barcode'); 
		$this->language->load('controller/dimensions.php');
		$this->url      =& $locator->get('url');
		$this->cart     =& $locator->get('cart');
	}
	function get_bestseller($bestseller_total){
		$results = $this->database->getRows("SELECT product.*, order_product.order_product_id, order_product.order_id, SUM(order_product.quantity) as TotalOrdered, product_description.*, image.* FROM product, order_product, order_history, product_description, image WHERE product.product_id = product_description.product_id AND product_description.name = order_product.name AND product.image_id = image.image_id AND product.status ='1' AND product_description.language_id = '" . (int)$this->language->getId() . "' GROUP BY order_product.name ORDER BY TotalOrdered DESC". $bestseller_total);
		return $results;
	}
	function get_trending($trending_total, $trending_days){
		$results = $this->database->getRows("SELECT product.*, order_product.order_product_id, order_product.order_id, SUM(order_product.quantity) as TotalOrdered, product_description.*, image.*, order.date_added FROM product, order_product, order_history, product_description, image, `order` WHERE product.product_id = product_description.product_id AND product_description.name = order_product.name AND product.image_id = image.image_id AND order.order_id = order_product.order_id AND product.status ='1' AND product_description.language_id = '" . (int)$this->language->getId() . "' AND order.date_added >= now() - INTERVAL ".$trending_days." DAY GROUP BY order_product.name ORDER BY TotalOrdered DESC". $trending_total);
		return $results;
	}
	function get_popular($popular_total){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available <= now() order by viewed DESC" . $popular_total);
		return $results;
	}
	function get_featured(){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.featured = '1' order by name asc");  
		return $results;
	}
	function get_specials(){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.special_offer = '1' order by p.date_added desc"); 
		return $results;
	}
	function get_latest($latest_total){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1' order by p.date_added desc" . $latest_total); 
		return $results;
	}
	function get_related($product_id){
		$results = $this->database->getRows( "SELECT * FROM related_products r INNER JOIN product_description pd ON r.related_product_id = pd.product_id INNER JOIN product p ON p.product_id = r.related_product_id LEFT JOIN image i ON i.image_id = p.image_id	WHERE r.product_id = '". (int)$product_id . "' and r.related_product_id = pd.product_id and pd.language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function getRow_product($product_id){
		$result = $this->database->getRow("select * from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and pd.language_id = '" . (int)$this->language->getId() . "' and p.date_available < now() and p.status = '1'");
		return $result;
	}
	function get_recently($recently_products){
		$results = array();
		foreach ($recently_products as $recently_product) {
			$results[] = $this->getRow_product((int)$recently_product);
		}
		return $results;
	}
	function get_product_discount($product_id){
		$results = $this->database->getRows("select * from product_discount where product_id = '" . (int)$product_id . "' order by quantity asc");
		return $results;
	}
	function getRow_manufacturer($manufacturer_id){
		$result = $this->database->getRow("select * from manufacturer where manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $result;
	}
	function get_additional_images($product_id){
		$results = $this->database->getRows("select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) left join product_to_image p2i on (i.image_id = p2i.image_id) where p2i.product_id = '" . (int)$product_id . "' and id.language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_reviews($product_id){
		$results = $this->database->getRows("select r.review_id, r.author, r.rating1, r.rating2, r.rating3, r.rating4, r.text, p.product_id, pd.name, p.price, i.filename, r.date_added from review r left join product p on(r.product_id = p.product_id) left join product_description pd on(p.product_id = pd.product_id) left join image i on(p.image_id = i.image_id) where p.product_id = '" . (int)$product_id . "' and p.date_available < now() and p.status = '1' and r.status = '1' and pd.language_id = '" . (int)$this->language->getId() . "' order by r.date_added desc limit 10");
		return $results;
	}
	function get_dimension_class($dimension_id){
		$result = $this->database->getRow("select title, unit, type_id from dimension where dimension_id = '" . (int)$dimension_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function check_options($product_id){   // Check for Products with Options
		$results = $this->database->getRows("select * from product_to_option where product_id = '" . (int)$product_id . "'");
		$option_status = $results ? TRUE : FALSE;
		return $option_status;
	}
	function get_product_options($product_id){
		$results = $this->database->getRows("select * from product_options po left join image i on (po.image_id = i.image_id) where po.product_id = '" . (int)$product_id . "' order by po.product_option asc");
		return $results;
	}
	function get_option_names($product_id){
		$results = $this->database->getRows("select distinct name from `option` o left join product_to_option pto on (o.option_id = pto.option_id) where product_id = '" . (int)$product_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		switch (count($results)) {
			case 1: 
				$option_names = $this->language->get('one_option_text', $results[0]['name']);
				break;
			case 2: 
				$option_names = $this->language->get('two_option_text', $results[0]['name'], $results[1]['name']);
				break;
			case 3: 
				$option_names = $this->language->get('three_option_text', $results[0]['name'], $results[1]['name'], $results[2]['name']);
				break;
			default:
				$option_names = FALSE;
				break;
		}
		return $option_names;
	}
	function get_option_weight($product_id, $weight_class_id){
		$results = $this->database->getRows("select product_to_option_id, option_weight, option_weightclass_id from product_to_option where product_id = '" . (int)$product_id . "' order by sort_order");
		$option_weight = array();
		foreach ($results as $result) {
			$option_weight[] = array(
				'product_to_option_id'	=> $result['product_to_option_id'],
				'option_weight'		=> $this->weight->convert($result['option_weight'], $result['option_weightclass_id'], $weight_class_id)
			);
		}
		return $option_weight;
	}
	function get_options($product_id, $tax_class_id){  // Get product Options
		$options = array();
		$results = $this->database->getRows("select * from product_to_option where product_id = '" . (int)$product_id . "' order by sort_order");
		foreach ($results as $result) {
			$options[$result['option_id']][] = array(
				'product_to_option_id' => $result['product_to_option_id'],
				'option_value_id'      => $result['option_value_id'],
				'price'                => $result['price'],
				'prefix'               => $result['prefix']
			);
		}
		$option_data = array();
		foreach ($options as $key => $values) {
			$option_value_data = array();
			foreach ($values as $value) {
				$option_value_info = $this->database->getRow("select * from option_value where option_value_id = '" . (int)$value['option_value_id'] . "' and option_id = '" . (int)$key . "' and language_id = '" . (int)$this->language->getId() . "'");
				$option_value_data[] = array(
				'product_to_option_id' => $value['product_to_option_id'],
				'option_value_id'      => $value['option_value_id'],
				'name'                 => $option_value_info['name'],
				'price'                => (($value['price'] != '0.00') ? $this->currency->format($this->tax->calculate($value['price'], $tax_class_id, $this->config->get('config_tax'))) : null),
				'prefix'               => $value['prefix']
			);
			}
			$option = $this->database->getRow("select * from `option` where option_id = '" . (int)$key . "' and language_id = '" . (int)$this->language->getId() . "'");
			$option_data[] = array(
				'option_id' => $key,
				'name'      => $option['name'],
				'value'     => $option_value_data
			);
		}
	return $option_data;
	}
	function update_viewed($product_id){
		$this->database->query("update product set viewed = viewed + 1 where product_id = '" . (int)$product_id . "'");
	}

	function get_downloads($product_id){
		$downloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 0 ' and dd.language_id = '" . (int)$this->language->getId() . "'");

		$download_data = array();

		foreach ($downloads as $download) {
		$download_data[] = array(
			'download_id' => $download['download_id'],
				'name'        => $download['name'],
				'filename'    => $download['filename'],
				'mask'        => $download['mask'],
				'remaining'   => $download['remaining']
		);
		}
		return $download_data;
	
	}

	function get_fdownloads($product_id){
		$fdownloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 1 ' and dd.language_id = '" . (int)$this->language->getId() . "'");
			
		$fdownload_data = array();
			
		foreach ($fdownloads as $fdownload) {
				$size = filesize(DIR_DOWNLOAD . $fdownload['filename']);
				$i = 0;
				$suffix = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}
		$fdownload_data[] = array(
				'download_id' => $fdownload['download_id'],
				'name'        => $fdownload['name'],
				'filename'    => $fdownload['filename'],
				'mask'        => $fdownload['mask'],
				'size'        => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
				'href'        => $this->url->ssl('product', 'download', array('product_id' => $fdownload['product_id'],'download_id' => $fdownload['download_id']))
		);
		}
		return $fdownload_data;
	
	}

	function get_fdownload($product_id, $download_id){
		$result = $this->database->getRow("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 1 ' and dd.language_id = '" . (int)$this->language->getId() . "' and p2d.download_id = '" . (int)$download_id . "'");
		return $result;
	}

	function get_product_with_options($product_id, $image_width = '140', $image_height = '140'){
		$results = $this->get_product_options($product_id);
		$product_options = array();
		foreach($results as $result){
			if($result['dimension_id']){
				$dimension_class = $this->get_dimension_class($result['dimension_id']);
				$dimension_value = $this->dimension->getValues($result['dimension_value'], $dimension_class['type_id'], $result['dimension_id']);
				$dimensions = $this->dimensionView($dimension_class, $dimension_value);
			} else {
				$dimensions = '';
			}

			$product_options[$result['product_option']] = array(
				'product_id'		=> $result['product_id'],
				'product_option'	=> $result['product_option'],
				'quantity'		=> $result['quantity'],
				'barcode'		=> $result['barcode'],
				'barcode_url'		=> $result['barcode'] ? $this->barcode->show($result['barcode']) : NULL,
				'image_id'		=> $result['image_id'],
				'popup'    		=> $result['filename'] ? $this->image->href($result['filename']) : '',
				'thumb'     		=> $result['filename'] ? $this->image->resize($result['filename'], $image_width, $image_height) : '',
				'dimensions'		=> $dimensions,
				'cart_level'		=> $this->cart->hasProduct($result['product_option']),
				'model_number' 		=> $result['model_number'] ? $result['model_number'] : ''
			);
		}
		return $product_options;
	}

	function dimensionView($dimension_class, $dimension_value){
		
		if($dimension_class && $dimension_value){
			$text_dimensions = $this->language->get('text_dimensions');
			$text_shipping = $this->language->get('text_shipping_dim');
			$text_length = $this->language->get('text_length');
			$text_width = $this->language->get('text_width');
			$text_height = $this->language->get('text_height');
			$text_volume = $this->language->get('text_volume');
			$text_area = $this->language->get('text_area');
			switch($dimension_class['type_id']){
				case '1':
					$dimensions = '<b>' . $text_dimensions . '</b>' . ' - ';
					$dimensions .= $text_length . $dimension_value[0] . ', ';
					$dimensions .= $text_width . $dimension_value[1] . ', ';
					$dimensions .= $text_height . $dimension_value[2];
					break;
				case '2':
					$dimensions = '<b>' . $text_dimensions . '</b>' . ' - ';
					$dimensions .= $text_area . $dimension_value[0];
					if(count($dimension_value) > 1){
						$dimensions .= '<br><b>' . $text_shipping . '</b>' . ' - ';
						$dimensions .= $text_length . $dimension_value[1] . ', ';
						$dimensions .= $text_width . $dimension_value[2] . ', ';
						$dimensions .= $text_height . $dimension_value[3];
					}
					break;
				case '3':
					$dimensions = '<b>' . $text_dimensions . '</b>' . ' - ';
					$dimensions .= $text_volume . $dimension_value[0];
					if(count($dimension_value) > 1){
						$dimensions .= '<br><b>' . $text_shipping . '</b>' . ' - ';
						$dimensions .= $text_length . $dimension_value[1] . ', ';
						$dimensions .= $text_width . $dimension_value[2] . ', ';
						$dimensions .= $text_height . $dimension_value[3];
					}
					break;
				default:
					return FALSE;
					break;
			}
		} else {
			return FALSE;
		}
		return $dimensions;
	}
	function currentpage($current_page){
		switch($current_page){
			case '':
			case 'home':
			case 'information':
			case 'sitemap':
			case 'search':
			case 'contact':
			case 'category':
			case 'product':
			case 'manufacturer';
			$add_enable = true;
			break;
		default:
			$add_enable = false;
			break;
		}
		return $add_enable;
	}
	function get_vendor($vendor_id){
		$result = $this->database->getRow("select name from vendor where vendor_id = '" . $vendor_id . "'");
		return $result;
	}
}
?>
