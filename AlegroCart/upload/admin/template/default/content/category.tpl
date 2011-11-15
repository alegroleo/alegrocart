<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/default/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png" ><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/default/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png" ><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png" ><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/default/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png" ><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/default/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png" ><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" ><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" ><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" ><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css" >
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script> 
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a><a><div class="tab_text"><?php echo $tab_image; ?></div></a> </div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($categories as $category) { ?>
            <a><div class="tab_text"><?php echo $category['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($categories as $category) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td><input name="language[<?php echo $category['language_id']; ?>][name]" value="<?php echo $category['name']; ?>" >
                      <?php if ($error_name) { ?>
                      <span class="error"><?php echo $error_name; ?></span>
                      <?php } ?></td>
                  </tr>
				  
                  <tr>  <!-- New Meta Tags -->
                    <td width="185" class="set"> <?php echo $entry_meta_title; ?></td>
                    <td><input size="60" maxlength="60" name="meta_title[<?php echo $category['language_id']; ?>]" value="<?php echo $category['meta_title']; ?>"></td> 
                  </tr>
				  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_description; ?></td>
                    <td><input size="100" maxlength="120" name="meta_description[<?php echo $category['language_id']; ?>]" value="<?php echo $category['meta_description']; ?>"></td>					
				  </tr>
				  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_keywords; ?></td>
                    <td><input size="100" maxlength="120" name="meta_keywords[<?php echo $category['language_id']; ?>]" value="<?php echo $category['meta_keywords']; ?>"></td>
				  </tr>	 <!-- End Meta Tags -->			  
				  
                  <tr>
                    <td valign="top" class="set"><?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $category['language_id']; ?>]" id="description<?php echo $category['language_id']; ?>"><?php echo $category['description']; ?></textarea>
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
              <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" ></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_image; ?></td>
              <td><select name="image_id" id="image_id" onchange="$('#image').load('index.php?controller=image&action=view&image_id='+this.value);">
                  <?php foreach ($images as $image) { ?>
                  <?php if ($image['image_id'] == $image_id) { ?>
                  <option value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td></td>
              <td id="image"></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  var sBasePath           = '<?php echo $URL_TPL->get_server().'javascript/fckeditor/'?>';
  <?php foreach ($categories as $category) { ?>
  var oFCKeditor<?php echo $category['language_id']; ?>          = new FCKeditor('description<?php echo $category['language_id']; ?>');
      oFCKeditor<?php echo $category['language_id']; ?>.BasePath = sBasePath;
      oFCKeditor<?php echo $category['language_id']; ?>.Value    = document.getElementById('description<?php echo $category['language_id']; ?>').value;
      oFCKeditor<?php echo $category['language_id']; ?>.Width    = '600';
      oFCKeditor<?php echo $category['language_id']; ?>.Height   = '300';
      oFCKeditor<?php echo $category['language_id']; ?>.Config['CustomConfigurationsPath'] = oFCKeditor<?php echo $category['language_id']; ?>.BasePath + 'myconfig.js';
      oFCKeditor<?php echo $category['language_id']; ?>.Config['DocType'] = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
      oFCKeditor<?php echo $category['language_id']; ?>.Config['SkinPath'] = oFCKeditor<?php echo $category['language_id']; ?>.BasePath + 'editor/skins/silver/' ;
      oFCKeditor<?php echo $category['language_id']; ?>.ToolbarSet = 'Custom' ;
      oFCKeditor<?php echo $category['language_id']; ?>.ReplaceTextarea();
  <?php } ?>      
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  <script type="text/javascript"><!--
  $('#image').load('index.php?controller=image&action=view&image_id='+document.getElementById('image_id').value);
  //--></script>
</form>
