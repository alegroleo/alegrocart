<?php //AdminModelProduct AlegroCart
class Model_Admin_Product extends Model {
	function __construct(&$locator) {
		$this->config   =& $locator->get('config');
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
		$this->request  =& $locator->get('request');
		$this->session 	=& $locator->get('session');
		$this->barcode  =& $locator->get('barcode'); 
	}
	function check_product_name($value){
		$result = $this->database->getRow("select name from product_description where name = '". htmlspecialchars_deep($value) ."'");
		return $result;
	}
	function check_barcode($value){
		$result = $this->database->getRow("select barcode from product where barcode = '". htmlspecialchars_deep($value) ."'union select barcode from product_options where barcode = '". htmlspecialchars_deep($value) ."'");
		return $result;
	}
	function check_product_id_name($value){
		$result = $this->database->getRow("select name, product_id from product_description where name = '". htmlspecialchars_deep($value) ."' and product_id != '".(int)$this->request->gethtml('product_id') ."'");
		return $result;
	}
	function check_barcode_id($value, $product_option = ''){
	      $result = $this->database->getRow("select barcode, product_id from product where barcode = '". htmlspecialchars_deep($value) ."' and product_id != '".(int)$this->request->gethtml('product_id') ."'union select barcode, product_option from product_options where barcode = '". htmlspecialchars_deep($value) ."' and product_option != '".$product_option."'");
	      return $result;
	}
	function insert_product(){
	$sql = "insert into product set quantity = '?', barcode = '?', date_available = '?', manufacturer_id = '?', image_id = '?', shipping = '?', price = '?', sort_order = '?', weight = '?', weight_class_id = '?', dimension_id = '?', dimension_value = '?', status = '?', tax_class_id = '?', min_qty = '?', featured = '?', special_offer = '?', related = '?', special_price = '?', sale_start_date = '?', sale_end_date = '?', remaining = '?', date_added = now(), date_modified = now()";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('quantity', 'post'), $this->barcode->check($this->request->gethtml('barcode', 'post'),$this->request->gethtml('encoding', 'post')), date('Y-m-d', strtotime($this->request->gethtml('date_available_year', 'post') . '/' . $this->request->gethtml('date_available_month', 'post') . '/' . $this->request->gethtml('date_available_day', 'post'))), $this->request->gethtml('manufacturer_id', 'post'), $this->request->gethtml('image_id', 'post'), $this->request->gethtml('shipping', 'post'), $this->request->gethtml('price', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('weight', 'post'), $this->request->gethtml('weight_class_id', 'post'), $this->request->gethtml('dimension_id', 'post'), implode(':',$this->request->gethtml('dimension_value', 'post')), $this->request->gethtml('status', 'post'), $this->request->gethtml('tax_class_id', 'post'), ($this->request->gethtml('min_qty', 'post') != null && $this->request->gethtml('min_qty', 'post') > 0) ? $this->request->gethtml('min_qty', 'post') : 1, $this->request->gethtml('featured', 'post'), $this->request->gethtml('special_offer', 'post'), $this->request->gethtml('related', 'post'), $this->request->gethtml('special_price', 'post'), date('Y-m-d', strtotime($this->request->gethtml('start_date_year', 'post') . '/' . $this->request->gethtml('start_date_month', 'post') . '/' . $this->request->gethtml('start_date_day', 'post'))), date('Y-m-d H:i:s', strtotime($this->request->gethtml('end_date_year', 'post') . '/' . $this->request->gethtml('end_date_month', 'post') . '/' . $this->request->gethtml('end_date_day', 'post') . '23:59:59')), $this->request->gethtml('remaining', 'post')));
	}
	function update_product(){
		$sql = "update product set quantity = '?', barcode = '?', date_available = '?', manufacturer_id = '?', image_id = '?', shipping = '?', price = '?', sort_order = '?', weight = '?', weight_class_id = '?', dimension_id = '?', dimension_value = '?', status = '?', tax_class_id = '?', min_qty = '?', featured = '?', special_offer = '?', related = '?', special_price = '?', sale_start_date = '?', sale_end_date = '?', remaining = '?', date_modified = now() where product_id = '?'";
      	$this->database->query($this->database->parse($sql, $this->request->gethtml('quantity', 'post'), $this->barcode->check($this->request->gethtml('barcode', 'post'),$this->request->gethtml('encoding', 'post')), date('Y-m-d', strtotime($this->request->gethtml('date_available_year', 'post') . '/' . $this->request->gethtml('date_available_month', 'post') . '/' . $this->request->gethtml('date_available_day', 'post'))), $this->request->gethtml('manufacturer_id', 'post'), $this->request->gethtml('image_id', 'post'), $this->request->gethtml('shipping', 'post'), $this->request->gethtml('price', 'post'), $this->request->gethtml('sort_order', 'post'), $this->request->gethtml('weight', 'post'), $this->request->gethtml('weight_class_id', 'post'), $this->request->gethtml('dimension_id', 'post'), implode(':',$this->request->gethtml('dimension_value', 'post')), $this->request->gethtml('status', 'post'), $this->request->gethtml('tax_class_id', 'post'), ($this->request->gethtml('min_qty', 'post') > 0) ? $this->request->gethtml('min_qty', 'post') : 1, $this->request->gethtml('featured', 'post'), $this->request->gethtml('special_offer', 'post'), $this->request->gethtml('related','post'), $this->request->gethtml('special_price', 'post'), date('Y-m-d', strtotime($this->request->gethtml('start_date_year', 'post') . '/' . $this->request->gethtml('start_date_month', 'post') . '/' . $this->request->gethtml('start_date_day', 'post'))), date('Y-m-d H:i:s', strtotime($this->request->gethtml('end_date_year', 'post') . '/' . $this->request->gethtml('end_date_month', 'post') . '/' . $this->request->gethtml('end_date_day', 'post') . '23:59:59')), $this->request->gethtml('remaining', 'post'), (int)$this->request->gethtml('product_id')));
	}
	function get_insert_id(){
		$insert_id = $this->database->getLastId();
		return $insert_id;
	}
	function update_description(){
		$insert_id = $this->request->gethtml('product_id');
		$this->write_description($insert_id);
	}
	function get_description_post(){
		$this->model	 	= $this->request->get('model', 'post');
		$this->model_number	= $this->request->get('model_number' , 'post');
		$this->description 	= $this->request->get('description', 'post');
		$this->technical  	= $this->request->get('technical', 'post');
		$this->technical_name  	= $this->request->get('technical_name', 'post');
		$this->alt_description = $this->request->get('alt_description', 'post');
		$this->meta_title 	= $this->request->get('meta_title', 'post');
		$this->meta_description = $this->request->get('meta_description', 'post');
		$this->meta_keywords = $this->request->get('meta_keywords', 'post');
	}
	function write_description($key, $insert_id, $name){
		$sql = "insert into product_description set product_id = '?', language_id = '?', name = '?', description = '?', technical = '?',  technical_name = '?',model = '?', model_number = '?', alt_description = '?', meta_title = '?', meta_description = '?', meta_keywords = '?'";
		$this->database->query($this->database->parse($sql, $insert_id, $key, htmlspecialchars_deep($name), $this->description[$key], $this->technical[$key], htmlspecialchars_deep($this->technical_name[$key]), htmlspecialchars_deep($this->model[$key]), htmlspecialchars_deep($this->model_number[$key]), $this->alt_description[$key], strip_tags($this->meta_title[$key]), strip_tags($this->meta_description[$key]), strip_tags($this->meta_keywords[$key])));
	}
	function write_discount($insert_id, $quantity, $discount){
		$this->database->query("insert into product_discount set product_id = '" . $insert_id . "', quantity = '" . $quantity . "', discount = '" . $discount . "'");
	}
	function write_PtoImage($insert_id, $image_id){
		$this->database->query("insert into product_to_image set product_id = '" . $insert_id . "', image_id = '" . $image_id . "'");
	}
	function write_download($insert_id, $download_id, $free = 0){
		$this->database->query("insert into product_to_download set product_id = '" . $insert_id . "', download_id = '" . $download_id . "', free = '" . $free . "'");
	}
	function write_PtoCategory($insert_id, $category_id){
		$this->database->query("insert into product_to_category set product_id = '" . $insert_id . "', category_id = '" . $category_id . "'");
	}
	function write_related($insert_id, $product_id){
		$this->database->query("insert into related_products set product_id = '" . $insert_id . "', related_product_id = '" . $product_id . "'");
	}
	function get_manufacturer_id(){
		$result = $this->database->getRow("select manufacturer_id from product where product_id = '" . $this->request->gethtml('product_id') . "'");
		return $result;
	}
	function get_categorys(){
		$results = $this->database->getRows("select category_id from product_to_category where product_id ='". $this->request->gethtml('product_id')."'");
		return $results;
	}
	function delete_product(){
		$this->database->query("delete from product where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_ProductOption(){
		$this->database->query("delete from product_to_option where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_description(){
		$this->database->query("delete from product_description where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_discount(){
		$this->database->query("delete from product_discount where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_PtoImage(){
		$this->database->query("delete from product_to_image where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_download(){
		$this->database->query("delete from product_to_download where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_PtoCategory(){
		$this->database->query("delete from product_to_category where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function deleted_related(){
		$this->database->query("delete from related_products where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function delete_review(){
		$this->database->query("delete from review where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
	}
	function get_page(){
		if (!$this->session->get('product.search')) {
            $sql = "select p.product_id, pd.name, p.price, p.quantity, p.weight, p.weight_class_id, pd.model, p.sort_order, p.status, p.special_price, p.featured, p.special_offer, p.related, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$this->language->getId() . "'";
       } else {
            $sql = "select p.product_id, pd.name, p.price, p.quantity, p.weight, p.weight_class_id, pd.model, p.sort_order, p.status, p.special_price, p.featured, p.special_offer, p.related, i.filename from product p left join product_description pd on (p.product_id = pd.product_id) left join image i on (p.image_id = i.image_id) where pd.language_id = '" . (int)$this->language->getId() . "' and pd.name like '?'";
       }
		$sort = array('pd.name', 'p.price', 'p.quantity', 'p.weight', 'pd.model', 'p.sort_order', 'p.featured', 'p.status',	'p.special_price', 'i.filename');
    	if (in_array($this->session->get('product.sort'), $sort)) {
      		$sql .= " order by " . $this->session->get('product.sort') . " " . (($this->session->get('product.order') == 'desc') ? 'desc' : 'asc');
    	} else {
      		$sql .= " order by pd.name asc";
    	}	
		$results = $this->database->getRows($this->database->splitQuery($this->database->parse($sql, '%' . $this->session->get('product.search') . '%'), $this->session->get('product.page'), $this->config->get('config_max_rows')));
		return $results;
	}
	function get_text_results(){
		$text_results = $this->language->get('text_results', $this->database->getFrom(), $this->database->getTo(), $this->database->getTotal());
		return $text_results;
	}
	function get_pagination(){
    	$page_data = array();
    	for ($i = 1; $i <= $this->get_pages(); $i++) {
      		$page_data[] = array(
        		'text'  => $this->language->get('text_pages', $i, $this->get_pages()),
        		'value' => $i
      		);
    	}
		return $page_data;
	}
	function get_pages(){
		$pages = $this->database->getpages();
		return $pages;
	}
	function get_models($language_id){
		$results = $this->database->getRows("select distinct model from product_description where language_id = '" . (int)$language_id . "' order by model asc");
		return $results;
	}
	function get_product_description($language_id){
		$result = $this->database->getRow("select name, description, technical,technical_name,model, model_number,alt_description, meta_title, meta_description, meta_keywords from product_description where product_id = '" . (int)$this->request->gethtml('product_id') . "' and language_id = '" . (int)$language_id . "'");
		return $result;
	}
	function get_product_info(){
		$result = $this->database->getRow("select distinct * from product where product_id = '" . (int)$this->request->gethtml('product_id') . "'");
		return $result;
	}
	function get_manufacturers(){
		$results = $this->database->cache('manufacturer', "select * from manufacturer order by sort_order, name asc");
		return $results;
	}
	function get_tax_classes(){
		$results = $this->database->cache('tax_class', "select * from tax_class");
		return $results;
	}
	function get_weight_class($weight_class_id){
		$result = $this->database->getRow("select unit from weight_class where weight_class_id = '" . $weight_class_id ."' and language_id = '" . (int)$this->language->getId() . "'");
		return $result['unit'];
	}
	function get_weight_classes(){
		$results = $this->database->cache('weight_class-' . $this->language->getId(), "select weight_class_id, title from weight_class where language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function get_product_discounts(){
		$results = $this->database->getRows("select product_discount_id, quantity, discount from product_discount where product_id = '" . (int)$this->request->gethtml('product_id') . "' order by quantity asc");
		return $results;
	}
	function get_images(){
		$results = $this->database->cache('image', "select i.image_id, i.filename, id.title from image i left join image_description id on (i.image_id = id.image_id) where id.language_id = '" . (int)$this->language->getId() . "' order by id.title");
		return $results;
	}
	function get_product_image($image_id){
		$result = $this->database->getRow("select * from product_to_image where product_id = '" . (int)$this->request->gethtml('product_id') . "' and image_id = '" . (int)$image_id . "'");
		return $result;
	}
	function get_downloads(){
		$results = $this->database->getRows("select d.download_id, d.filename, dd.name from download d left join download_description dd on d.download_id = dd.download_id where dd.language_id = '" . (int)$this->language->getId() . "' order by dd.name");
		return $results;
	}
	function get_product_download($download_id, $free = 0){
		$result = $this->database->getRow("select * from product_to_download where product_id = '" . (int)$this->request->gethtml('product_id') . "' and download_id = '" . (int)$download_id . "' and free = '" . (int)$free . "'");
		return $result;
	}
	function get_categories(){
		$results = $this->database->getRows("select c.category_id, cd.name, c.parent_id, c.path from category c left join category_description cd on (c.category_id = cd.category_id) where cd.language_id = '" . (int)$this->language->getId() . "' order by c.path");
		return $results;
	}
	function get_productToCategory($category_id){
		$result = $this->database->getRow("select * from product_to_category where product_id = '" . (int)$this->request->gethtml('product_id') . "' and category_id = '" . (int)$category_id . "'");
		return $result;
	}
	function get_related_products(){
		$results = $this->database->getRows("select p.product_id, pd.name from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "' order by pd.name asc");
		return $results;
	}
	function get_relatedToProduct($product_id){
		$result = $this->database->getRow("select * from related_products where product_id = '" . (int)$this->request->gethtml('product_id') . "' and related_product_id = '" . (int)$product_id . "'");
		return $result;
	}
	function delete_SEO($query_path){
		$this->database->query("delete from url_alias where query = '".$query_path."'");
	}
	function get_product_name($product_id){
		$result = $this->database->getRow("select name as product_name from product_description where product_id = '" . $product_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_category_path($category_id){
		$result = $this->database->getRow("select path from category where category_id ='" . $category_id . "'");
		return $result;
	}
	function get_category_name($cat_id){
		$result = $this->database->getRow("select name as category_name from category_description where category_id = '" . $cat_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_manufacturer_name($manufacturer_id){
		$result = $this->database->getRow("select name from manufacturer where manufacturer_id = '" . $manufacturer_id . "'");
		return $result;
	}
	function get_languages(){
		$results = $this->database->cache('language', "select * from language order by sort_order");
		return $results;
	}
	function get_dimension_class($dimension_id){
		$result = $this->database->getRow("select title, unit, type_id from dimension where dimension_id = '" . (int)$dimension_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_types(){
		$results = $this->database->getRows("select * from dimension_type");
		return $results;
	}
	function get_type($type_id){
		$result = $this->database->getRow("select * from dimension_type where type_id = '" . $type_id . "'");
		return $result;
	}
	function get_dimension_classes($type_id){
		$results = $this->database->getRows("select * from dimension where type_id = '" . $type_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
	function delete_options($product_id){
		$this->database->query("delete from product_options where product_id = '" . (int)$product_id . "'");
	}
	function insert_options(){
		foreach($this->request->gethtml('product_options', 'post', array()) as $product_option){
			$sql = "insert into product_options set product_id = '?', product_option = '?', quantity = '?', barcode = '?', image_id = '?', dimension_id = '?', dimension_value = '?', model_number = '?'";
			$this->database->query($this->database->parse($sql, $product_option['product_id'], $product_option['product_option'], $product_option['quantity'], $this->barcode->check($product_option['barcode'], $product_option['encoding']), $product_option['image_id'], $product_option['dimension_id'], $product_option['dimension_value'], $product_option['model_number']));
		}
	}
	function get_options($product_id){
		$results = $this->database->getRows("select distinct option_id from product_to_option where product_id = '" . (int)$product_id . "' order by sort_order");
		return $results;
	}
	function get_option_names($option_id){
		$result = $this->database->getRow("select name from `option` where option_id = '" . $option_id . "' and language_id = '" . (int)$this->language->getId() . "'");
		return $result;
	}
	function get_option_values(){
		$results = $this->database->getRows("select pto.product_to_option_id, pto.option_value_id, pto.option_id, ov.name from product_to_option pto left join option_value ov on (pto.option_value_id = ov.option_value_id) where product_id = '" . (int)$this->request->gethtml('product_id') . "' and language_id = '" . (int)$this->language->getId() . "' order by sort_order");
		return $results;
	}
	function get_product_options(){
		$results = $this->database->getRows("select * from product_options po left join image i on (po.image_id = i.image_id) where po.product_id = '" . (int)$this->request->gethtml('product_id') . "' order by po.product_option asc");
		return $results;
	}
	function check_downloads($product_id){
		$downloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 0 ' and dd.language_id = '" . (int)$this->language->getId() . "'");
		return $downloads ? TRUE : FALSE;
	}
	function check_fdownloads($product_id){
		$fdownloads = $this->database->getRows("select * from product_to_download p2d left join download d on (p2d.download_id = d.download_id) left join download_description dd on (d.download_id = dd.download_id) where p2d.product_id = '" . (int)$product_id . "' and p2d.free = ' 1 ' and dd.language_id = '" . (int)$this->language->getId() . "'");
		return $fdownloads ? TRUE : FALSE;
	}
}
?>