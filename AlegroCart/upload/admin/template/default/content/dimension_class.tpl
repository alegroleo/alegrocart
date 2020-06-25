<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <?php foreach ($dimension_classes as $dimension_class) { ?>
  <input type="hidden" name="language[<?php echo $dimension_class['language_id']; ?>][title]" value="">
  <input type="hidden" name="language[<?php echo $dimension_class['language_id']; ?>][unit]" value="">
  <?php } ?>
  <?php if(isset($type)){?>
  <input type="hidden" name="type_id" value="">
  <input type="hidden" name="type_name" value="">
  <?php } else {?>
  <input type="hidden" name="type_id" value="">
  <?php }?>
  <?php foreach ($dimension_rules as $dimension_rule) { ?>
  <input type="hidden" name="rule[<?php echo $dimension_rule['to_id']; ?>]" value="">
  <?php } ?>
  <input type="hidden" name="dimension_id" id="dimension_id" value="<?php echo $dimension_id; ?>">
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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a> <a><div class="tab_text"><?php echo $tab_data; ?></div></a> </div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($dimension_classes as $dimension_class) { ?>
            <a><div class="tab_text"><?php echo $dimension_class['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($dimension_classes as $dimension_class) { ?>
            <div class="page">
              <div class="minipad">
                <table>
				  
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_title; ?></td>
                    <td><input name="language[<?php echo $dimension_class['language_id']; ?>][title]" value="<?php echo $dimension_class['title']; ?>">
                      <?php if ($error_title) { ?>
                      <span class="error"><?php echo $error_title; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td class="set"><span class="required">*</span> <?php echo $entry_unit; ?></td>
                    <td><input name="language[<?php echo $dimension_class['language_id']; ?>][unit]" value="<?php echo $dimension_class['unit']; ?>">
                      <?php if ($error_unit) { ?>
                      <span class="error"><?php echo $error_unit; ?></span>
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
			  <td width="185" class="set"><span class="required">*</span> <?php echo $entry_type; ?></td>
			  <?php if(isset($type)){?>
				<td>
				<input type="hidden" name="type_id" value="<?php echo $type['type_id'];?>">
				<input size="32" type="text" name="type_name" readonly="readonly" value="<?php echo $type['type_text'];?>">
			  
			  <?php } else {?>
			    <td>
			      <select id="typeid" name="type_id" onchange="$('#rules').load('index.php?controller=dimension_class&action=dimensionClasses&type_id='+this.value);">
				    <?php foreach($types as $type){?>
				  	  <option value="<?php echo $type['type_id'];?>"<?php if($type['type_id'] == $default_type){ echo ' selected';}?>><?php echo $type['type_text'];?></option> 
				    <?php }?>
			      </select>
			    </td>
			  <?php }?>
			</tr>
		  </table>
		  <table class="rules" id="rules">
            <?php foreach ($dimension_rules as $dimension_rule) { ?>
            <tr>
              <td width="185"><?php echo $dimension_rule['title']; ?></td>
              <td><input class="validate_float" id="rule<?php echo $dimension_rule['to_id']; ?>" type="text" name="rule[<?php echo $dimension_rule['to_id']; ?>]" value="<?php echo $dimension_rule['rule']; ?>"></td>
            </tr>
            <?php } ?>
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
		url:     'index.php?controller=dimension_class&action=help',
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
	var id = $('#dimension_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=dimension_class&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#dimension_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=dimension_class&action=tab',
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
		<?php foreach ($dimension_classes as $dimension_class) { ?>
			document.forms['update_form'].elements['language[<?php echo $dimension_class['language_id']; ?>][title]'].value=document.forms['form'].elements['language[<?php echo $dimension_class['language_id']; ?>][title]'].value;
			document.forms['update_form'].elements['language[<?php echo $dimension_class['language_id']; ?>][unit]'].value=document.forms['form'].elements['language[<?php echo $dimension_class['language_id']; ?>][unit]'].value;
		<?php } ?>
		<?php if(isset($type)){?>
			document.forms['update_form'].type_id.value=document.forms['form'].type_id.value;
			document.forms['update_form'].type_name.value=document.forms['form'].type_name.value;
		<?php } else {?>
			document.forms['update_form'].type_id.value=document.forms['form'].type_id.value;
		<?php }?>
		<?php foreach ($dimension_rules as $dimension_rule) { ?>
			document.forms['update_form'].elements['rule[<?php echo $dimension_rule['to_id']; ?>]'].value=document.forms['form'].elements['rule[<?php echo $dimension_rule['to_id']; ?>]'].value;
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
