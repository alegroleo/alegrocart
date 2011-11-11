<?php //AdminModelWatermark AlegroCart
class Model_Admin_Watermark extends Model {
	function __construct(&$locator) {
		$this->config   	=& $locator->get('config');
		$this->database 	=& $locator->get('database');
		$this->request  	=& $locator->get('request');
		$this->session 		=& $locator->get('session');
	}
	function delete_watermark($method){
			$this->database->query("delete from watermark where wm_method = '" . $method . "'");
	}
	function update_watermark($method){
			$sql = "insert into watermark set wm_method = '?', wm_text = '?', wm_font = '?', wm_fontcolor = '?', wm_transparency = '?', wm_thposition = '?', wm_tvposition = '?', wm_thmargin = '?', wm_tvmargin = '?', wm_image = '?', wm_ihposition = '?', wm_ivposition = '?', wm_ihmargin = '?', wm_ivmargin = '?', wm_scale = '?'";
			$this->database->query($this->database->parse($sql, $method, $this->request->gethtml('wm_text','post'), (int)$this->request->gethtml('wm_font','post'), $this->request->gethtml('wm_fontcolor','post'), (int)$this->request->gethtml('wm_transparency','post'), $this->request->gethtml('wm_thposition','post'), $this->request->gethtml('wm_tvposition','post'), (int)$this->request->gethtml('wm_thmargin','post'), (int)$this->request->gethtml('wm_tvmargin','post'), $this->request->gethtml('wm_image','post'), $this->request->gethtml('wm_ihposition','post'), $this->request->gethtml('wm_ivposition','post'), (int)$this->request->gethtml('wm_ihmargin','post'), (int)$this->request->gethtml('wm_ivmargin','post'), (int)$this->request->gethtml('wm_scale','post')));
	}
	function get_watermark_data($method){
			$result = $this->database->getRow("select * from watermark where wm_method = '" . $method . "'");
			return $result;
	}
	function get_originals(){
		$results = $this->database->getRows("select filename from image");
		return $results;
	}
}
?>