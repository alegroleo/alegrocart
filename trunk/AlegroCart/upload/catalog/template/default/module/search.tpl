<?php if($location == 'header'){?>
<div class="search">
<?php } else {?>
<div class="search" style="position: relative; right: 0px; top: 0px; margin: 10px 0px 10px 0px; padding-right: 2px;">
<?php }?>
  <form action="<?php echo $action; ?>" method="post">
    <div>
      <?php if ($search) { ?>
      <input type="text" name="search" value="<?php echo cleansearch($search); ?>">
      <?php } else { ?>
      <input type="text" name="search" value="<?php echo $text_keywords; ?>" onclick="this.value = ''">
      <?php }
      ?>
      <input type="hidden" name="description" value="<?php echo $description;?>"<?php if($description == "on"){?> CHECKED<?php }?>>
	  <input type="hidden" name="max_rows" value="<?php echo $max_rows; ?>">
	  <?php if (isset($columns)){?>
		<input type="hidden" name="columns" value="<?php echo $columns; ?>"> <?php echo "\n";}?>
	  <?php if (isset($page_rows)){?>
	    <input type="hidden" name="page_rows" value="<?php echo $page_rows; ?>"> <?php echo "\n";}?>
	  <?php if (isset($default_order)){?>
	    <input type="hidden" name="default_order" value="<?php echo $default_order; ?>"><?php echo "\n";}?>
	  <?php if (isset($default_filter)){?>
	    <input type="hidden" name="default_filter" value="<?php echo $default_filter; ?>"><?php echo "\n";}?>
     <button class="searchbt" name="submit" type="submit"></button>
    </div>
  </form>
</div>
