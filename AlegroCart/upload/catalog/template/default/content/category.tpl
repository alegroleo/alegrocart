<?php 
  $head_def->setcss($this->style . "/css/paging.css");
  $head_def->setcss($this->style . "/css/product_cat.css");
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
  if ($meta_title){
    $head_def->set_MetaTitle($meta_title);
  }
  if ($meta_description){
    $head_def->set_MetaDescription($meta_description);
  }
  if ($meta_keywords){
    $head_def->set_MetaKeywords($meta_keywords);
  }
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';    
?>
<?php if (isset($products) && $tpl_columns == 1)  { 
   include $shared_path . 'content_options.tpl';
} ?>
<?php if ($description) { ?>
<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
<div id="category_description">
  <div class="center">
    <div class="text"><?php echo $description; ?></div>
  </div>
  <div class="bottom"></div>
</div>
<?php } else { ?>
<div class="heading"><h2><?php echo $heading_title; ?></h2></div>
<?php } ?>
<?php if ($categories) { ?>
  <div class="contentBodyTop"></div>
  <div class="contentBody">
     <?php $column_count=0;	?> 
  <?php foreach ($categories as $key => $category) { ?>
    <?php if($column_count == '3'){
		$column_count = 1;
	  } else {
		$column_count++;
	  }
	  if($column_count == 1){
	    echo '<div class="category_row">';
	  }
	?>
    <div class="categories">
  <?php if ($category['thumb'] != NULL) { ?>
	  <a href="<?php echo $category['href']; ?>">
	  <img src="<?php echo $category['thumb']; ?>" title="<?php echo $category['name']; ?>" width="<?php echo $category['width']; ?>" height="<?php echo $category['height']; ?>" alt="<?php echo $category['name']; ?>">
	  </a><br>
  <?php } ?> 
	  <b><a href="<?php echo $category['href']; ?>"><?php echo $category['name'] . ($category['products_in_category'] != 0 ? ' (' . $category['products_in_category'] . ')':''); ?></a></b>
</div>
	<?php if($column_count == '3'){
	    echo "</div>";
	    if(($key + 1) < count($categories)){
		  echo "<div class=\"divider\"></div>";}
	} ?>
<?php } ?>
    <?php if($column_count != '3'){
	  echo "</div>";}?>
</div>
<div class="contentBodyBottom"></div>
<?php } ?>
<?php if ($products) { ?>
<?php if ($categories) { ?>
<div class="heading"><h2><?php echo $text_product; ?></h2></div>
<?php } ?>
<!-- Start of Product ****************************** -->
 <?php if($columns == 1){ ?> <!-- Single column display -->
  <?php include $shared_path . 'single_column.tpl'; ?>
 <?php } else { ?> <!-- Muliple column display --> 
  <?php if($columns > 1){
   $heading_info = isset($heading_info) ? " - " . $heading_info : " - ".$text_product;
   include $shared_path . 'multiple_columns.tpl';
  }?>
 <?php } ?> 
  <!-- End of mulitple column display -->
 <?php include $shared_path . 'pagination.tpl'; ?>
<?php } ?>
 <div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div> 
