<?php //Model Sitemap AlegroCart
class Model_Sitemap extends Model {
	function __construct(&$locator) {	
		$this->database =& $locator->get('database');
		$this->language =& $locator->get('language');
	}
	function get_categories(){
		$results = $this->database->getRows("select c.category_id, cd.name, c.parent_id, c.path, c.sort_order from category c left join category_description cd on (c.category_id = cd.category_id) where cd.language_id = '".(int)$this->language->getId()."' order by c.path, c.sort_order, cd.name");
		return $results;
	}
	function get_information(){
		$results = $this->database->cache('information-' . (int)$this->language->getId(), "select * from information i left join information_description id on (i.information_id = id.information_id) where id.language_id = '" . (int)$this->language->getId() . "'");
		return $results;
	}
}
?>