<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="fileName" value="">
  <?php foreach ($images as $image) { ?>
  <input type="hidden" name="language[<?php echo $image['language_id']; ?>][title]" value="">
  <?php } ?>
  <input type="hidden" name="image_id" id="image_id" value="<?php echo $image_id; ?>">
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
            <?php foreach ($images as $image) { ?>
            <a><div class="tab_text"><?php echo $image['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($images as $image) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_title; ?></td>
                    <td><input name="language[<?php echo $image['language_id']; ?>][title]" value="<?php echo $image['title']; ?>">
                      <?php if ($error_title) { ?>
                      <span class="error"><?php echo $error_title; ?></span>
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
	    <?php if ($wm_active) { ?><tr><td colspan=2>
		   <b style="color:red;"><?php echo $text_autowm_warning; ?></b> 
	    </td></tr><?php } ?>
            <tr>
              <td style="width: 100px;" class="set"><?php echo $entry_filename; ?></td>
              <td><input type="text" id="fileName" class="file_input_textbox" readonly="readonly">
	      <div class="file_input_div">
	      <input type="button" value="<?php echo $text_browse; ?>" class="file_input_button" />
	      <input type="file" name="image" class="file_input_hidden" onchange="javascript: document.getElementById('fileName').value = this.value" />
	      </div>
                <?php if ($error_file) { ?>
                <span class="error"><?php echo $error_file; ?></span>
                <?php } ?></td>
            </tr>
		  </table>
		  <?php if(isset($image_data)){?>
			<table>
			  <tr>
			    <td class="set"><?php echo $text_image_filename . '<b style="color:#0099FF;">' . $image_data[0]['filename'] . '</b>';?></td>
				<td id="image"><img src="<?php echo $image_data[0]['thumb'];?>" alt="" title=""></td>
			  </tr>
			</table>
		  <?php }?>
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
    $('input[name="language[1][title]"]').change(function () {
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
		url:     'index.php?controller=image&action=help',
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
	var id = $('#image_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=image&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#image_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=image&action=tab',
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
		document.forms['update_form'].fileName.value=document.forms['form'].fileName.value;
		<?php foreach ($images as $image) { ?>
			document.forms['update_form'].elements['language[<?php echo $image['language_id']; ?>][title]'].value=document.forms['form'].elements['language[<?php echo $image['language_id']; ?>][title]'].value;
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
</form>
