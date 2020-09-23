<?php 
  $head_def->setcss($this->style . "/css/review.css");
$head_def->setcss($this->style . "/css/paging.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';  
?>
<div class="headingbody">
  <div class="left"><h1><?php echo $heading_title; ?></h1></div>
  <div class="right">
  <?php if($special_price){
  echo '<div class="price_old" >'. ($tax_included ? '<span class="tax">*</span>' : '')  .$price.'</div> '.'<div class="price_new">'. ($tax_included ? '<span class="tax">*</span>' : '')  .$special_price.'</div>';
  } else {
  echo '<div class="price_new">'. ($tax_included ? '<span class="tax">*</span>' : '')  .$price.'</div> '; 
  }?>
  </div>
</div>
<div class="module">
<div id="review">
  <?php foreach ($reviews as $review) { ?>
  <div class="a">
    <div class="b"><a href="<?php echo $review['href']; ?>" ><?php echo $review['name']; ?></a> <?php echo $text_review_by; ?> <?php echo $review['author']; ?></div>
    <div class="c"><b><?php echo $text_date_added; ?></b> <?php echo $review['date_added']; ?></div>
    <table>
      <tr>
        <td rowspan="3"><a href="<?php echo $review['href']; ?>"><img src="<?php echo $review['thumb']; ?>" width="<?php echo $review['width']; ?>" height="<?php echo $review['height']; ?>" alt="<?php echo $review['name']; ?>" class="d"></a></td>
        <td><?php echo $review['text']; ?></td>
      </tr>
      <tr>
        <td><b><?php echo $text_rating; ?></b>&nbsp;</td>
	<td class="avg"><?php echo $review['avgrating2']; ?>/5</td>
      </tr>
 <tr>
        <td></td><td class="stars"><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="112" height="20" data-src="catalog/styles/<?php echo $this->style?>/image/stars_<?php echo $review['avgrating'] . '.png'; ?>" alt="<?php echo $review['out_of']; ?>" class="png"></td>
	
      </tr>
    </table>
  </div>
  <?php } ?>
</div>
<div class="clearfix"></div>
</div>
<div class="module_bottom"></div>
<?php include $shared_path . 'pagination.tpl'; ?>
