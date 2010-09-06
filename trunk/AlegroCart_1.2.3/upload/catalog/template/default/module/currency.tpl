<?php if($location == 'header'){?>
<div class="currency">
<?php } else {?>
<div class="currency" style="position: relative; top: 5px; left: 10px; margin: 5px 0px 10px 12px;">
<?php }?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency">
    <div>
      <select name="currency" onchange="document.getElementById('currency').submit();">
        <?php foreach ($currencies as $currency) { ?>
        <?php if ($currency['code'] == $default) { ?>
        <option value="<?php echo $currency['code']; ?>" SELECTED><?php echo $currency['title']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </form>
</div>
