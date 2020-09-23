<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="name" value="">
  <input type="hidden" name="user_group_id" id="user_group_id" value="<?php echo $user_group_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input class="validate_alpha" id="name" type="text" name="name" value="<?php echo $name; ?>">
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><?php echo $entry_access; ?></td>
		<td><?php echo $entry_all_access; ?><input name="all_access" type="checkbox"<?php echo (isset($all_access))?'CHECKED':''?>><br>
		<select name="access[]" multiple="multiple" size="15">
                  <?php foreach ($permissions as $permission) { ?>
                  <?php if (!$permission['access']) { ?>
                  <option value="<?php echo $permission['name']; ?>"><?php echo $permission['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $permission['name']; ?>" selected><?php echo $permission['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td class="expl"><?php echo $explanation_multiselect;?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><?php echo $entry_modify; ?></td>
		<td><?php echo $entry_all_modify; ?><input name="all_modify" type="checkbox"<?php echo isset($all_modify)?'CHECKED':''?>><br>
		<select name="modify[]" multiple="multiple" size="15">
                  <?php foreach ($permissions as $permission) { ?>
                  <?php if (!$permission['modify']) { ?>
                  <option value="<?php echo $permission['name']; ?>"><?php echo $permission['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $permission['name']; ?>" selected><?php echo $permission['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
	      <td class="expl"><?php echo $explanation_multiselect;?></td>
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
    $('input[name="name"]').change(function () {
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
		url:     'index.php?controller=usergroup&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
	function getValues() {
		document.forms['update_form'].name.value=document.forms['form'].name.value;

		getCheckedBoxes('form', 'all_access', 'update_form');
		getCheckedBoxes('form', 'all_modify', 'update_form');

		getMultipleSelection('form', 'access[]', 'update_form');
		getMultipleSelection('form', 'modify[]', 'update_form');
	}
	function getMultipleSelection(formName,elementName,newFormName){ 
		var html ='';
		var mySelect = document.forms[formName].elements[elementName];
		for(let j = 0; j < mySelect.options.length; j++) { 
			if(mySelect.options[j].selected) { 
				html +='<input type="hidden" name="' + elementName + '" value="' + mySelect.options[j].value + '">';
			}
		}
		document.forms[newFormName].innerHTML += html;
	}
	function getCheckedBoxes(formName,elementName,newFormName) {
		var html=''
		var checkboxes = document.getElementsByName(elementName);
		for (i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked) {
				html +='<input type="hidden" name="' + elementName + '" value="' + checkboxes[i].value + '">';
			}
		}
		document.forms[newFormName].innerHTML += html;
	}
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
	  RegisterValidation();
    });
  </script>
</form>
