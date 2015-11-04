<div class="currency">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency">
    <div>
      <select name="currency" onchange="document.getElementById('currency').submit();">
        <?php foreach ($currencies as $currency) { ?>
        <?php if ($currency['code'] == $default) { ?>
        <option value="<?php echo $currency['code']; ?>" SELECTED><?php echo $currency['code']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['code']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </form>
</div>
