<?php if(isset($tax_included) && $text_tax){
  $head_def->set_javascript("ajax/jquery.js");
  $head_def->set_javascript("ajax/tooltip.js");
}?>

<?php if($location == 'column' || $location == 'columnright'){?>
<div class="headingcolumn"><h1><?php echo $heading_title; ?></h1></div>
<div class="information">
<?php foreach ($information as $info) { ?>
<a href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a>
<?php } ?>
<a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
<a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?> </a>
<?php if(isset($tax_included) && $text_tax){?>
  <script type="text/javascript">
    $(document).ready(function(){
	  $('.taxE[title]').tooltip({
      offset: [70,160], tipClass: 'tooltip_white'});
	});
  </script>
<?php echo '<div title="' . $text_tax_explantion . '" class="taxE" style="height: 14px; padding: 6px;"><span class="tax"> *</span>' . $text_tax . '</div>';
}?>
</div>
<div class="columnBottom"></div>
<?php } else {?>
<div class="information" style="background: none; width: 850px; margin-left: 200px; letter-spacing: 2px; border:none;">

<?php foreach ($information as $info) { ?>
<a style="float: left; background: none;" href="<?php echo $info['href']; ?>"><?php echo $info['title']; ?></a>
<?php } ?>
<a style="float: left; background: none;" href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
<a style="float: left; background: none;" href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a>
<?php if(isset($tax_included) && $text_tax){?>
  <script type="text/javascript">
    $(document).ready(function(){
	  $('.taxE[title]').tooltip({
       offset: [-70,190], tipClass: 'tooltip_white'});
	});
  </script>
<?php echo '<div title="' . $text_tax_explantion . '" class="taxE" ><span class="tax"> *</span>' . $text_tax . '</div>';
}?>
</div>
<div class="clearfix"></div>
<?php }?> 



