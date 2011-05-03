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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_title; ?></td>
              <td><input type="text" name="title" value="<?php echo $title; ?>">
                <?php if ($error_title) { ?>
                <span class="error"><?php echo $error_title; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>">
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
			<tr>
			  <td class="set"><?php echo $entry_status;?></td>
			  <td><select name="status">
			    <?php if($status) { ?>
			    <option value="1" selected><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected><?php echo $text_disabled; ?></option>
			    <?php } ?>
			  </select>
			  <?php if ($error_default) { ?>
                <span class="error"><?php echo $error_default; ?></span>
                <?php } ?>
			  </td>
			</tr>
			<tr>
			  <td class="set"><?php echo $entry_lock_rate;?></td>
			  <td><select name="lock_rate">
			    <?php if($lock_rate) { ?>
			    <option value="1" selected><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected><?php echo $text_disabled; ?></option>
			    <?php } ?>
			  </select></td>
			  <td class="expl"><?php echo $text_lock_rate; ?></td>
			</tr>
            <tr>
              <td class="set"><?php echo $entry_symbol_left; ?></td>
              <td><input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_symbol_right; ?></td>
              <td><input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_decimal_place; ?></td>
              <td><input type="text" name="decimal_place" value="<?php echo $decimal_place; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_value; ?></td>
              <td><input type="text" name="value" value="<?php echo $value; ?>"></td>
			  <td class="expl"><?php echo $text_default_rate ;?></td>
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
