<div class="language">
  <?php foreach ($languages as $language) { ?>
	<?php if($language['language_status'] == '1'){?>
	  <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">
	    <div>
	      <input type="image" src="catalog/styles/<?php echo $this->style?>/image/language/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>">
	      <input type="hidden" name="module_language" value="<?php echo $language['code']; ?>">
	    </div>
	  </form>
	<?php } ?>
  <?php } ?>
</div>
