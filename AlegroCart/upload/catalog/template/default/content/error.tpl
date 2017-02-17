<?php if ($this->condense) {
	switch ($this_controller) {
		case 'category' :
			$this->head_def->setcss($this->style . "/css/paging.css");
			$this->head_def->setcss($this->style . "/css/product_cat.css");
			if($tpl_columns != 1){
				$this->head_def->setcss($this->style . "/css/display_options.css");
			}
			$this->head_def->set_javascript("ajax/jquery.js");
			$this->head_def->set_javascript("ajax/jqueryadd2cart.js");
			if($image_display == 'thickbox'){
				$this->head_def->setcss($this->style . "/css/thickbox.css");  
				$this->head_def->set_javascript("thickbox/thickbox-compressed.js");
			} else if ($image_display == 'fancybox'){
				$this->head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" ); 
				$this->head_def->set_javascript("fancybox/jquery.fancybox.js");
			} else if ($image_display == 'lightbox'){
				$this->head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" ); 
				$this->head_def->set_javascript("lightbox/lightbox.js");
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
			break;
		case 'review' :
			$this->head_def->setcss($this->style . "/css/review.css");
			$this->head_def->setcss($this->style . "/css/paging.css");
			break;
		case 'account_invoice' :
			$this->head_def->setcss($this->style . "/css/account_invoice.css");
			$this->head_def->set_javascript("ajax/jquery.js");
			break;
		case 'information' :
			$this->head_def->setcss($this->style . "/css/information.css");
			break;
		case 'manufacturer' :
			$this->head_def->setcss($this->style . "/css/paging.css");
			$this->head_def->setcss($this->style . "/css/product_cat.css");
			if($tpl_columns == 1){
				$this->head_def->setcss($this->style . "/css/manufacturer.css");
			}
			$this->head_def->set_javascript("ajax/jquery.js");
			$this->head_def->set_javascript("ajax/jqueryadd2cart.js");
			if($image_display == 'thickbox'){
				$this->head_def->setcss($this->style . "/css/thickbox.css");
				$this->head_def->set_javascript("thickbox/thickbox-compressed.js");
			} else if ($image_display == 'fancybox'){
				$this->head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" );
				$this->head_def->set_javascript("fancybox/jquery.fancybox.js");
			} else if ($image_display == 'lightbox'){
				$this->head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" );
				$this->head_def->set_javascript("lightbox/lightbox.js");
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
			break;
		case 'product' :
			$this->head_def->setcss($this->style . "/css/product.css");
			$this->head_def->setcss($this->style . "/css/tab.css");
			$this->head_def->set_javascript("ajax/jquery.js");
			$this->head_def->set_javascript("ajax/jqueryadd2cart.js");
			$this->head_def->set_javascript("tab/tab.js");
			if($magnifier){
				$this->head_def->set_javascript("magnify/ddpowerzoomer.js"); 
			}
			if($image_display == 'thickbox'){
				$this->head_def->setcss($this->style . "/css/thickbox.css");  
				$this->head_def->set_javascript("thickbox/thickbox-compressed.js");
			} else if ($image_display == 'fancybox'){
				$this->head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" ); 
				$this->head_def->set_javascript("fancybox/jquery.fancybox.js");
			} else if ($image_display == 'lightbox'){
				$this->head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" ); 
				$this->head_def->set_javascript("lightbox/lightbox.js");
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
			break;
		case 'account_history' :
			$this->head_def->setcss($this->style . "/css/account_history.css");
			$this->head_def->setcss($this->style . "/css/paging.css");
			break;
		case 'cart' :
			$this->head_def->setcss($this->style . "/css/cart.css");
			if(isset($tax_included)){
				$this->head_def->set_javascript("ajax/jquery.js");
				$this->head_def->set_javascript("ajax/tooltip.js");
			}
			break;
		case 'account_download' :
			$this->head_def->setcss($this->style . "/css/account_download.css");
			$this->head_def->setcss($this->style . "/css/paging.css");
			break;
		case 'review_write' :
			$this->head_def->setcss($this->style . "/css/review.css");
			$this->head_def->set_javascript("ajax/jquery.js");
			if($image_display == 'thickbox'){
				$this->head_def->setcss($this->style . "/css/thickbox.css");  
				$this->head_def->set_javascript("thickbox/thickbox-compressed.js");
			} else if ($image_display == 'fancybox'){
				$this->head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" ); 
				$this->head_def->set_javascript("fancybox/jquery.fancybox.js");
			} else if ($image_display == 'lightbox'){
				$this->head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" ); 
				$this->head_def->set_javascript("lightbox/lightbox.js");
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
			break;
		case 'review_info' :
			$this->head_def->setcss($this->style . "/css/review.css");
			$this->head_def->set_javascript("ajax/jquery.js");
			if($image_display == 'thickbox'){
				$this->head_def->setcss($this->style . "/css/thickbox.css");  
				$this->head_def->set_javascript("thickbox/thickbox-compressed.js");
			} else if ($image_display == 'fancybox'){
				$this->head_def->setcss($this->style . "/css/jquery.fancybox.css\" media=\"screen" ); 
				$this->head_def->set_javascript("fancybox/jquery.fancybox.js");
			} else if ($image_display == 'lightbox'){
				$this->head_def->setcss($this->style . "/css/lightbox.css\"  media=\"screen" ); 
				$this->head_def->set_javascript("lightbox/lightbox.js");
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
			break;
		default :
			break;
	}
}
?>
<div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
<div class="contentBody">
<div id="error"><?php echo $text_error; ?></div>
</div>
<div class="contentBodyBottom"></div>
<?php if (isset($breadcrumbs)) { ?> 
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div> 
<?php } ?>
<div class="buttons">
  <table>
    <tr>
      <td class="right"><input type="button" value="<?php echo $button_continue; ?>" onclick="location='<?php echo $continue; ?>'"></td>
    </tr>
  </table>
</div>
