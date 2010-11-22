<?php 
  $head_def->setcss( $this->style . "/css/module_column.css");
?>

<div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
<div class="module_column">
  <div class="module_review">
    <?php if (isset($review)) { ?>
    <div><a href="<?php echo $review; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>"></a></div>
    <div><a style="text-decoration: none" href="<?php echo $review; ?>"><?php echo $desciption; ?></a></div>
    <div><a href="<?php echo $reviews; ?>"><img src="catalog/styles/<?php echo $this->style;?>/image/stars_<?php echo $rating . '.png'; ?>" alt="<?php echo $text_rating; ?>"></a></div>
    <?php } ?>
  </div>
</div>
<div class="columnBottom"></div>
