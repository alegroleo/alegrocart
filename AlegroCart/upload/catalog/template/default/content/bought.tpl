<?php 
  $head_def->setcss($this->style . "/css/paging.css");
  $head_def->setcss($this->style . "/css/product_cat.css");
  $head_def->setcss($this->style . "/css/bought.css");
  if($tpl_columns != 1){
	$head_def->setcss($this->style . "/css/display_options.css");
  }
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
  <script type="text/javascript">
	$(document).ready(function(){
		$(".lightbox").lightbox({
			fitToScreen: true,
			imageClickClose: true
		});
	});
  </script>
  <?php }
  $head_def->set_MetaTitle("Bought Products");
  $head_def->set_MetaDescription("Sort Products by Customer");
  $head_def->set_MetaKeywords("products");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<?php if (isset($products) && $tpl_columns == 1)  { 
   include $shared_path . 'content_options.tpl';
} ?>
<?php if ($products) { ?>
<!-- Start of Product ****************************** -->
 <?php if($columns == 1){ ?> <!-- Single column display -->
  <div class="heading"><h2><?php echo $heading_title; ?></h2></div>
  <?php include $shared_path . 'single_column.tpl'; ?>
 <?php } else { ?> <!-- Muliple column display --> 
  <?php if($columns > 1){
   $heading_info = isset($heading_info) ? " - " . $heading_info : "";
   include $shared_path . 'multiple_columns.tpl';
  }?>
 <?php } ?> 
  <!-- End of mulitple column display -->
 <?php include $shared_path . 'pagination.tpl'; ?>
<?php } ?> 
