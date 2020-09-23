<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
  $head_def->set_admin_javascript("javascript/preview/preview.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" width=32 height=32 alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="getValues();document.getElementById('update_form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <input type="hidden" name="update_form" value="1">
  <input type="hidden" name="author" value="">
  <input type="hidden" name="product_id" value="">
  <input type="hidden" name="text" value="">
  <input type="hidden" name="rating1" value="">
  <input type="hidden" name="rating2" value="">
  <input type="hidden" name="rating3" value="">
  <input type="hidden" name="rating4" value="">
  <input type="hidden" name="status" value="">
  <input type="hidden" name="review_id" value="<?php echo $review_id; ?>">
  </form>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" width=32 height=32 alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
  <?php if (@$delete) { ?>
  <div class="enabled delete" onmouseover="className='hover delete'" onmouseout="className='enabled delete'" onclick="if (confirm('Are you sure you want to delete?')) { location='<?php echo $delete; ?>'; } else { return; }"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" width=32 height=32 alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <?php } ?>
  <div class="enabled store" onmouseover="className='hover store'" onmouseout="className='enabled store'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/save_enabled.png" width=32 height=32 alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/print_disabled.png" width=32 height=32 alt="<?php echo $button_print; ?>" class="png"><?php echo $button_print; ?></div>
  <div class="enabled cancel" onmouseover="className='hover cancel'" onmouseout="className='enabled cancel'" onclick="location='<?php echo $cancel; ?>'"><img src="template/<?php echo $this->directory?>/image/cancel_enabled.png" width=32 height=32 alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
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
              <td width="185" class="set"><span class="required">*</span> <?php echo $entry_author; ?></td>
              <td><input class="validate_alpha" id="author" type="text" name="author" value="<?php echo $author?>">
                <?php if ($error_author) { ?>
                <span class="error"><?php echo $error_author; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_product; ?></td>
              <td><select id="image_to_preview" name="product_id">
                  <?php foreach ($products as $product) { ?>
                  <?php if ($product['product_id'] == $product_id) { ?>
                  <option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>" selected><?php echo $product['name']; ?></option>
                  <?php } else { ?>
                  <option title="<?php echo $product['previewimage']; ?>" value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <?php if ($error_product) { ?>
                <span class="error"><?php echo $error_product; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td valign="top" class="set"><span class="required">*</span> <?php echo $entry_text; ?></td>
              <td><textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
                <?php if ($error_text) { ?>
                <span class="error"><?php echo $error_text; ?></span>
                <?php } ?></td>
            </tr>
            <?php for ($i=1; $i<5; $i++) { ?>
            <tr>
              <td class="set"><?php echo ${'entry_rating'.$i}; ?></td>
              <td><b class="rating"><?php echo $entry_bad; ?></b>&nbsp;
                <?php if (@${'rating'.$i} == 1) { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="1" checked>
                <?php } else { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="1">
                <?php } ?>
                &nbsp;
                <?php if (@${'rating'.$i} == 2) { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="2" checked>
                <?php } else { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="2">
                <?php } ?>
                &nbsp;
                <?php if (@${'rating'.$i} == 3) { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="3" checked>
                <?php } else { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="3">
                <?php } ?>
                &nbsp;
                <?php if (@${'rating'.$i} == 4) { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="4" checked>
                <?php } else { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="4">
                <?php } ?>
                &nbsp;
                <?php if (@${'rating'.$i} == 5) { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="5" checked>
                <?php } else { ?>
                <input type="radio" name="rating<?php echo $i; ?>" value="5">
                <?php } ?>
                &nbsp; <b class="rating"><?php echo $entry_good; ?></b>
                <?php if (${'error_rating'.$i}) { ?>
                <span class="error"><?php echo ${'error_rating'.$i}; ?></span>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <tr>
              <td class="set"><?php echo $entry_status; ?></td>
              <td><?php if ($status == 1) { ?>
                <input type="radio" name="status" value="1" id="enabled" checked>
                <label for="enabled"><?php echo $text_enabled; ?></label>
                <input type="radio" name="status" value="0" id="disabled">
                <label for="disabled"><?php echo $text_disabled; ?></label>
                <?php } else { ?>
                <input type="radio" name="status" value="1" id="enabled">
                <label for="enabled"><?php echo $text_enabled; ?></label>
                <input type="radio" name="status" value="0" id="disabled" checked>
                <label for="disabled"><?php echo $text_disabled; ?></label>
                <?php } ?>
                <?php if ($error_status) { ?>
                <span class="error"><?php echo $error_status; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form>
  <script type="text/javascript">
  tabview_initialize('tab');
  </script>
  <script type="text/javascript">
    $('#author').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  </script>
  <script type="text/javascript">
	function getValues() {
		document.forms['update_form'].author.value=document.forms['form'].author.value;
		document.forms['update_form'].product_id.value=document.forms['form'].product_id.value;
		document.forms['update_form'].text.value=document.forms['form'].text.value;
		document.forms['update_form'].rating1.value=document.forms['form'].rating1.value;
		document.forms['update_form'].rating2.value=document.forms['form'].rating2.value;
		document.forms['update_form'].rating3.value=document.forms['form'].rating3.value;
		document.forms['update_form'].rating4.value=document.forms['form'].rating4.value;
		document.forms['update_form'].status.value=document.forms['form'].status.value;
	}
  </script>
  <script type="text/javascript">
  $(document).ready(function() {
	$('.task').each(function(){
		$('.task .disabled').hide(0);
	});
	<?php if (!$help) { ?>
		$('.description').hide(0);
	<?php } ?>
 });
  function ShowDesc(){
	$.ajax({
		type:    'POST',
		url:     'index.php?controller=review&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
	  RegisterValidation();
    });
  </script>
