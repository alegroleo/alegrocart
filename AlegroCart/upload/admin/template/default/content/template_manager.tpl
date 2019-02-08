<?php 
  $head_def->set_admin_css("template/".$this->directory."/css/tab.css");
  $head_def->set_admin_javascript("javascript/ajax/jquery.js");
  $head_def->set_admin_javascript("javascript/tab/tab.js");
  $head_def->set_admin_javascript("javascript/ajax/validateforms.js");
?>
<div class="task">
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="update_form" name="update_form" >
   <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="getValues();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
   <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
   <input type="hidden" name="update_form" value="1">
   <input type="hidden" name="tpl_controller" value="">
   <input type="hidden" name="text_tpl_controller" value="">
   <input type="hidden" name="tpl_columns" value="">
   <input type="hidden" name="default_columns" value="<?php echo $default_columns;?>">
   <input type="hidden" name="tpl_color" value="">
   <input type="hidden" name="tpl_status" value="">
  <input type="hidden" name="tpl_manager_id" id="tpl_manager_id" value="<?php echo $tpl_manager_id; ?>">
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
	<div class="tabs"><a><div class="tab_text"><?php echo $tab_controller; ?></div></a><a><div class="tab_text"><?php echo $tab_header; ?></div></a><a><div class="tab_text"><?php echo $tab_extra; ?></div></a><a><div class="tab_text"><?php echo $tab_left_column; ?></div></a><a><div class="tab_text"><?php echo $tab_content; ?></div></a><a><div class="tab_text"><?php echo $tab_right_column; ?></div></a><a><div class="tab_text"><?php echo $tab_footer; ?></div></a><a><div class="tab_text"><?php echo $tab_page_bottom; ?></div></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
		  <table>
		    <tr>
			  <td class="set"><?php echo $entry_controller;?></td>
			  <td><?php if($tpl_controller){?>
			      <input type="hidden" size="32" readonly="readonly" maxlength="64" id="tpl_controller" name="tpl_controller" value="<?php echo $tpl_controller;?>">
				  <input type="text" size="32" readonly="readonly" maxlength="64" id="text_tpl_controller" name="text_tpl_controller" value="<?php echo $text_tpl_controller;?>">
			    <?php } else { ?>
			      <input type="hidden" size="32" maxlength="64" id="tpl_controller" name="tpl_controller" value="<?php echo $tpl_controller;?>">
				  <input type="text" readonly="readonly" size="32" maxlength="64" id="text_tpl_controller" name="text_tpl_controller" value="<?php echo '';?>">
			  <?php }?></td>
			  <?php if(isset($controllers)){?>
			    <td>
			      <select id="controllers" name="controllers" onchange="set_controller();">
				    <option value=""><?php echo $text_select_controller;?></option>
					<?php  foreach($controllers as $controller){?>
					  <?php if($controller['controller'] == $tpl_controller){?>
					    <option value="<?php echo $controller['controller'];?>" selected><?php echo $controller['text_controller'];?></option>
					  <?php } else {?>
					    <option value="<?php echo $controller['controller'];?>"><?php echo $controller['text_controller'];?></option>
					  <?php }?>
					<?php }?>
				  </select>
				</td>
			  <?php }?>
			</tr>
		    <tr>
			  <td class="set"><?php echo $entry_columns;?></td>
			  <td>
			    <select id="tpl_columns" name="tpl_columns" onchange="set_columns(); $('#tpl_colors').load('index.php?controller=template_manager&action=getColors&columns='+this.value);">
				  <?php foreach($columns as $pagecolumn){?>
				    <?php if ($pagecolumn == $tpl_columns){?>
					  <option value="<?php echo $pagecolumn == 'default' ? 0 : $pagecolumn;?>" selected><?php echo $pagecolumn;?></option>
					<?php } else {?>
					  <option value="<?php echo $pagecolumn == 'default' ? 0 : $pagecolumn;?>"><?php echo $pagecolumn;?></option>
					<?php }?>
				  <?php }?>
				</select>
			  </td>
			  <input type="hidden" id="default_columns" name="default_columns" value="<?php echo $default_columns;?>">
			</tr>
			<tr>
              <td class="set"><?php echo $entry_color; ?></td>
              <td id="tpl_colors"><select name="tpl_color">
                  <?php foreach ($catalog_colors as $catalog_color) { ?>
                  <?php if ($tpl_color == $catalog_color['colorcss']) { ?>
                  <option value="<?php echo $catalog_color['colorcss']; ?>" selected><?php echo $catalog_color['colorcss']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $catalog_color['colorcss']; ?>"><?php echo $catalog_color['colorcss']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
			  </td>
			</tr>
			<tr>
              <td class="set"><?php echo $entry_status; ?></td>
              <td><select name="tpl_status">
                  <?php if ($tpl_status == '1') { ?>
                  <option value="1" selected><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
			</tr>
		  </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_manager;?>
		  </td></tr></table>
		</div>
	  </div>
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="headertable">
		
		    <?php $i = 0;?>
		    <?php foreach($header as $header_info){?>
			<tr id="header_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="header" name="header[<?php echo $i; ?>][module_code]">
			    <?php foreach($header_modules as $header_module){?>
				  <?php if($header_module == $header_info['module_code']){?>
				    <option value="<?php echo $header_module;?>" selected><?php echo $header_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $header_module;?>"><?php echo $header_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="header<?php echo $i; ?>sort_order" type="text" size="2" name="header[<?php echo $i; ?>][sort_order]" value="<?php echo $header_info['sort_order'];?>"></td>
			  <input type="hidden" name="header[<?php echo $i; ?>][location_id]" value="<?php echo $header_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('header_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('headertable',<?php echo $header_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_header;?>
		  </td></tr></table>
		  
		</div>
	  </div>
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="extratable">
		
		    <?php $i = 0;?>
		    <?php foreach($extra as $extra_info){?>
			<tr id="extra_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="extra" name="extra[<?php echo $i; ?>][module_code]">
			    <?php foreach($extra_modules as $extra_module){?>
				  <?php if($extra_module == $extra_info['module_code']){?>
				    <option value="<?php echo $extra_module;?>" selected><?php echo $extra_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $extra_module;?>"><?php echo $extra_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="extra<?php echo $i; ?>sort_order" type="text" size="2" name="extra[<?php echo $i; ?>][sort_order]" value="<?php echo $extra_info['sort_order'];?>"></td>
			  <input type="hidden" name="extra[<?php echo $i; ?>][location_id]" value="<?php echo $extra_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('extra_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('extratable',<?php echo $extra_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_extra;?>
		  </td></tr></table>
		</div>
	  </div>
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="columntable">

		    <?php $i = 0;?>
		    <?php foreach($column as $column_info){?>
			<tr id="column_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="column" name="column[<?php echo $i; ?>][module_code]">
			    <?php foreach($column_modules as $column_module){?>
				  <?php if($column_module == $column_info['module_code']){?>
				    <option value="<?php echo $column_module;?>" selected><?php echo $column_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $column_module;?>"><?php echo $column_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="column<?php echo $i; ?>sort_order" type="text" size="2" name="column[<?php echo $i; ?>][sort_order]" value="<?php echo $column_info['sort_order'];?>"></td>
			  <input type="hidden" name="column[<?php echo $i; ?>][location_id]" value="<?php echo $column_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('column_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table id="columntablebutton">
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('columntable',<?php echo $column_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_column;?>
		  </td></tr></table>
		</div>
	  </div>
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="contenttable">
		
		    <?php $i = 0;?>
		    <?php foreach($content as $content_info){?>
			<tr id="content_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="content" name="content[<?php echo $i; ?>][module_code]">
			    <?php foreach($content_modules as $content_module){?>
				  <?php if($content_module == $content_info['module_code']){?>
				    <option value="<?php echo $content_module;?>" selected><?php echo $content_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $content_module;?>"><?php echo $content_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="content<?php echo $i; ?>sort_order" type="text" size="2" name="content[<?php echo $i; ?>][sort_order]" value="<?php echo $content_info['sort_order'];?>"></td>
			  <input type="hidden" name="content[<?php echo $i; ?>][location_id]" value="<?php echo $content_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('content_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('contenttable',<?php echo $content_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_content;?>
		  </td></tr></table>
		</div>
	  </div>
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="columnrighttable">
		
		    <?php $i = 0;?>
		    <?php foreach($columnright as $columnright_info){?>
			<tr id="columnright_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="columnright" name="columnright[<?php echo $i; ?>][module_code]">
			    <?php foreach($columnright_modules as $columnright_module){?>
				  <?php if($columnright_module == $columnright_info['module_code']){?>
				    <option value="<?php echo $columnright_module;?>" selected><?php echo $columnright_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $columnright_module;?>"><?php echo $columnright_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="columnright<?php echo $i; ?>sort_order" type="text" size="2" name="columnright[<?php echo $i; ?>][sort_order]" value="<?php echo $columnright_info['sort_order'];?>"></td>
			  <input type="hidden" name="columnright[<?php echo $i; ?>][location_id]" value="<?php echo $columnright_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('columnright_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table id="columnrightbutton">
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('columnrighttable',<?php echo $columnright_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_columnright;?>
		  </td></tr></table>
		</div>
	  </div>
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="footertable">
		
		    <?php $i = 0;?>
		    <?php foreach($footer as $footer_info){?>
			<tr id="footer_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="footer" name="footer[<?php echo $i; ?>][module_code]">
			    <?php foreach($footer_modules as $footer_module){?>
				  <?php if($footer_module == $footer_info['module_code']){?>
				    <option value="<?php echo $footer_module;?>" selected><?php echo $footer_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $footer_module;?>"><?php echo $footer_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="footer<?php echo $i; ?>sort_order" type="text" size="2" name="footer[<?php echo $i; ?>][sort_order]" value="<?php echo $footer_info['sort_order'];?>"></td>
			  <input type="hidden" name="footer[<?php echo $i; ?>][location_id]" value="<?php echo $footer_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('footer_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('footertable',<?php echo $footer_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_footer;?>
		  </td></tr></table>
		</div>
	  </div> 
      <div class="page">
        <div class="pad">
		  <div></div>
		  <table id="pagebottomtable">
		
		    <?php $i = 0;?>
		    <?php foreach($pagebottom as $pagebottom_info){?>
			<tr id="pagebottom_<?php echo $i; ?>">
			  <td class="set"><?php echo $entry_module; ?></td>
			  <td><select class="pagebottom" name="pagebottom[<?php echo $i; ?>][module_code]">
			    <?php foreach($pagebottom_modules as $pagebottom_module){?>
				  <?php if($pagebottom_module == $pagebottom_info['module_code']){?>
				    <option value="<?php echo $pagebottom_module;?>" selected><?php echo $pagebottom_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $pagebottom_module;?>"><?php echo $pagebottom_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td class="set"><?php echo $entry_sortorder;?></td>
			  <td><input class="validate_int" id="pagebottom<?php echo $i; ?>sort_order" type="text" size="2" name="pagebottom[<?php echo $i; ?>][sort_order]" value="<?php echo $pagebottom_info['sort_order'];?>"></td>
			  <input type="hidden" name="pagebottom[<?php echo $i; ?>][location_id]" value="<?php echo $pagebottom_info['location_id']?>">
			  <td><input type="button" class="button" value="<?php echo $button_remove; ?>" onclick="removeModule('pagebottom_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" class="button" value="<?php echo $button_add; ?>" onclick="addModule('pagebottomtable',<?php echo $pagebottom_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td class="expl">
		    <?php echo $text_tpl_pagebottom;?>
		  </td></tr></table>
		</div>
	  </div>
	</div>
  </div>
  <input type="hidden" name="<?php echo $cdx;?>" value="<?php echo $validation;?>">
</form> 

  <script type="text/javascript"><!--
  tabview_initialize('tab');
  //--></script>
  <script type="text/javascript"><!--
$(document).ready(function(){
	set_columns();
}); 
function set_controller(){
	var Controller = $('#controllers').val();
	var ControllerText = $('#controllers option:selected').text();
	$('#tpl_controller').val(Controller);
	$('#text_tpl_controller').val(ControllerText);
	$(".heading em").text(ControllerText);
} 
function set_columns(){
	var Columns;
	if($('#tpl_columns').val() > 0) {
		Columns = $('#tpl_columns').val();
	} else {
		Columns = $('#default_columns').val();
	}
	$('#columntable').show();
	$('#columnrighttable').show();
	$('#columntablebutton').show();
	$('#columnrightbutton').show();
	if(Columns == "1"){
		$('#columnrighttable').empty();
		$('#columntable').empty();
		$('#columnrightbutton').hide();
		$('#columntablebutton').hide();
	} else if(Columns == "1.2"){
		$('#columnrighttable').empty();
		$('#columnrightbutton').hide();
	} else if(Columns == "2.1"){
		$('#columntable').empty();
		$('#columntablebutton').hide();
	}
}
function addModule(Location,Location_id) {
	var Columns;
	var Last = $('#' + Location + ' tr:last');
	var nextId = Last.size() == 0 ? 1 : + Last.attr('id').split("_").pop() + 1;
	if($('#tpl_columns').val() > 0) {
		Columns = $('#tpl_columns').val();
	} else {
		Columns = $('#default_columns').val();
	}
	$.ajax({
   		type:    'GET',
   		url:     'index.php?controller=template_manager&action=module&module_id='+ nextId +'&location_id='+Location_id+'&tpl_controller='+$('#tpl_controller').val()+'&tpl_column='+Columns,
		async:   false,
   		success: function(data) {
     		$('#'+Location).append(data);
   		}
 	});
}
  
function removeModule(row) {
  	$('#'+row).remove();
}
  //--></script>
  <script type="text/javascript"><!--
    $('input[name="text_tpl_controller"]').change(function () {
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
		url:     'index.php?controller=template_manager&action=help',
		async:   false,
		success: function(data) {
			$('.description').toggle('slow');
		}
	});
  }
  //--></script>
  <script type="text/javascript"><!--
	function copyModules(module) {
		var ids = [];
		var modules = document.querySelectorAll("[id^='" + module + "_']");
		var modulesLenght = modules.length;
		for (j =0; j < modulesLenght; j++) {
			ids[j] = modules[j].getAttribute("id").split("_").pop();
		}
		var html ='';
		for (i =0; i < modulesLenght; i++) {
			if (document.forms['form'].elements[module+'['+ids[i]+'][module_code]'] !=undefined){
				html +='<input type="hidden" name="' + module + '[' + [i] + '][module_code]" value="' + document.forms['form'].elements[module+'['+ids[i]+'][module_code]'].value + '">';
				html +='<input type="hidden" name="' + module + '[' + [i] + '][sort_order]" value="' + document.forms['form'].elements[module+'['+ids[i]+'][sort_order]'].value + '">';
				html +='<input type="hidden" name="' + module + '[' + [i] + '][location_id]" value="' + document.forms['form'].elements[module+'['+ids[i]+'][location_id]'].value + '">';
			}
		}
		document.forms['update_form'].innerHTML += html;
	}
	function getValues() {
		document.forms['update_form'].tpl_controller.value=document.forms['form'].tpl_controller.value;
		document.forms['update_form'].text_tpl_controller.value=document.forms['form'].text_tpl_controller.value;
		document.forms['update_form'].tpl_columns.value=document.forms['form'].tpl_columns.value;
		document.forms['update_form'].tpl_color.value=document.forms['form'].tpl_color.value;
		document.forms['update_form'].tpl_status.value=document.forms['form'].tpl_status.value;

		copyModules('header');
		copyModules('extra');
		copyModules('column');
		copyModules('content');
		copyModules('columnright');
		copyModules('footer');
		copyModules('pagebottom');
		document.getElementById('update_form').submit();
	}
  //--></script>
  <script type="text/javascript"><!--
	$('.tabs a').on('click', function() {
	var activeTab = $(this).index()+1;
	var id = $('#tpl_manager_id').val();
	var data_json = {'activeTab':activeTab, 'id':id};
	$.ajax({
		type:	'POST',
		url:	'index.php?controller=template_manager&action=tab',
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
