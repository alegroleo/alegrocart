<?php 
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  if($image_display == 'thickbox'){
	$head_def->setcss($this->style . "/css/thickbox.css");
	$head_def->set_javascript("thickbox/thickbox-compressed.js");
  } else if ($image_display == 'fancybox'){
	$head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" );
	$head_def->set_javascript("fancybox/jquery.fancybox.js");
} else if ($image_display == 'lightbox'){
    $head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" );
	$head_def->set_javascript("lightbox/lightbox.js");
  ?>
  <script>
	$(document).ready(function(){
		$(".lightbox").lightbox({
			fitToScreen: true,
			imageClickClose: true
		});
	});
  </script>
  <?php }
  if($columns == 1){
    $head_def->setcss($this->style . "/css/product_cat.css");
  }
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
if (isset($products)) {
  $heading_info = isset($heading_info) ? " - " . $heading_info : "";
  if($columns > 1){
	if (isset($slider) && $slider) {
		$head_def->setcss($this->style . "/css/slick.css");
		$head_def->setcss($this->style . "/css/slick-theme.css");
		$head_def->set_javascript("slider/slick.min.js");
		include $shared_path . 'slider_columns.tpl';
	} else {
		include $shared_path . 'multiple_columns.tpl';
	}
  } else {
	echo '<div class="heading"><h2>' . $heading_title . $heading_info . '</h2></div>';
	include $shared_path . 'single_column.tpl';
  }
}
echo '<br>';
 ?>
