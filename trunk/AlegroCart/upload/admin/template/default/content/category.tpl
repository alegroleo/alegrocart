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
<script type="text/javascript" src="javascript/ckeditor/ckeditor.js"></script> 
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
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
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  <?php foreach ($categories as $category) { ?>
    CKEDITOR.replace( 'description<?php echo $category['language_id']; ?>' );
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
  <script type="text/javascript"><!--original idea by Alen Grakalic
  $(function() {

	var xOffset = 10;
	var yOffset = 20;

	if (typeof window.innerHeight != 'undefined') effectiveHeight = window.innerHeight;
	else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientHeight !='undefined' && document.documentElement.clientHeight != 0) effectiveHeight = document.documentElement.clientHeight;
	else effectiveHeight = document.getElementsByTagName('body')[0].clientHeight;

	var scrolled = 0;

  $("#image_to_preview option").hover(function (event) {
	this.t = this.title;
	this.title = "";
	
	if (typeof window.pageYOffset == 'number') scrolled = window.pageYOffset;
	else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) scrolled = document.body.scrollTop;
	else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) scrolled = document.documentElement.scrollTop;

	$("body").append("<p id='preview'><img src='"+ this.t + "' >" + "</p>");								 
	$("#preview").css("top", (event.pageY - scrolled < effectiveHeight/2 ? (event.pageY - xOffset) : event.pageY - xOffset - (this.t.substr(-7,3))) + "px").css("left",(event.pageX + yOffset) + "px").fadeIn("fast");		
  },
  function() {
	this.title = this.t;
	$("#preview").remove();
  }
  );

  $("#image_to_preview option").mousemove(function(event){
	$("#preview").css("top",(event.pageY - scrolled < effectiveHeight/2 ? (event.pageY - xOffset) : event.pageY - xOffset - (this.t.substr(-7,3))) + "px").css("left",(event.pageX + yOffset) + "px");
  });
  });
  //--></script>
</form>
