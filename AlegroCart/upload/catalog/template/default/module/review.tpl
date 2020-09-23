<?php 
  $head_def->setcss( $this->style . "/css/module_column.css");
?>

<div class="headingcolumn"><h3><?php echo $heading_title; ?></h3></div>
<div class="module_column">
  <div class="module_review">
    <?php if (isset($review)) { ?>
    <div><a href="<?php echo $review; ?>"><img src="<?php echo $image; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" alt="<?php echo $name; ?>"></a></div>
    <div><a style="text-decoration: none" href="<?php echo $review; ?>"><?php echo $desciption; ?></a></div>
    <div><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="112" height="20" data-src="catalog/styles/<?php echo $this->style;?>/image/stars_<?php echo $rating . '.png'; ?>" alt="<?php echo $text_rating; ?>"></div>
    <div style="color:gray; font-weight:bold; font-size:x-small;"><?php echo $avgrating;?>/5</div>
    <?php } ?>
  </div>
</div>
<div class="columnBottom"></div>
