<?php
  $head_def->setcss($this->style . "/css/review.css");
  $head_def->set_javascript("ajax/jquery.js");
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
  <?php } ?>
<div class="headingbody">
  <div class="left"><h1><?php echo $heading_title; ?></h1></div>
  <div class="right">
  <?php if($special_price){
  echo '<div class="price_old" >'. ($tax_included ? '<span class="tax">*</span>' : '')  .$price.'</div> '.'<div class="price_new">'. ($tax_included ? '<span class="tax">*</span>' : '') .$special_price.'</div>';
  } else {
  echo '<div class="price_new">'. ($tax_included ? '<span class="tax">*</span>' : '') .$price.'</div> '; 
  }?>
  </div>
</div>
<div class="module">
<div id="review_info">
 <div class="left">
  <p><b><?php echo $text_author; ?></b> <?php echo $author; ?></p>
  <p><b><?php echo $text_date_added; ?> </b> <?php echo $date_added; ?></p>
  <p><b><?php echo $text_rating; ?></b><?php echo $text; ?></p><br>
 </div>
<div class="a">

<?php if($image_display == 'thickbox'){?>
<a href="<?php echo $href; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>"></a>
<div class="enlarge"><a class="thickbox" href="<?php echo $popup; ?>"><?php echo $text_enlarge; ?></a></div>
<?php } elseif ($image_display == 'fancybox') {?>
<script type="text/javascript">$(document).ready(function(){$("a#<?php echo $this_controller.$id; ?>").fancybox({openEffect : 'elastic', closeEffect : 'elastic'}); });</script>
<a href="<?php echo $href; ?>"><img src="<?php echo $thumb;?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>"></a>
<div class="enlarge"> <a id="<?php echo $this_controller.$id; ?>" href="<?php echo $popup; ?>"> <?php echo $text_enlarge; ?></a></div>
<?php } elseif ($image_display == 'lightbox') { ?>
<a href="<?php echo $href; ?>"><img src="<?php echo $thumb;?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>"></a>
<div class="enlarge"> <a class="lightbox" id="<?php echo $this_controller.$id; ?>" href="<?php echo $popup; ?>"><?php echo $text_enlarge; ?></a></div>
<?php } ?>

</div>
<table><?php for ($i=1; $i<5; $i++) { ?>
  <tr><td style="width:15px;"></td><td><b><?php echo ${'text_rating'.$i}; ?></b></td><td><img src="catalog/styles/<?php echo $this->style?>/image/stars_<?php echo ${'rating'.$i} . '.png'; ?>" alt="<?php echo ${'text_out_of'.$i}; ?>" class="png"></td></tr><tr><td style="width:15px;"></td><td></td><td>(<?php echo ${'text_out_of'.$i}; ?>)</td></tr>
  <?php } ?></table></br>
<p><b><a href="<?php echo $href; ?>"><?php echo $name; ?></a></b></p>
</div>
<br>
<div class="clearfix"></div>
</div>
<div class="module_bottom"></div>
<div class="buttons">
  <table>
    <tr>
      <td align="left"><input type="button" value="<?php echo $button_reviews; ?>" onclick="location='<?php echo $review; ?>'"></td>
      <td align="right"><input type="button" value="<?php echo $button_write; ?>" onclick="location='<?php echo $write; ?>'"></td>
    </tr>
  </table>
</div>
