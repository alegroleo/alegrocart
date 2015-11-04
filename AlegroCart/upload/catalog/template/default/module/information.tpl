<?php if(isset($tax_included) && $tax_included){
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/tooltip.js");
}?>

<?php if($location == 'column' || $location == 'columnright'){?>
<div class="headingcolumn"><h3><?php echo $heading_title; ?></h3></div>
<div class="information">
<?php foreach ($information as $info) { ?>
<a href="<?php echo $info['href']; ?>"><h4><?php echo $info['title']; ?></h4></a>
<?php } ?>
<a href="<?php echo $contact; ?>"><h4><?php echo $text_contact; ?></h4></a>
<a href="<?php echo $sitemap; ?>"><h4><?php echo $text_sitemap; ?></h4></a>
<?php if(isset($tax_included) && $tax_included){?>
  <script type="text/javascript">
    $(document).ready(function(){
	  $('.taxE[title]').tooltip({
		offset: [70,<?php echo ($location == 'column' ? 160 : -210) ?>], tipClass: 'tooltip_white'});
	});
  </script>
<?php echo '<div title="' . $text_tax_explantion . '" class="taxE" style="height: 14px; padding: 6px;"><span class="tax"> *</span>' . $text_tax . '</div>';
}?>
</div>
<div class="columnBottom"></div>
<?php } else {?>
<div class="information">
<div>
<?php foreach ($information as $info) { ?>
<a href="<?php echo $info['href']; ?>"><h4><?php echo $info['title']; ?></h4></a>
<?php } ?>
<a href="<?php echo $contact; ?>"><h4><?php echo $text_contact; ?></h4></a>
<a href="<?php echo $sitemap; ?>"><h4><?php echo $text_sitemap; ?></h4></a>
</div>
<?php if(isset($tax_included) && $tax_included){?>
  <script type="text/javascript">
    $(document).ready(function(){
	  $('.taxE[title]').tooltip({
		offset: [-70,190], tipClass: 'tooltip_white'});
	});
  </script>
<?php echo '<div style="display: table; margin: 0 auto;" title="' . $text_tax_explantion . '" class="taxE" ><span class="tax"> *</span>' . $text_tax . '</div>';
}?>
</div>
<div class="clearfix"></div>
<?php }?>
