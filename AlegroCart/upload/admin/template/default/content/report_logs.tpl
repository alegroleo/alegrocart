<?php 
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <?php if($log_file){?><div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="window.print();"><img src="template/<?php echo $this->directory?>/image/print_enabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div><?php }else{?><div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div><?php }?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/cancel_disabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<div class="heading"><?php echo $heading_title; ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<hr>
<div style="min-height: 400px;">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<table>
  <tr>
    <td style="width:185px;" class="set"><?php echo $entry_dir; ?></td>
    <td><select name="directory" onchange="$('#file_name').load('index.php?controller=report_logs&action=get_files&directory='+this.value);">
		<option value=""><?php echo $text_select_dir;?></option>
		<?php foreach($log_directories as $directory){?>
			<option value="<?php echo $directory['directory'];?>"<?php if($directory['directory'] == $log_directory) {echo ' selected';}?>><?php echo $directory['directory'];?></option>
		<?php }?>
	</select></td>
  </tr>
</table>
<table id="file_name" style="height: 24px;">
  <?php if(isset($log_files)){ echo $log_files;}?>
</table>
<table id="encrypt">
  <tr>
    <td class="set" style="width: 185px;"><?php echo $entry_decrytion; ?></td>
	<td><?php if ($decrytion) { ?>
       <input type="radio" name="decrytion" value="1" id="decyes" checked>
       <label for="decyes"><?php echo $text_yes; ?></label>
       <input type="radio" name="decrytion" value="0" id="decno">
       <label for="decno"><?php echo $text_no; ?></label>
                <?php } else { ?>
       <input type="radio" name="decrytion" value="1" id="decyes">
       <label for="decyes"><?php echo $text_yes; ?></label>
       <input type="radio" name="decrytion" value="0" id="decno" checked>
       <label for="decno"><?php echo $text_no; ?></label>
                <?php } ?></td>
	<td class="expl">
	  <?php echo $text_dycrypt_exp; ?> 
	</td>			
  </tr>
  <tr><td><input type="submit" class="submit" value="<?php echo $button_submit; ?>"></td>
  </tr>
</table>
</form>
<?php if($log_file){?>
<table style="border: 1px solid #000000; width: 100%; padding: 2px;">
  <tr><td>
  <?php echo $log_file;?>
  </td></tr>
</table>
<?php }?>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	$('.task').each(function(){
	$('.task .disabled').hide();
	});
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
  });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=report_logs&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
