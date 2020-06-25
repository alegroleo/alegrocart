<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
   <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
   <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
   <input type="hidden" name="update_form" value="1">
   <input type="hidden" name="query" value="">
   <input type="hidden" name="alias" value="">
  <input type="hidden" name="url_alias_id" value="<?php echo $url_alias_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <em></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_query; ?></td>
              <td><input type="text" name="query" value="<?php echo $query; ?>" size="50">
                <?php if ($error_query) { ?>
                <span class="error"><?php echo $error_query; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_alias; ?></td>
              <td><input type="text" name="alias" value="<?php echo $alias; ?>" size="50"></td>
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
  <script type="text/javascript"><!--
    $('input[name="alias"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].query.value=document.forms['form'].query.value;
		document.forms['update_form'].alias.value=document.forms['form'].alias.value;
	}
  //--></script>
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
		url:     'index.php?controller=url_alias&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
</form>
