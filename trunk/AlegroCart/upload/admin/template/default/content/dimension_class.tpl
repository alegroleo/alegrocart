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
    <div class="tabs"><a><?php echo $tab_general; ?></a> <a><?php echo $tab_data; ?></a> </div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($dimension_classes as $dimension_class) { ?>
            <a><?php echo $dimension_class['language']; ?></a>
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
              <td><input type="text" name="rule[<?php echo $dimension_rule['to_id']; ?>]" value="<?php echo $dimension_rule['rule']; ?>"></td>
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
</form>