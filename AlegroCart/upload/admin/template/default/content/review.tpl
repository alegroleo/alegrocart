<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185"><span class="required">*</span> <?php echo $entry_author; ?></td>
              <td><input type="text" name="author" value="<?php echo $author?>">
                <?php if ($error_author) { ?>
                <span class="error"><?php echo $error_author; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_product; ?></td>
              <td><select name="product_id">
                  <?php foreach ($products as $product) { ?>
                  <?php if ($product['product_id'] == $product_id) { ?>
                  <option value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td valign="top"><span class="required">*</span> <?php echo $entry_text; ?></td>
              <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
                <?php if ($error_text) { ?>
                <span class="error"><?php echo $error_text; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_rating; ?></td>
              <td><b class="rating"><?php echo $entry_bad; ?></b>&nbsp;
                <?php if (@$rating == 1) { ?>
                <input type="radio" name="rating" value="1" checked>
                <?php } else { ?>
                <input type="radio" name="rating" value="1">
                <?php } ?>
                &nbsp;
                <?php if (@$rating == 2) { ?>
                <input type="radio" name="rating" value="2" checked>
                <?php } else { ?>
                <input type="radio" name="rating" value="2">
                <?php } ?>
                &nbsp;
                <?php if (@$rating == 3) { ?>
                <input type="radio" name="rating" value="3" checked>
                <?php } else { ?>
                <input type="radio" name="rating" value="3">
                <?php } ?>
                &nbsp;
                <?php if (@$rating == 4) { ?>
                <input type="radio" name="rating" value="4" checked>
                <?php } else { ?>
                <input type="radio" name="rating" value="4">
                <?php } ?>
                &nbsp;
                <?php if (@$rating == 5) { ?>
                <input type="radio" name="rating" value="5" checked>
                <?php } else { ?>
                <input type="radio" name="rating" value="5">
                <?php } ?>
                &nbsp; <b class="rating"><?php echo $entry_good; ?></b></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><?php if ($status == 1) { ?>
                <input type="radio" name="status" value="1" checked>
                <?php echo $text_enabled; ?>
                <input type="radio" name="status" value="0">
                <?php echo $text_disabled; ?>
                <?php } else { ?>
                <input type="radio" name="status" value="1">
                <?php echo $text_enabled; ?>
                <input type="radio" name="status" value="0" checked>
                <?php echo $text_disabled; ?>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
</form>
