<?php 
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  if($image_display == 'thickbox'){
	$head_def->setcss($this->style . "/css/thickbox.css");  
	$head_def->set_javascript("thickbox/thickbox-compressed.js");
  } else if ($image_display == 'fancybox'){
	$head_def->setcss($this->style . "/css/jquery.fancybox-1.3.1.css\" media=\"screen" ); 
	$head_def->set_javascript("fancybox/jquery.fancybox-1.3.1.js");
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
    include $shared_path . 'multiple_columns.tpl';
  } else {
	echo '<div class="heading"><h1>' . $heading_title . $heading_info . '</h1></div>';
    include $shared_path . 'single_column.tpl';
  }
  
} else { 
   echo '<p>' . $text_notfound . '</p>'; 
}
echo '<br>';
 ?>
