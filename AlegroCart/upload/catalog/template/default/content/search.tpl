<?php 
  $head_def->setcss($this->style . "/css/search.css");
  $head_def->setcss($this->style . "/css/paging.css");
  $head_def->setcss($this->style . "/css/product_cat.css");
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
  $head_def->set_MetaTitle("Enhanced Product Search");
  $head_def->set_MetaDescription("Search for products using keywords, does not need to be a consecutive phrase");
  $head_def->set_MetaKeywords("product search, keywords, enhanced, wildcard");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>

<div id="search">
  <div class="headingbody"><?php echo $heading_title; ?></div>
  <div class="contentBody">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="a"><?php echo $text_critea; ?></div>
    <div class="b">
      <div class="c">
        <table>
          <tr>
            <td style="width: 100px"><?php echo $entry_search; ?></td>
            <td><?php if ($search) { ?>
              <input type="text" name="search" value="<?php echo cleansearch($search); ?>">
              <?php } else { ?>
              <input type="text" name="search" value="<?php echo $text_keywords; ?>" onclick="this.value = ''">
              <?php } ?></td>
          </tr>		
          <tr>
            <td colspan="2">
              <input type="checkbox" name="description"<?php if ($description == "on") { ?> value="on" CHECKED<?php } else {?> value="off"<?php }?>>
              <?php echo $entry_description; ?></td>			  
			<td style="width: 350px;" align="right"><input type="submit" value="<?php echo $button_search; ?>"></td>
          </tr>
		</table>
		<input type="hidden" name="max_rows" value="<?php echo $max_rows; ?>">
		<?php if (isset($columns)){?>
			<input type="hidden" name="columns" value="<?php echo $columns; ?>"> <?php echo "\n";}?>
		<?php if (isset($page_rows)){?>
			<input type="hidden" name="page_rows" value="<?php echo $page_rows; ?>"> <?php echo "\n";}?>
		<?php if (isset($default_order)){?>
			<input type="hidden" name="default_order" value="<?php echo $default_order; ?>"><?php echo "\n";}?>
		<?php if (isset($default_filter)){?>
			<input type="hidden" name="default_filter" value="<?php echo $default_filter; ?>"><?php echo "\n";}?>
		  
		<?php if(isset($maximum_results)){
			if($maximum_results){
		    echo "<table style=\"background-color: #FFCCCC;\"><tr><td>" . $text_max_reached . "</td></tr></table>";
		  }
		} ?>		
      </div>
    </div> 
  </form></div>
  <div class="contentBodyBottom"></div>
  <?php if (isset($products)) { //<!-- Start of Product  -->?>
  <?php if($columns == 1){ //<!-- Single column display -->?> 
   <?php include $shared_path . 'single_column.tpl'; ?>
 <?php } else { //<!-- Muliple column display -->?>
  <?php if($columns > 1){
   $heading_info = isset($heading_info) ? " - " . $heading_info : " - ".$text_search;
   include $shared_path . 'multiple_columns.tpl';
  }?>
  <?php } //<!-- End of mulitple column display -->?>
 <?php include $shared_path . 'pagination.tpl'; ?>
 <?php } else { ?>
   <div class="d"><?php echo $text_error; ?></div>
 <?php }?>
</div>