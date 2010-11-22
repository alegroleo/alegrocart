<?php if($location == 'header'){?>
<div class="language">
<?php } else {?>
<div class="language" style="top: 5px; margin: 5px 5px 10px 0px;">
<?php }?>
  <?php foreach ($languages as $language) { ?>
  <form action="<?php echo $action; ?>" method="POST" enctype="multipart/form-data">
    <div>
      <input type="image" src="catalog/styles/<?php echo $this->style?>/image/language/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>">
      <input type="hidden" name="module_language" value="<?php echo $language['code']; ?>">
    </div>
  </form>
  <?php } ?>
</div>
