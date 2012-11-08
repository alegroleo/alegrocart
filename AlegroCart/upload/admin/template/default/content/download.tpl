<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
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
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?><em></em></div>
<div class="description"><?php echo $heading_description; ?></div>
<script type="text/javascript" src="javascript/tab/tab.js"></script>
<script type="text/javascript" src="javascript/ajax/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="javascript/tab/tab.css">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
    <div class="tabs"><a><div class="tab_text"><?php echo $tab_general; ?></div></a><a><div class="tab_text"><?php echo $tab_data; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div id="tabmini">
          <div class="tabs">
            <?php foreach ($downloads as $download) { ?>
            <a><div class="tab_text"><?php echo $download['language']; ?></div></a>
            <?php } ?>
          </div>
          <div class="pages">
            <?php foreach ($downloads as $download) { ?>
            <div class="page">
              <div class="minipad">
                <table>
                  <tr>
                    <td width="185" class="set"><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td><input name="language[<?php echo $download['language_id']; ?>][name]" value="<?php echo $download['name']; ?>">
                      <?php if ($error_name) { ?>
                      <span class="error"><?php echo $error_name; ?></span>
                      <?php } ?></td>
		    <td class="expl">
			    <?php echo $explanation_name; ?> 
		    </td>
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
              <td width="185" class="set"><?php echo $entry_filename; ?></td>
               <td><input type="text" id="fileName" class="file_input_textbox" readonly="readonly" value="<?php echo $filename;?>">
	      <?php if ($error_file) { ?>
                <span class="error"><?php echo $error_file; ?></span>
                <?php } ?>
	      <td><div class="file_input_div">
	      <input type="button" value="<?php echo $text_browse; ?>" class="file_input_button" />
	      <input type="file" id="download" name="download" class="file_input_hidden" onchange="javascript: document.getElementById('fileName').value = this.value; set_mask()" />
	      </div><td>
                </td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_mask; ?></td>
              <td><input type="input" id="mask" name="mask" value="<?php echo $mask; ?>">
                <?php if ($error_mask) { ?>
                <span class="error"><?php echo $error_mask; ?></span>
                <?php } ?></td>
		<td class="expl">
		    <?php echo $explanation_mask; ?> 
		</td>
            </tr>
            <tr>
              <td class="set"><?php echo $entry_remaining; ?></td>
              <td><input type="input" id="remaining" name="remaining" value="<?php echo $remaining; ?>" size="6"></td>
	      <td class="expl">
		    <?php echo $explanation_remaining; ?> 
	      </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
  <script type="text/javascript"><!--
  function set_mask(){
  var url = $('#download').val();  
  var Filename = url.substring(url.lastIndexOf('/')+1);
  var Extension = Filename.substring(Filename.lastIndexOf("."));
  Mask = RandomNumber(Filename)+Extension;
  $('#mask').val(Mask);
  }
  function RandomNumber(Filename){
	var Seed = Filename.substring(0,Filename.lastIndexOf("."));
	var Filecount = Seed.length;
	var NumberSeed = '';
	for(intI = 0; intI < Filecount; intI++){
		NumberSeed += String(Seed.charCodeAt(intI));
	}
	NumberSeed = NumberSeed.substring(0,16);
	D = new Date();
	SeedTime = D.getTime();
	var Mask = String(SeedTime + parseInt(NumberSeed))
	return Mask;
  }
   //--></script> 
  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
  tabview_initialize('tabmini');
  //--></script>
  <script type="text/javascript"><!--
    $('input[name="language[1][name]"]').change(function () {
      var value = $(this).val();
      $(".heading em").text(value);
    }).change();
  //--></script>
</form>
