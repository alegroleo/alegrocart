<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getTabs()"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="code" value="">
  <input type="hidden" name="directory" value="">
  <input type="hidden" name="filename" value="">
  <input type="hidden" name="controller" value="">
  <?php foreach ($extensions as $extension) { ?>
  <input type="hidden" name="extension_language[<?php echo $extension['language_id']; ?>][name]" value="">
  <input type="hidden" name="extension_language[<?php echo $extension['language_id']; ?>][description]" value="">
  <?php } ?>
</form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs();document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($extensions as $extension) { ?>
            <a><div class="tab_text"><?php echo $extension['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($extensions as $extension) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td><input name="extension_language[<?php echo $extension['language_id']; ?>][name]" value="<?php echo $extension['name']; ?>">
                      <?php if ($error_name) { ?>
                      <span class="error"><?php echo $error_name; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td valign="top" class="set"><span class="required">*</span> <?php echo $entry_description; ?></td>
                    <td><textarea name="extension_language[<?php echo $extension['language_id']; ?>][description]" cols="40" rows="5"><?php echo $extension['description']; ?></textarea>
                      <?php if ($error_description) { ?>
                      <span class="error"><?php echo $error_description; ?></span>
                      <?php } ?></td>
                  </tr>
                </table>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input class="validate_alpha" id="code" name="code" value="<?php echo $code; ?>">
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_directory; ?></td>
              <td><input class="validate_alpha" id="directory" name="directory" value="<?php echo $directory; ?>">
                <?php if ($error_directory) { ?>
                <span class="error"><?php echo $error_directory; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_filename; ?></td>
              <td><input class="validate_file" id="filename" name="filename" value="<?php echo $filename; ?>">
                <?php if ($error_filename) { ?>
                <span class="error"><?php echo $error_filename; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><span class="required">*</span> <?php echo $entry_controller; ?></td>
              <td><input class="validate_alpha" id="controller" name="controller" value="<?php echo $controller; ?>">
                <?php if ($error_controller) { ?>
                <span class="error"><?php echo $error_controller; ?></span>
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
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  <script type="text/javascript"><!--
    $('input[name="extension_language[1][name]"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
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
		url:     'index.php?controller=extension&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	function saveTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=extension&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=extension&action=tab',
		data: data_json,
		dataType:'json',
		success: function (data) {
				if (data.status===true) {
					getValues();
					document.getElementById('update_form').submit();
				} else {
					$('<div class="warning"><?php echo $error_update; ?></div>').insertBefore(".heading");
				}
		}
	});
	}
	function getValues() {
		document.forms['update_form'].code.value=document.forms['form'].code.value;
		document.forms['update_form'].directory.value=document.forms['form'].directory.value;
		document.forms['update_form'].filename.value=document.forms['form'].filename.value;
		document.forms['update_form'].controller.value=document.forms['form'].controller.value;
		<?php foreach ($extensions as $extension) { ?>
			document.forms['update_form'].elements['extension_language[<?php echo $extension['language_id']; ?>][name]'].value=document.forms['form'].elements['extension_language[<?php echo $extension['language_id']; ?>][name]'].value;
			document.forms['update_form'].elements['extension_language[<?php echo $extension['language_id']; ?>][description]'].value=document.forms['form'].elements['extension_language[<?php echo $extension['language_id']; ?>][description]'].value;
		<?php } ?>
	}
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
	if (<?php echo $tabmini; ?>!=undefined && <?php echo $tabmini; ?> > 0) {
			tabview_switch('tabmini', <?php echo $tabmini; ?>);
		}
	}
   });
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>
</form>
