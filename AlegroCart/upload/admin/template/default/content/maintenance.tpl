<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ckeditor/ckeditor.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="catalog_maintenance_status" value="">
  <?php foreach ($maintenance_descriptions as $maintenance_description) { ?>
    <input type="hidden" name="header[<?php echo $maintenance_description['language_id']; ?>]" value="">
    <input type="hidden" name="description[<?php echo $maintenance_description['language_id']; ?>]" value="">
  <?php } ?>
</form>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs;document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
<?php if (@$last) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $last; ?>'"><img src="template/<?php echo $this->directory?>/image/last_enabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/last_disabled.png" width=32 height=32 alt="<?php echo $button_last; ?>" class="png"><?php echo $button_last; ?></div>
  <?php } ?>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_description; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
	  <table>
	    <tr>
              <td width="185" class="set"><?php echo $entry_status; ?></td>
              <td><select name="catalog_maintenance_status">
                  <?php if ($catalog_maintenance_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
	      <td class="expl">
                <?php echo($explanation_entry_status); ?>
	      </td>
	    </tr>
	  </table>
        </div>
      </div>
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($maintenance_descriptions as $maintenance_description) { ?>
            <a><div class="tab_text"><?php echo $maintenance_description['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($maintenance_descriptions as $maintenance_description) { ?>
            <div class="page">
              <div class="minipad">
                <table>
		  <tr>
			<td style="width: 165px;" class="set"><span class="required">*</span> <?php echo $entry_header; ?></td>
			<td><input size="100" maxlength="120" name="header[<?php echo $maintenance_description['language_id']; ?>]" value="<?php echo $maintenance_description['header']; ?>">
			<?php if (@$error_header[$maintenance_description['language_id']]) { ?>
				<span class="error"><?php echo $error_header[$maintenance_description['language_id']]; ?></span>
			<?php } ?>
			</td>
  		  </tr>
                  <tr>
                    <td style="vertical-align: top; width: 165px" class="set"><span class="required">*</span> <?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $maintenance_description['language_id']; ?>]" id="description<?php echo $maintenance_description['language_id']; ?>"><?php echo $maintenance_description['description']; ?></textarea>
			<?php if (@$error_description[$maintenance_description['language_id']]) { ?>
				<span class="error"><?php echo $error_description[$maintenance_description['language_id']]; ?></span>
			<?php } ?>
		    </td>
                  </tr>
		</table>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
	  </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form>
  <script type="text/javascript">
    <?php foreach ($maintenance_descriptions as $maintenance_description) { ?>
      CKEDITOR.replace( 'description<?php echo $maintenance_description['language_id']; ?>' );
  <?php } ?>      
  </script>
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
 <script type="text/javascript">
  tabview_initialize('tabmini');
  </script>
  <script type="text/javascript">
	function saveTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=maintenance&action=tab',
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
		url:	'index.php?controller=maintenance&action=tab',
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
		document.forms['update_form'].catalog_maintenance_status.value=document.forms['form'].catalog_maintenance_status.value;
		<?php foreach ($maintenance_descriptions as $maintenance_description) { ?>
			document.forms['update_form'].elements['header[<?php echo $maintenance_description['language_id']; ?>]'].value=document.forms['form'].elements['header[<?php echo $maintenance_description['language_id']; ?>]'].value;
			document.forms['update_form'].elements['description[<?php echo $maintenance_description['language_id']; ?>]'].value=CKEDITOR.instances.description<?php echo $maintenance_description['language_id']; ?>.getData();
		<?php } ?>
	}
  </script>
  <script type="text/javascript">
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
		url:     'index.php?controller=maintenance&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
	if (<?php echo $tabmini; ?>!=undefined && <?php echo $tabmini; ?> > 0) {
			tabview_switch('tabmini', <?php echo $tabmini; ?>);
		}
	}
   });
  </script>
