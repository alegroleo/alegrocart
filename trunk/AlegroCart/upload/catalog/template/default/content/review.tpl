<?php 
  $head_def->setcss($this->style . "/css/review.css");
$head_def->setcss($this->style . "/css/paging.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';  
?>
<div class="headingpadded">
  <div class="left"><?php echo $heading_title; ?></div>
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
        <td rowspan="2"><a href="<?php echo $review['href']; ?>"><img src="<?php echo $review['thumb']; ?>" alt="<?php echo $review['name']; ?>" class="d"></a></td>
        <td><?php echo $review['text']; ?></td>
      </tr>
      <tr>
        <td><b><?php echo $text_rating; ?></b> <img src="catalog/styles/<?php echo $this->style?>/image/stars_<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['out_of']; ?>" class="png"><br>
          (<?php echo $review['out_of']; ?>)</td>
      </tr>
    </table>
  </div>
  <?php } ?>
</div>
<div class="clearfix"></div>
</div>
<div class="module_bottom"></div>
<?php include $shared_path . 'pagination.tpl'; ?>