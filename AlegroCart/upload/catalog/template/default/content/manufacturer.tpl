<?php 
  $head_def->setcss($this->style . "/css/manufacturer.css");
  $head_def->setcss($this->style . "/css/paging.css");
  $head_def->setcss($this->style . "/css/product_cat.css");
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
  $head_def->set_MetaTitle("Products by Manufacturer");
  $head_def->set_MetaDescription("Sort Products by Manufacturer");
  $head_def->set_MetaKeywords("products, manufacturer, brands");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<?php if ((isset($products) && $columns == 1) || (isset($products) && $display_options == FALSE) || (isset($products) && ($tpl_columns == 1.2 || $tpl_columns == 2.1 || $tpl_columns == 1))) { 
   include $shared_path . 'content_options.tpl';
} ?>
<div id="manufacturer">
<?php if (isset($products)) { ?>
 <?php if($columns == 1){ ?>
  <?php include $shared_path . 'single_column.tpl'; ?>
 <?php } else { ?>
  <?php if($columns > 1){
   $heading_info = isset($manufacturer) ? " - " . $manufacturer : "";
   include $shared_path . 'multiple_columns.tpl';
  }?>
 <?php } ?>
 <?php include $shared_path . 'pagination.tpl'; ?>
<?php } else {
  echo '<div class="manufacturer_error"><br><p>' . $text_error . '</p></div>';
}?>
</div>
<?php if(isset($breadcrumbs)){?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div> 
<?php }?>
