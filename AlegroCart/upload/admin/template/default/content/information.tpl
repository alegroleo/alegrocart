<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/ckeditor/ckeditor.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="information_hide" value="">
  <input type="hidden" name="sort_order" value="">
  <?php foreach ($informations as $information) { ?>
  <input type="hidden" name="language[<?php echo $information['language_id']; ?>][title]" value="">
  <input type="hidden" name="language[<?php echo $information['language_id']; ?>][description]" value="">
  <?php } ?>
  <input type="hidden" name="information_id" id="information_id" value="<?php echo $information_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs();document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
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
 <em></em>
 <div class="help" onclick="ShowDesc()"><img src="template/<?php echo $this->directory?>/image/help.png" width=31 height=30 alt="<?php echo $button_help; ?>" title="<?php echo $button_help; ?>" class="png"></div>
</div>
<div class="description"><?php echo $heading_description; ?></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($informations as $information) { ?>
            <a><div class="tab_text"><?php echo $information['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($informations as $information) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_title; ?></td>
                    <td><input name="language[<?php echo $information['language_id']; ?>][title]" value="<?php echo $information['title']; ?>">
                      <?php if (@$error_title[$information['language_id']]) { ?>
                      <span class="error"><?php echo $error_title[$information['language_id']]; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td valign="top" class="set"><span class="required">*</span> <?php echo $entry_description; ?></td>
                    <td><textarea name="language[<?php echo $information['language_id']; ?>][description]" id="description<?php echo $information['language_id']; ?>"><?php echo $information['description']; ?></textarea>
		    </td>
                  </tr>
                  <?php if (@$error_description[$information['language_id']]) { ?>
                  <tr>
                    <td></td>
                    <td>
                     <span class="error"><?php echo $error_description[$information['language_id']]; ?></span>
                  <?php if (@$error_mod_description[$information['language_id']]) { ?>
                     <textarea name="language[<?php echo $information['language_id']; ?>][mod_description]" id="mod_description<?php echo $information['language_id']; ?>"><?php echo $error_mod_description[$information['language_id']]; ?></textarea>
                  <?php } ?>
                    </td>
                  </tr>
                  <?php } ?>
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
              <td class="set"><?php echo $entry_hide; ?></td>
              <td><?php if ($information_hide) { ?>
                <input type="radio" name="information_hide" value="1" id="ihyes" checked>
                <label for="ihyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="information_hide" value="0" id="ihno">
                <label for="ihno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="information_hide" value="1" id="ihyes">
                <label for="ihyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="information_hide" value="0" id="ihno" checked>
                <label for="ihno"><?php echo $text_no; ?></label>
                <?php } ?>
                <?php if ($error_hide) { ?>
                  <span class="error"><?php echo $error_hide; ?></span>
                <?php } ?>
              </td>
		<td class="expl"><?php echo $explanation_hide; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_sort_order; ?></td>
              <td><input class="validate_int" id="sort_order" name="sort_order" value="<?php echo $sort_order; ?>" size="1">
                <?php if ($error_sort_order) { ?>
                  <span class="error"><?php echo $error_sort_order; ?></span>
                <?php } ?>
              </td>
		<td class="expl"><?php echo $explanation_sort_order; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
  tabview_initialize('tabmini');
  </script>
  <script type="text/javascript">
  <?php foreach ($informations as $information) { ?>
    CKEDITOR.replace( 'description<?php echo $information['language_id']; ?>' );
  <?php } ?>
  </script>
  <script type="text/javascript">
  <?php foreach ($informations as $information) { ?>
    CKEDITOR.replace( 'mod_description<?php echo $information['language_id']; ?>' );
  <?php } ?>
  </script>
  <script type="text/javascript">
    $('input[name="language[1][title]"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
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
		url:     'index.php?controller=information&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
	function saveTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#information_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=information&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#information_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=information&action=tab',
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
		document.forms['update_form'].information_hide.value=document.forms['form'].information_hide.value;
		document.forms['update_form'].sort_order.value=document.forms['form'].sort_order.value;
		<?php foreach ($informations as $information) { ?>
			document.forms['update_form'].elements['language[<?php echo $information['language_id']; ?>][title]'].value=document.forms['form'].elements['language[<?php echo $information['language_id']; ?>][title]'].value;
			document.forms['update_form'].elements['language[<?php echo $information['language_id']; ?>][description]'].value=CKEDITOR.instances.description<?php echo $information['language_id']; ?>.getData();
		<?php } ?>
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
  <script type="text/javascript">
    $(document).ready(function() {
	  RegisterValidation();
    });
  </script>
</form>
