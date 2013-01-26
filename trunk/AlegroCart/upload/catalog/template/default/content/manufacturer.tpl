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
  $head_def->set_MetaTitle("Products by Manufacturer");
  $head_def->set_MetaDescription("Sort Products by Manufacturer");
  $head_def->set_MetaKeywords("products, manufacturer, brands");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<?php if ((isset($products) && $columns == 1) || (isset($products) && $display_options == FALSE) || (isset($products) && $tpl_columns == 2)) { ?>
 <div class="headingbody"><?php echo $heading_title." - ". $manufacturer; ?></div>
 <div class="contentBody">
  <div class="manufacturer_filter">	
   <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data">
	<div class="top_entry">
	 <div class="left">
	  <?php echo $text_sort_by;?>
	  <div class="data">		  
	   <?php foreach($sort_filter as $filter){ ?>		    
	    <input type="radio" <?php if($default_filter == $filter){ ?>CHECKED <?php }?>name="sort_filter" value="<?php echo $filter;?>"><?php echo $filter;?>
	    <br>	
	   <?php }?>		  
	  </div>
	 </div>
	 <div class="floatleft">
	  <?php echo $text_order;?>
	  <div class="data">
	   <?php foreach($sort_order as $order){ ?>		    
	    <input type="radio" <?php if($default_order == $order){ ?>CHECKED <?php }?>name="sort_order" value="<?php echo $order;?>"><?php echo $order;?>
	    <br>	
	   <?php }?>
	  </div>
	 </div>	
	 <?php if($options_model){?>
	   <div class="right">
	     <?php if (count($models_data ) > 1){ ?>
	       <table>
	         <tr><td><?php echo $text_model; ?></td></tr>
		     <tr><td>
		       <select name="model">
		         <option value="all" <?php if($model == "all"){?>selected <?php }?>><?php echo $text_all;?></option>
		         <?php foreach($models_data as $model_data){?>
		           <option value="<?php echo $model_data['model_value'];?>" <?php if($model_data['model'] == $model){?>selected <?php }?>><?php echo $model_data['model'];?></option>
		         <?php }?>
		       </select>
		     </td></tr>
	       </table>
	     <?php }?>		
	   </div>
	 <?php }?>
	</div>
	<div class="divider"></div>
	<?php if($display_lock == False){ ?>
	<table><tr>
	 <td><div class="lowerleft">
	  <?php echo $text_page_rows;?>
	  <div class="data">
	   <input type="text" size="4" name="page_rows" value="<?php echo $default_page_rows;?>">
	  </div>
	 </div></td>
	 <td><div class="lowerleft">
	  <?php echo $text_max_rows;?>
	  <div class="data">
	   <input type="text" size="4" name="max_rows" value="<?php echo $default_max_rows;?>">
	  </div>
	 </div></td>	
	  <td><div class="lowerright">
	   <?php echo $text_columns;?>
	   <div class="data">
	    <select name="columns">
		 <?php foreach($number_columns as $number_column){
			if($columns == $number_column){
			  echo '<option value="' . $number_column . '" selected>' . $number_column . '</option>';
			} else {
			  echo '<option value="' . $number_column . '">' . $number_column . '</option>';
			}
		 }?>
		</select>
	   </div>
	  </div></td>
	</tr></table>
	<div class="divider"></div>
	<?php } ?>
	<div class="entry">
	  <input type="submit" value="<?php echo $entry_submit;?>">
	 </div>	
   </form>
   <div class="clearfix"></div>  
  </div>
  
 </div>
 <div class="contentBodyBottom">  </div>	
<?php }?>
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
  echo '<div style="text-align: center;"><br><h1>' . $text_error . '</h1></div>';
}?>
</div>
<?php if(isset($breadcrumbs)){?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div> 
<?php }?>