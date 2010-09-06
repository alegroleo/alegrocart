<div class="language">
  <?php foreach ($languages as $language) { ?>
  <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">
    <div>
      <input type="image" src="template/<?php echo $this->directory?>/image/language/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>">
      <input type="hidden" name="module_language" value="<?php echo $language['code']; ?>">
    </div>
  </form>
  <?php } ?>
</div>
