<?php 
  $head_def->setcss($this->style . "/css/product.css");
  $head_def->setcss($this->style . "/css/tab.css");
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/jqueryadd2cart.js");
  $head_def->set_javascript("tab/tab.js");
  $head_def->set_javascript("magnify/ddpowerzoomer.js"); 
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
<div class="headingbody"><?php echo "Product Detail : ".$heading_title; ?></div>
<div class="contentBody">
 <div class="product" id="product">
  <div class="a" >
   <?php include $shared_path . 'product_image.tpl';?>
  </a>
 </div>
 <div class="pbox">
  <div class="pheading">
   <div class="left"><?php echo $product_number.$heading_title; ?></div>
  </div>
  <?php if($product['model_number'] || $product_options){?>
    <div class="model_number">
	  <?php echo $text_model_number;?>
	    <span id="<?php echo $this_controller . '_model_' . $product['product_id'];?>"><?php echo $product['model_number'];?></span>
		<?php if($product_options){?>
		  <script language="JavaScript">
			$(document).ready(function(){
			  UpdateModel(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
			});
		  </script>
		  <?php foreach($product_options as $product_option){?>
		    <input type="hidden" id="<?php echo $this_controller . '_model_' . $product_option['product_option'];?>" value="<?php echo $product_option['model_number'];?>">
		  <?php }?>
		<?php }?>
    </div>
  <?php }?>
  <div>
   <?php if(isset($alt_description)){ echo $alt_description;} ?>
  </div>
  <div class="shipping">
    <?php if ($shipping) { ?><img src="catalog/styles/<?php echo $this->style?>/image/shippable.png" alt="<?php echo $text_shippable; ?>" title="<?php echo $text_shippable; ?>" ><?php } elseif(!$downloads) { ?><img src="catalog/styles/<?php echo $this->style?>/image/non_shippable.png" alt="<?php echo $text_non_shippable; ?>" title="<?php echo $text_non_shippable; ?>"><?php  } ?>
	<?php if ($downloads) { ?><img src="catalog/styles/<?php echo $this->style?>/image/downloadable.png" alt="<?php echo $text_downloadable; ?>" title="<?php echo $text_downloadable; ?>" >
	<span><?php echo $text_downloadable;?></span>
	<?php }?>
  </div>
  <div class="ponhand"><?php echo $quantity_available; ?>
  <span id="<?php echo $this_controller . '_stock_level_' . $product['product_id'];?>"><?php echo $stock_level; ?></span></div>
  <?php if($product_options){?>
    <script language="JavaScript">
	  $(document).ready(function(){
	    UpdateQuantity(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
	  });
	</script>
	<?php foreach($product_options as $product_option){?>
	  <input type="hidden" id="<?php echo $this_controller . '_stock_level_' . $product_option['product_option'];?>" value="<?php echo $product_option['quantity'];?>">
	<?php }?>
  <?php }?>  
   <?php include $shared_path . 'product_price.tpl' ;?>
  <?php if ($product['options']){
   if(isset($product_options_select) && $product_options_select == 'radio'){
     include $shared_path . 'product_options_radio.tpl';
   } else {
	 include $shared_path . 'product_options.tpl';
  }} ?>
   <?php if ($product_addtocart) { ?>	
     <table>
      <tr>
	   <td align="left">		  
		<?php include $shared_path . 'add_to_cart.tpl';?>		  
	   </td>
      </tr>
     </table>
   <?php } ?>
  </div>
 </div>
 <div class="clearfix"></div>
<div class="box">
  <?php if($review_status){
      if($maxrow){
		$Review_tabs = "<a><div class=\"tab_text\">".$tab_reviews."(".$maxrow.")"."</div></a>";
	  } else {
		$Review_tabs = "<a><div class=\"tab_text\">".$tab_write."</div></a>";
	  }
	}
	if($technical){
	$Technical_tabs = "<a><div class=\"tab_text\">" . $tab_technical . "</div></a>";}
  ?>
<div class="tab" id="tab">
  <div class="tabs"><a><div class="tab_text"><?php echo $tab_description; ?></div></a>
    <?php if (isset($Technical_tabs)){ echo $Technical_tabs;} ?>
    <?php if(count($images)){?>
	<a><div class="tab_text"><?php echo $tab_images."(".count($images).")"; ?></div></a>
	<?php }?>
	<a><div class="tab_text"><?php echo $tab_information; ?></div></a>
	<?php if (isset($Review_tabs)){ echo $Review_tabs;} ?></div>
  <div class="pages">
    <div class="page">
	  <div class="pad">
	    <Div>
		  <?php echo $description; ?>
		</div>
	  </div>
	</div>
	<?php if (isset($Technical_tabs)) { ?>
	<div class="page">
      <div class="pad">  	
	    <Div>
		  <?php echo $technical; ?>
		</div>
	  </div>
	</div>	
	<?php } ?>
	<?php if(count($images)){?>
	<div class="page">
      <div class="pad">  
        <?php if ($images) { ?>
          <?php foreach ($images as $image) { ?>
            <?php include $shared_path . 'additional_images.tpl' ;?>
          <?php } ?>
        <?php } ?>
			  <div class="clearfix"></div>
      </div>
    </div>
	<?php }?>
	<div class="page">
	  <div class="pad">
	    <?php if ($product['min_qty'] >= '1') { ?>
          <?php echo '<b>' . $text_min_qty . '</b>'; ?><?php echo $product['min_qty']; ?><br><br>
        <?php } ?>
		<?php echo '<b>' . $text_tax_rate . '</b>' . $tax_rate;?><br>
		<?php if (isset($product_discounts)){ ?><br>
		  <?php echo "<b>".$text_quantity_discount."</b><br>"; ?>
		  <?php foreach ($product_discounts as $product_discount){ ?>
		    <?php echo "&nbsp;&nbsp;".$text_qty_discount.$product_discount['discount_quantity']."&nbsp;&nbsp;".$text_discount.$product_discount['discount_amount']."&nbsp;&nbsp; (".$product_discount['discount_percent']."%)"; ?><br>
		  <?php } ?>
		<?php } ?>
		<?php if (($product['special_price'] > '$0.00' ) && date('Y-m-d') >= $product['sale_start_date'] && date('Y-m-d') <= $product['sale_end_date']) { ?><br>	
		  <?php echo "<b>".$text_date."</b><br>"; ?>
		  <?php echo "&nbsp;&nbsp;".$text_sale_start.date("F-d-Y",strtotime($product['sale_start_date'])); ?><br>
		  <?php echo "&nbsp;&nbsp;".$text_sale_end.date("F-d-Y",strtotime($product['sale_end_date'])); ?><br>
		<?php } ?>
		<?php if($downloads){?>
		  <?php echo '<br>' . $text_product_download;?>
		<?php }?>
		<?php if (isset($manufacturer)) { ?><br>
		  <?php echo '<b>' . $text_manufacturer . '</b>' . $manufacturer; ?><br>
		<?php } ?>
		<?php if ($weight) { ?><br>
		  <?php echo '<b>' . $text_weight . '</b>' . ' '; ?>
		  <span id="<?php echo $this_controller . '_weights_' . $product['product_id'];?>"><?php echo $weight;?></span>
		  <?php echo ' ' . $weight_unit;?>
		  <?php if($option_weights){?>
		    <input type="hidden" id="weight_<?php echo $product['product_id'];?>" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$weight)),$decimal_place,'.','');?>">
			<script language="JavaScript">
			  $(document).ready(function(){
			    UpdateWeight(<?php echo $decimal_place . ",'" . $decimal_point . "',". $product['product_id'] . ",'" . $this_controller . "'";?>);
			  });
			</script>
		    <?php foreach($option_weights as $option_weight){?>
			  <input type="hidden" id="<?php echo $this_controller . '_weight_' . $option_weight['product_to_option_id'];?>" value="<?php echo number_format((float)str_replace($decimal_point,'.',str_replace($symbols,'',$option_weight['option_weight'])),$decimal_place,'.','');?>">
			<?php }?>
		  <?php }?>
		  <br>
		 <?php } ?>
		<?php if ($dimensions || $product_options) { ?><br>
		  <span id="<?php echo $this_controller . '_dimensions_' . $product['product_id'];?>"><?php echo $dimensions ? @$dimensions : ''; ?></span><br>
		  <?php if($product_options){?>
		    <input type="hidden" id="dimension_<?php echo $product['product_id'];?>" value="<?php echo $dimensions;?>">
		    <script language="JavaScript">
			  $(document).ready(function(){
			    UpdateDimensions(<?php echo $product['product_id'] . ',"' . $this_controller . '"';?>);
			  });
			</script>
			<?php foreach($product_options as $product_option){?>
			<input type="hidden" id="<?php echo $this_controller . '_dimension_' . $product_option['product_option'];?>" value="<?php echo $product_option['dimensions'];?>">
			<?php }?>
		  <?php }?>
		<?php } ?>
		<?php if ($shipping) { ?><br>
		  <?php echo '<b>' . $text_shipping_yes . '</b>'; } elseif(!$downloads) { echo '<br><b>' . $text_shipping_no. '</b>'; ?> <br>
		<?php  } ?>
	  </div>
	</div>
	<div class="page">
      <div class="pad">
	    <?php if(!$maxrow){ echo $text_error; } ?>
		<div class= "rtab" id="rtab">
		  <div class="rtabs">
		  <?php $i=1; while($i <= $maxrow){ echo "<a> ".$i." </a>";$i++;} ?>
		  </div>		  
		  <div class="rpages">
	        <?php foreach ($review_data as $review) { ?>		  
		    <div class="rpage">
			  <div class="rpad">
				<div class="review">
				  <div class="a">
			        <div class="b"><a href="<?php echo $review['href']; ?>" ><?php //echo $review['name']; ?></a><br>
			          <b><?php echo $text_review_by; ?></b><?php echo $review['author']; ?></div><br>
			        <div class="c"><b><?php echo $text_date_added; ?></b> <?php echo $review['date_added']; ?></div><br>
			        <table>
				      <tr>				  
				        <td colspan="2"><?php echo $review['text']; ?></td>
				      </tr>
				      <?php for ($i=1; $i<5; $i++) { ?>
				      <tr>
				        <td><br><b><?php echo ${'text_rating'.$i}; ?></b></td><td><img src="catalog/styles/<?php echo $this->style?>/image/stars_<?php echo $review['rating'.$i] . '.png'; ?>" alt="<?php echo $review['out_of'.$i]; ?>" class="png">&nbsp;(<?php echo $review['out_of'.$i]; ?>)</td>
				      </tr>
				      <?php } ?>
			        </table>
			      </div>
		        </div>
		      </div>	  
	        </div>		
			<?php } ?>
		  </div>	
	    </div>
		<div class="review_write"><br><a href="<?php echo $write; ?>"><img src="catalog/styles/<?php echo $this->style?>/image/write.png" alt="Write"></a><br><br>
		<a href="<?php echo $write; ?>"><?php echo $text_write; ?></a></div>
	  </div>
    </div>
  </div>
</div>
</div></div>
<div class="contentBodyBottom"></div>
<div>
<div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
</div>

</div>
	<script type="text/javascript"><!--
      tabview_initialize('tab')
    //--></script>
	  <script type="text/javascript"><!--
	  rtabview_initialize('rtab');
    //--></script>