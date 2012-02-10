<?php 
  $head_def->setcss($this->style . "/css/review.css");
  $head_def->setcss($this->style . "/css/thickbox.css");  
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("thickbox/thickbox-compressed.js");
?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="headingpadded">
    <div class="left"><?php echo $heading_title; ?></div>
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
    <div class="a"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="thickbox"><img src="<?php echo $thumb; ?>" title="<?php echo $product; ?>" alt="<?php echo $product; ?>"><div class="enlarge"><?php echo $text_enlarge; ?></div></a></div>
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
        <td align="left"><input type="button" value="<?php echo $button_back; ?>" onclick="location='<?php echo $back; ?>'"></td>
        <td align="right"><input type="submit" value="<?php echo $button_continue; ?>"></td>
      </tr>
    </table>
  </div>
</form>
