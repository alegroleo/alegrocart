<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/ckeditor/ckeditor.js");
?>
<div class="task">
  <div class="disabled"><img src="template/default/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png" ><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getTabs();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="category_hide" value="">
  <input type="hidden" name="sort_order" value="">
  <input type="hidden" name="image_id" value="">
  <?php foreach ($categories as $category) { ?>
    <input type="hidden" name="language[<?php echo $category['language_id']; ?>][name]" value="">
    <input type="hidden" name="meta_description[<?php echo $category['language_id']; ?>]" value="">
    <input type="hidden" name="meta_keywords[<?php echo $category['language_id']; ?>]" value="">
    <input type="hidden" name="meta_title[<?php echo $category['language_id']; ?>]" value="">
    <input type="hidden" name="description[<?php echo $category['language_id']; ?>]" value="">
  <?php } ?>
  <input type="hidden" name="category_id" id="category_id" value="<?php echo $category_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/default/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png" ><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/default/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png" ><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/default/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png" ><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="saveTabs();document.getElementById('form').submit();"><img src="template/default/image/save_enabled.png" alt="<?php echo $button_save; ?>" class="png" ><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" alt="<?php echo $button_print; ?>" class="png" /><?php echo $button_print; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $cancel; ?>'"><img src="template/default/image/cancel_enabled.png" alt="<?php echo $button_cancel; ?>" class="png" ><?php echo $button_cancel; ?></div>
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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a><a><div class="tab_text"><?php echo $tab_image; ?></div></a><a><div class="tab_text"><?php echo $tab_product; ?></div></a></div>
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
                      <?php if (@$error_name[$category['language_id']]) { ?>
                      <span class="error"><?php echo $error_name[$category['language_id']]; ?></span>
                      <?php } ?></td>
                  </tr>
				  
                  <tr>  <!-- New Meta Tags -->
                    <td width="185" class="set"> <?php echo $entry_meta_title; ?></td>
                    <td><input class="validate_meta" id="meta_title<?php echo $category['language_id']; ?>" size="60" maxlength="60" name="meta_title[<?php echo $category['language_id']; ?>]" value="<?php echo $category['meta_title']; ?>">
                      <?php if (@$error_meta_title[$category['language_id']]) { ?>
                      <span class="error"><?php echo $error_meta_title[$category['language_id']]; ?></span>
                      <?php } ?></td> 
                  </tr>
				  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_description; ?></td>
                    <td><input class="validate_meta" id="meta_description<?php echo $category['language_id']; ?>" size="100" maxlength="120" name="meta_description[<?php echo $category['language_id']; ?>]" value="<?php echo $category['meta_description']; ?>">
                      <?php if (@$error_meta_description[$category['language_id']]) { ?>
                      <span class="error"><?php echo $error_meta_description[$category['language_id']]; ?></span>
                      <?php } ?></td>
				  </tr>
				  <tr>
                    <td width="185" class="set"> <?php echo $entry_meta_keywords; ?></td>
                    <td><input class="validate_meta" id="meta_keywords<?php echo $category['language_id']; ?>" size="100" maxlength="120" name="meta_keywords[<?php echo $category['language_id']; ?>]" value="<?php echo $category['meta_keywords']; ?>">
                      <?php if (@$error_meta_keywords[$category['language_id']]) { ?>
                      <span class="error"><?php echo $error_meta_keywords[$category['language_id']]; ?></span>
                      <?php } ?></td>
				  </tr>	 <!-- End Meta Tags -->
                  <tr>
                    <td valign="top" class="set"><?php echo $entry_description; ?></td>
                    <td><textarea name="description[<?php echo $category['language_id']; ?>]" id="description<?php echo $category['language_id']; ?>"><?php echo $category['description']; ?></textarea>
		    </td>
                  </tr>
                  <?php if (@$error_description[$category['language_id']]) { ?>
                  <tr>
                    <td></td>
                    <td>
                     <span class="error"><?php echo $error_description[$category['language_id']]; ?></span>
                     <?php if (@$error_mod_description[$category['language_id']]) { ?>
                         <textarea name="language[<?php echo $category['language_id']; ?>][mod_description]" id="mod_description<?php echo $category['language_id']; ?>"><?php echo $error_mod_description[$category['language_id']]; ?></textarea>
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
              <td><?php if ($category_hide) { ?>
                <input type="radio" name="category_hide" value="1" id="chyes" checked>
                <label for="chyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="category_hide" value="0" id="chno">
                <label for="chno"><?php echo $text_no; ?></label>
                <?php } else { ?>
                <input type="radio" name="category_hide" value="1" id="chyes">
                <label for="chyes"><?php echo $text_yes; ?></label>
                <input type="radio" name="category_hide" value="0" id="chno" checked>
                <label for="chno"><?php echo $text_no; ?></label>
                <?php } ?>
                <?php if ($error_hide) { ?>
                  <span class="error"><?php echo $error_hide; ?></span>
                <?php } ?>
		</td>
		<td class="expl"><?php echo $explanation_hide; ?></td>
            </tr>
            <tr>
              <td width="185" class="set"><?php echo $entry_sort_order; ?></td>
              <td><input class="validate_int" id="sort_order" name="sort_order" value="<?php echo $sort_order; ?>" size="1" >
                <?php if ($error_sort_order) { ?>
                  <span class="error"><?php echo $error_sort_order; ?></span>
                <?php } ?>
		</td>
		<td class="expl"><?php echo $explanation_sort_order; ?>
		</td>
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
                  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>" selected><?php echo $image['title']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $image['previewimage']; ?>" value="<?php echo $image['image_id']; ?>"><?php echo $image['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td></td>
              <td id="image"></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?php if ($error_image) { ?>
                  <span class="error"><?php echo $error_image; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </div>
     <div class="page">
       <div class="pad">
         <table>
          <tr>
            <td width="185" valign="top" class="set"><?php echo $entry_product; ?></td>
            <td><select id="image_to_preview" name="productdata[]" multiple="multiple" size="15">
          	<?php foreach ($productdata as $product) { ?>
	        <?php if (!$product['productdata']) { ?>
         	<option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
          	<?php } else { ?>
          	<option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
          	<?php } ?>
          	<?php } ?>
        	</select>
                <?php if ($error_assigned) { ?>
                  <span class="error"><?php echo $error_assigned; ?></span>
                <?php } ?>
	    </td>
 	    <td class="expl"><?php echo $explanation_multiselect;?></td>
         </tr>
       </table>
     </div>
    </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  <?php foreach ($categories as $category) { ?>
    CKEDITOR.replace( 'description<?php echo $category['language_id']; ?>' );
  <?php } ?>      
  //--></script>
  <script type="text/javascript"><!--
  <?php foreach ($categories as $category) { ?>
    CKEDITOR.replace( 'mod_description<?php echo $category['language_id']; ?>' );
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
  <script type="text/javascript"><!--
    $('input[name="language[1][name]"]').change(function () {
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
		url:     'index.php?controller=category&action=help',
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
	var id = $('#category_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=category&action=tab',
		data: data_json,
		dataType:'json'
	});
	}
	function getTabs() {
	var activeTab = $('#tab > .tabs > a.active').index()+1;
	var activeTabmini = $('#tabmini > .tabs > a:visible.active').index()+1;
	var id = $('#category_id').val();
	var data_json = {'activeTab':activeTab, 'activeTabmini':activeTabmini, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=category&action=tab',
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
	function getMultipleSelection(formName,elementName,newFormName){ 
		var html ='';
		var mySelect = document.forms[formName].elements[elementName];
		for(j = 0; j < mySelect.options.length; j++) { 
			if(mySelect.options[j].selected) { 
				html +='<input type="hidden" name="' + elementName + '" value="' + mySelect.options[j].value + '">';
			}
		}
		document.forms[newFormName].innerHTML += html;
	}
	function getValues() {
		document.forms['update_form'].category_hide.value=document.forms['form'].category_hide.value;
		document.forms['update_form'].sort_order.value=document.forms['form'].sort_order.value;
		document.forms['update_form'].image_id.value=document.forms['form'].image_id.value;
		<?php foreach ($categories as $category) { ?>
			document.forms['update_form'].elements['description[<?php echo $category['language_id']; ?>]'].value=CKEDITOR.instances.description<?php echo $category['language_id']; ?>.getData();
			document.forms['update_form'].elements['language[<?php echo $category['language_id']; ?>][name]'].value=document.forms['form'].elements['language[<?php echo $category['language_id']; ?>][name]'].value;
			document.forms['update_form'].elements['meta_description[<?php echo $category['language_id']; ?>]'].value=document.forms['form'].elements['meta_description[<?php echo $category['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_keywords[<?php echo $category['language_id']; ?>]'].value=document.forms['form'].elements['meta_keywords[<?php echo $category['language_id']; ?>]'].value;
			document.forms['update_form'].elements['meta_title[<?php echo $category['language_id']; ?>]'].value=document.forms['form'].elements['meta_title[<?php echo $category['language_id']; ?>]'].value;
		<?php } ?>

		getMultipleSelection('form', 'productdata[]', 'update_form');
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
