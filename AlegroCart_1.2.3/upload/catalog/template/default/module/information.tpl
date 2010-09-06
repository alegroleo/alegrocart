<?php if($location == 'column' || $location == 'columnright'){?>
<div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
<div class="information">
    <?php foreach ($information as $info) { ?>
  <a href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a>
    <?php } ?>
  <a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
  <a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?> </a>
</div>
<div class="columnBottom"></div>
<?php } else {?>
<div class="information" style="background: none; width: 900px;">
  <?php foreach ($information as $info) { ?>
    <a style="float: left; background: none;" href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a>
    <?php } ?>
    <a style="float: left; background: none;" href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
	<a style="float: left; background: none;" href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a>
</div>
<div class="clearfix"></div>
<?php }?>