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
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><?php echo $tab_general; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td>
			    <?php if($code == "en"){?>
			      <input type="text" readonly="readonly" name="name" maxlength="32" value="<?php echo $name; ?>">
			    <?php } else {?>
			      <input type="text" name="name" maxlength="32" value="<?php echo $name; ?>">
				<?php } ?>
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td>
			    <?php if($code == "en"){?>
				  <input type="text" readonly="readonly" name="code" maxlength="32" value="<?php echo $code; ?>">
				<?php } else {?>
			      <input type="text" name="code" maxlength="32" value="<?php echo $code; ?>">
			    <?php } ?>
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_image; ?></td>
              <td><input type="text" id="flag_image" name="image" maxlength="32" value="<?php echo $image; ?>">
                <?php if ($error_image) { ?>
                <span class="error"><?php echo $error_image; ?></span>
                <?php } ?></td>
				<td>
				  <select  name="flag_image" onchange="$('#image').load('index.php?controller=language&action=view_image&flag_image='+this.value), $('#flag_image').val(this.value);">
				    <?php foreach($flags as $flag){?>
				      <option value="<?php echo $flag['filename'];?>"<?php if($image == $flag['filename']){ echo ' selected';}?>><?php echo '(' . $flag['name'] . ') ' . $flag['country'];?></option>
					<?php }?>
				  </select>
				<td>
				<td id="image">
				  <?php if(isset($image_thumb)){?>
				    <img src="<?php echo $image_thumb;?>" alt="" title="Flag" width="32" height="22">
				  <?php }?>
				</td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_directory; ?></td>
              <td>
			    <?php if($code == "en"){?>
				  <input type="text" readonly="readonly" name="directory" maxlength="32" value="<?php echo $directory; ?>">
				<?php } else {?>
			      <input type="text" name="directory" maxlength="32" value="<?php echo $directory; ?>">
			    <?php } ?>
                <?php if ($error_directory) { ?>
                <span class="error"><?php echo $error_directory; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_filename; ?></td>
              <td>
			    <?php if($code == "en"){?>
				  <input type="text" readonly="readonly" name="filename" maxlength="32" value="<?php echo $filename; ?>">
				<?php } else {?>
			      <input type="text" name="filename" maxlength="32" value="<?php echo $filename; ?>">
				<?php } ?>
                <?php if ($error_filename) { ?>
                <span class="error"><?php echo $error_filename; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"></td>
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
<script type="text/javascript"><!--


//--></script>