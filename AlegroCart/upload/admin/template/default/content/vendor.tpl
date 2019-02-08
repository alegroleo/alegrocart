<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="name" value="">
  <input type="hidden" name="status" value="">
  <input type="hidden" name="image_id" value="">
  <input type="hidden" name="description" value="">
  <input type="hidden" name="discount" value="">
  <input type="hidden" name="address_id" value="<?php echo $address_id;?>">
  <input type="hidden" name="firstname" value="">
  <input type="hidden" name="lastname" value="">
  <input type="hidden" name="email" value="">
  <input type="hidden" name="telephone" value="">
  <input type="hidden" name="fax" value="">
  <input type="hidden" name="website" value="">
  <input type="hidden" name="trade" value="">
  <input type="hidden" name="company" value="">
  <input type="hidden" name="address_1" value="">
  <input type="hidden" name="address_2" value="">
  <input type="hidden" name="postcode" value="">
  <input type="hidden" name="city" value="">
  <input type="hidden" name="country_id" value="">
  <input type="hidden" name="zone_id" value="">
  <input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo $vendor_id; ?>">
  </form>
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
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_vendor; ?></div></a><a><div class="tab_text"><?php echo $tab_address; ?></div></a><a><div class="tab_text"><?php echo $tab_product; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
          <table>
            <tr>
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input name="name" value="<?php echo $name; ?>">
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
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
              <td valign="top" class="set"><?php echo $entry_description; ?></td>
              <td><textarea name="description" cols="40" rows="7"><?php echo $description; ?></textarea></td>
	      <td class="expl"><?php echo $explanation_description; ?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><?php echo $entry_discount; ?></td>
              <td><textarea name="discount" cols="40" rows="7"><?php echo $discount; ?></textarea></td>
	      <td class="expl"><?php echo $explanation_discount; ?></td>
            </tr>
          </table>
        </div>
      </div>
     <div class="page">
       <div class="pad">
         <table>
            <tr>
              <td width="185" class="set"><?php echo $entry_firstname; ?></td>
              <td><input class="validate_alpha" id="firstname" type="text" name="firstname" value="<?php echo $firstname; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_lastname; ?></td>
              <td><input class="validate_alpha" id="lastname" type="text" name="lastname" value="<?php echo $lastname; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_email; ?></td>
              <td><input class="validate_mail" id="email" type="text" name="email" value="<?php echo $email; ?>">
                <?php if ($error_email) { ?>
                <span class="error"><?php echo $error_email; ?></span>
                <?php  } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_telephone; ?></td>
              <td><input class="validate_phone" id="telephone" type="text" name="telephone" value="<?php echo $telephone; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_fax; ?></td>
              <td><input class="validate_phone" id="fax" type="text" name="fax" value="<?php echo $fax; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_website; ?></td>
              <td><input class="validate_url" id="website" type="text" name="website" value="<?php echo $website; ?>"></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_trade; ?></td>
              <td><input class="validate_url" id="trade" type="text" name="trade" value="<?php echo $trade; ?>"></td>
            </tr>
       </table>
     </div>
    </div>
     <div class="page">
       <div class="pad">
         <table>
	    <tr>
              <td class="set"><?php echo $entry_company; ?></td>
              <td><input class="validate_alpha_num" id="company" type="text" name="company" value="<?php echo $company; ?>"></td>
            </tr>
	    <tr>
	      <td class="set"><?php echo $entry_address_1; ?></td>
              <td><input class="validate_alpha_num" id="address_1" type="text" name="address_1" value="<?php echo $address_1; ?>">
              </td>
	    </tr>
	    <tr>
	      <td class="set"><?php echo $entry_address_2; ?></td>
		<td><input class="validate_alpha_num" id="address_2" type="text" name="address_2" value="<?php echo $address_2; ?>"></td>
	    </tr>
	    <tr>
	      <td class="set"><?php echo $entry_postcode; ?></td>
	      <td><input class="validate_alpha_num" id="postcode" type="text" name="postcode" value="<?php echo $postcode; ?>"></td>
		  <td class="expl"><?php echo $text_no_postal;?></td>
	    </tr>
	    <tr>
	      <td class="set"><?php echo $entry_city; ?></td>
	      <td><input class="validate_alpha_num" id="city" type="text" name="city" value="<?php echo $city; ?>"></td>
	    </tr>
	    <tr>
	      <td class="set"><?php echo $entry_country; ?></td>
	      <td><select name="country_id" onchange="$('#zone').load('index.php?controller=vendor&action=zone&country_id='+this.value+'&zone_id=<?php echo $zone_id; ?>');">
              <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
				  <option value="<?php echo $country['country_id']; ?>" SELECTED><?php echo $country['name']; ?></option>
				<?php } else { ?>
				  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
				<?php } ?>
              <?php } ?>
              </select>
              </td>
	    </tr>
		<tr>
		<td class="set"><?php echo $entry_zone; ?></td>
		<td id="zone"><select name="zone_id">
		</select></td>
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
	    </td>
 	    <td class="expl"><?php echo $explanation_multiselect;?></td>
         </tr>
       </table>
     </div>
    </div>
   </div>
  </div>
  <input type="hidden" name="address_id" value="<?php echo $address_id;?>">
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  $('#image').load('index.php?controller=image&action=view&image_id='+document.getElementById('image_id').value);
  //--></script>
  <script type="text/javascript"><!--
  $('#zone').load('index.php?controller=vendor&action=zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
  //--></script>
  <script type="text/javascript"><!--
    $('input[name="name"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  //--></script>
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
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
		url:     'index.php?controller=vendor&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	$('.tabs a').on('click', function() {
	var activeTab = $(this).index()+1;
	var id = $('#vendor_id').val();
	var data_json = {'activeTab':activeTab, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=vendor&action=tab',
		data: data_json,
		dataType:'json'
	});
	});
  //--></script>
  <script type="text/javascript"><!--
  $(document).ready(function() {
	if (<?php echo $tab; ?>!=undefined && <?php echo $tab; ?> > 0) {
		tabview_switch('tab', <?php echo $tab; ?>);
	}
   });
  //--></script>
  <script type="text/javascript"><!--
	function getValues() {
		document.forms['update_form'].name.value=document.forms['form'].name.value;
		document.forms['update_form'].status.value=document.forms['form'].status.value;
		document.forms['update_form'].image_id.value=document.forms['form'].image_id.value;
		document.forms['update_form'].description.value=document.forms['form'].description.value;
		document.forms['update_form'].discount.value=document.forms['form'].discount.value;
		document.forms['update_form'].firstname.value=document.forms['form'].firstname.value;
		document.forms['update_form'].lastname.value=document.forms['form'].lastname.value;
		document.forms['update_form'].email.value=document.forms['form'].email.value;
		document.forms['update_form'].telephone.value=document.forms['form'].telephone.value;
		document.forms['update_form'].fax.value=document.forms['form'].fax.value;
		document.forms['update_form'].website.value=document.forms['form'].website.value;
		document.forms['update_form'].trade.value=document.forms['form'].trade.value;
		document.forms['update_form'].company.value=document.forms['form'].company.value;
		document.forms['update_form'].address_1.value=document.forms['form'].address_1.value;
		document.forms['update_form'].address_2.value=document.forms['form'].address_2.value;
		document.forms['update_form'].postcode.value=document.forms['form'].postcode.value;
		document.forms['update_form'].city.value=document.forms['form'].city.value;
		document.forms['update_form'].country_id.value=document.forms['form'].country_id.value;
		document.forms['update_form'].zone_id.value=document.forms['form'].zone_id.value;

		getMultipleSelection('form', 'productdata[]', 'update_form');

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
  //--></script>
</form>
