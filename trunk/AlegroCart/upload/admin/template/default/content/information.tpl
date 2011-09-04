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
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
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
                      <?php if ($error_title) { ?>
                      <span class="error"><?php echo $error_title; ?></span>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td valign="top" class="set"><span class="required">*</span> <?php echo $entry_description; ?></td>
                    <td><textarea name="language[<?php echo $information['language_id']; ?>][description]" id="description<?php echo $information['language_id']; ?>"><?php echo $information['description']; ?></textarea>
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
              <td width="185" class="set"><?php echo $entry_sort_order; ?></td>
              <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1"></td>
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
  var sBasePath           = '<?php echo $URL_TPL->get_server().'javascript/fckeditor/'?>';
  <?php foreach ($informations as $information) { ?>
	var oFCKeditor          = new FCKeditor('description<?php echo $information['language_id']; ?>');
	oFCKeditor.BasePath = sBasePath;
	oFCKeditor.Value	  = document.getElementById('description<?php echo $information['language_id']; ?>').value;
	oFCKeditor.Width    = '600';
	oFCKeditor.Height   = '300';
	oFCKeditor.Config['CustomConfigurationsPath'] = oFCKeditor.BasePath + 'myconfig.js';
	oFCKeditor.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
	oFCKeditor.Config['SkinPath'] = oFCKeditor.BasePath + 'editor/skins/silver/' ;
	oFCKeditor.ToolbarSet = 'Custom' ;
	oFCKeditor.ReplaceTextarea();
  <?php } ?>	  
  //--></script>
</form>
