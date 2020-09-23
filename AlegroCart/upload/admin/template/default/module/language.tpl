<div class="language">
  <?php foreach ($languages as $language) { ?>
	<?php if($language['language_status'] == '1'){?>
	  <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">
	    <div>
	      <input type="image" src="template/<?php echo $this->directory?>/image/language/<?php echo $language['image']; ?>" width=16 height=11 alt="<?php echo $language['name']; ?>">
	      <input type="hidden" name="module_language" value="<?php echo $language['code']; ?>">
	    </div>
	  </form>
	<?php } ?>
  <?php } ?>
</div>
