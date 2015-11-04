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
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="headingbody">
    <div class="left"><h1><?php echo $heading_title; ?></h1></div>
  <div class="right">
	<?php if($special_price){
  echo '<div class="price_old" >'. ($tax_included ? '<span class="tax">*</span>' : '') .$price.'</div> '.'<div class="price_new">'. ($tax_included ? '<span class="tax">*</span>' : '') .$special_price.'</div>';
  } else {
  echo '<div class="price_new">'. ($tax_included ? '<span class="tax">*</span>' : '') .$price.'</div> '; 
  }?>
  </div>
</div>
  <div class="module">
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div id="review_write">
	<div class="a">
		<?php if($image_display == 'thickbox'){?>
		<a href="<?php echo $popup; ?>" title="<?php echo $product; ?>" class="thickbox"><img src="<?php echo $thumb; ?>" title="<?php echo $product; ?>" alt="<?php echo $product; ?>"></a>
		<div class="enlarge"><a class="thickbox" href="<?php echo $popup; ?>"><?php echo $text_enlarge; ?></a></div>
		<?php } elseif ($image_display == 'fancybox') {?>
		<script type="text/javascript">$(document).ready(function(){$("a#<?php echo $this_controller.$id; ?>").fancybox({openEffect : 'elastic', closeEffect : 'elastic'}); });</script>
		<a href="<?php echo $popup; ?>" id="<?php echo $this_controller.$id; ?>"><img src="<?php echo $thumb;?>" title="<?php echo $product; ?>" alt="<?php echo $product; ?>"></a>
		<div class="enlarge"> <a id="<?php echo $this_controller.$id; ?>" href="<?php echo $popup; ?>"> <?php echo $text_enlarge; ?></a></div>
		<?php } elseif ($image_display == 'lightbox') { ?>
		<a href="<?php echo $popup; ?>" class="lightbox" id="<?php echo $this_controller.$id; ?>"><img src="<?php echo $thumb;?>" title="<?php echo $product; ?>" alt="<?php echo $product; ?>"></a>
		<div class="enlarge"> <a class="lightbox" id="<?php echo $this_controller.$id; ?>" href="<?php echo $popup; ?>"><?php echo $text_enlarge; ?></a></div>
		<?php } ?>
	</div>
    <div class="b"><b><?php echo $text_author; ?></b> <?php echo $author; ?><br>
      <br>
      <b><?php echo $entry_review; ?></b></div>
    <div id="c">
      <textarea name="text" cols="60" rows="10"><?php echo $text; ?></textarea>
      <div class="d"><?php echo $text_note; ?></div>
    <table class="e">
    <?php for ($i=1; $i<5; $i++) { ?>
      <tr><td><b><?php echo ${'entry_rating'.$i}; ?></b>&nbsp;</td>
        <td><span class="bad"><?php echo $entry_bad; ?></span>&nbsp;<?php if (${'rating'.$i} == 1) { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="1" CHECKED>
        <?php } else { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="1">
        <?php } ?>
        &nbsp;
        <?php if (${'rating'.$i} == 2) { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="2" CHECKED>
        <?php } else { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="2">
        <?php } ?>
        &nbsp;
        <?php if (${'rating'.$i} == 3) { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="3" CHECKED>
        <?php } else { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="3">
        <?php } ?>
        &nbsp;
        <?php if (${'rating'.$i} == 4) { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="4" CHECKED>
        <?php } else { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="4">
        <?php } ?>
        &nbsp;
        <?php if (${'rating'.$i} == 5) { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="5" CHECKED>
        <?php } else { ?>
        <input type="radio" name="rating<?php echo $i; ?>" value="5">
        <?php } ?>
        &nbsp; <span class="good"><?php echo $entry_good; ?></span></td></tr>
    <?php } ?></table>
    </div>
  </div>
<br>
<div class="clearfix"></div>
</div>
<div class="module_bottom"></div>
  <div class="buttons">
    <table>
      <tr>
        <td class="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td class="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>
