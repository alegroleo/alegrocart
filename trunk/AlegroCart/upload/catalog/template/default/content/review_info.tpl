<?php 
  $head_def->setcss($this->style . "/css/review.css");
  $head_def->setcss($this->style . "/css/thickbox.css");  
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("thickbox/thickbox-compressed.js");
?>
<div class="headingpadded">
  <div class="left"><?php echo $heading_title; ?></div>
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
  <p><b><?php echo $text_author; ?></b> <?php echo $author; ?></p><br>
  <p><b><?php echo $text_date_added; ?> </b> <?php echo $date_added; ?></p><br>
  <p><b><?php echo $text_rating; ?></b></p><br>
<div class="a"><a href="<?php echo $href; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>"></a>
<div class="enlarge"><a class="thickbox" href="<?php echo $popup; ?>"><?php echo $text_enlarge; ?></a></div></a>
</div>
<table><?php for ($i=1; $i<5; $i++) { ?>
  <tr><td style="width:15px;"></td><td><b><?php echo ${'text_rating'.$i}; ?></b></td><td><img src="catalog/styles/<?php echo $this->style?>/image/stars_<?php echo ${'rating'.$i} . '.png'; ?>" alt="<?php echo ${'text_out_of'.$i}; ?>" class="png"></td></tr><tr><td style="width:15px;"></td><td></td><td><?php echo ${'text_out_of'.$i}; ?></td></tr>
  <?php } ?></table></br>
<div><?php echo $text; ?></div><br>
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
