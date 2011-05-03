<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/default/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png" /><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png" /><?php echo $button_insert; ?></div>
  <div class="disabled"><img src="template/default/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png" /><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" /><?php echo $button_delete; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" /><?php echo $button_save; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" /><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" />
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_module; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
		  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">	
            <table>
              <tr>
                <td width="100" class="set"><?php echo $entry_status; ?></td>
                <td><select name="catalog_imagedisplay_status">
                  <?php if ($catalog_imagedisplay_status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
				<td class="expl"><?php echo $text_save;?></td>
              </tr>
            </table>
			<input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
		  </form>
		  <form action="<?php echo $action_home; ?>" method="post" enctype="multipart/form-data" id="entryform">
			<table><tr>_______________________________________________</tr>
			  <tr><td class="set"><?php echo $text_imagedisplay; ?></td>
			  <td><input type="submit" class="submit" value="<?php echo $button_home; ?>">
			  </td></tr>
			</table>
	      </form>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>