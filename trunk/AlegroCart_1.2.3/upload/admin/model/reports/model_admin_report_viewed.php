<?php //AdminModelReportViewed AlegroCart
class Model_Admin_Report_Viewed extends Model {
	function __construct(&$locator) {
		$this->database 	=& $locator->get('database');
		$this->language 	=& $locator->get('language');
	}
	function get_viewed(){
		$results = $this->database->getRows("select * from product p left join product_description pd on (p.product_id = pd.product_id) where pd.language_id = '" . (int)$this->language->getId() . "' order by viewed desc");
		return $results;
	}
}
?>