<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php if (@$update) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="document.getElementById('form').submit();"><img src="template/<?php echo $this->directory?>/image/update_enabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <?php } ?>
<?php if (@$delete) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $delete; ?>'"><img src="template/<?php echo $this->directory?>/image/delete_enabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
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
<script type="text/javascript" src="javascript/fckeditor/fckeditor.js"></script>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="tab" id="tab">
	<div class="tabs"><a><?php echo $tab_controller; ?></a><a><?php echo $tab_header; ?></a><a><?php echo $tab_extra; ?></a><a><?php echo $tab_left_column; ?></a><a><?php echo $tab_content; ?></a><a><?php echo $tab_right_column; ?></a><a><?php echo $tab_footer; ?></a><a><?php echo $tab_page_bottom; ?></a></div>
    <div class="pages">
      <div class="page">
        <div class="pad">
		  <table>
		    <tr>
			  <td><?php echo $entry_controller;?></td>
			  <td><?php if($tpl_controller){?>
			      <input type="text" size="32" readonly="readonly" maxlength="64" id="tpl_controller" name="tpl_controller" value="<?php echo $tpl_controller;?>">
			    <?php } else { ?>
			      <input type="text" size="32" maxlength="64" id="tpl_controller" name="tpl_controller" value="<?php echo $tpl_controller;?>">
			  <?php }?></td>
			  <?php if(isset($controllers)){?>
			    <td>
			      <select id="controllers" name="controllers" onchange="$('#tpl_controller').val(this.value)">
				    <option value="">Select a Controller</option>
					<?php  foreach($controllers as $controller){?>
					  <?php if($controller['controller'] == $tpl_controller){?>
					    <option value="<?php echo $controller['controller'];?>" selected><?php echo $controller['controller'];?></option>
					  <?php } else {?>
					    <option value="<?php echo $controller['controller'];?>"><?php echo $controller['controller'];?></option>
					  <?php }?>
					<?php }?>
				  </select>
				</td>
			  <?php }?>
			</tr>
		    <tr>
			  <td><?php echo $entry_columns;?></td>
			  <td>
			    <select id="tpl_columns" name="tpl_columns" onchange="set_column_right(); $('#tpl_colors').load('index.php?controller=template_manager&action=getColors&columns='+this.value);">
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
              <td><?php echo $entry_color; ?></td>
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
              <td><?php echo $entry_status; ?></td>
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
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="header[<?php echo $i; ?>][module_code]">
			    <?php foreach($header_modules as $header_module){?>
				  <?php if($header_module == $header_info['module_code']){?>
				    <option value="<?php echo $header_module;?>" selected><?php echo $header_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $header_module;?>"><?php echo $header_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="header[<?php echo $i; ?>][sort_order]" value="<?php echo $header_info['sort_order'];?>"></td>
			  <input type="hidden" name="header[<?php echo $i; ?>][location_id]" value="<?php echo $header_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('header_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('headertable',<?php echo $header_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="extra[<?php echo $i; ?>][module_code]">
			    <?php foreach($extra_modules as $extra_module){?>
				  <?php if($extra_module == $extra_info['module_code']){?>
				    <option value="<?php echo $extra_module;?>" selected><?php echo $extra_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $extra_module;?>"><?php echo $extra_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="extra[<?php echo $i; ?>][sort_order]" value="<?php echo $extra_info['sort_order'];?>"></td>
			  <input type="hidden" name="extra[<?php echo $i; ?>][location_id]" value="<?php echo $extra_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('extra_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('extratable',<?php echo $extra_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="column[<?php echo $i; ?>][module_code]">
			    <?php foreach($column_modules as $column_module){?>
				  <?php if($column_module == $column_info['module_code']){?>
				    <option value="<?php echo $column_module;?>" selected><?php echo $column_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $column_module;?>"><?php echo $column_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="column[<?php echo $i; ?>][sort_order]" value="<?php echo $column_info['sort_order'];?>"></td>
			  <input type="hidden" name="column[<?php echo $i; ?>][location_id]" value="<?php echo $column_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('column_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('columntable',<?php echo $column_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="content[<?php echo $i; ?>][module_code]">
			    <?php foreach($content_modules as $content_module){?>
				  <?php if($content_module == $content_info['module_code']){?>
				    <option value="<?php echo $content_module;?>" selected><?php echo $content_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $content_module;?>"><?php echo $content_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="content[<?php echo $i; ?>][sort_order]" value="<?php echo $content_info['sort_order'];?>"></td>
			  <input type="hidden" name="content[<?php echo $i; ?>][location_id]" value="<?php echo $content_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('content_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('contenttable',<?php echo $content_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="columnright[<?php echo $i; ?>][module_code]">
			    <?php foreach($columnright_modules as $columnright_module){?>
				  <?php if($columnright_module == $columnright_info['module_code']){?>
				    <option value="<?php echo $columnright_module;?>" selected><?php echo $columnright_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $columnright_module;?>"><?php echo $columnright_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="columnright[<?php echo $i; ?>][sort_order]" value="<?php echo $columnright_info['sort_order'];?>"></td>
			  <input type="hidden" name="columnright[<?php echo $i; ?>][location_id]" value="<?php echo $columnright_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('columnright_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table id="columnrightbutton">
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('columnrighttable',<?php echo $columnright_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="footer[<?php echo $i; ?>][module_code]">
			    <?php foreach($footer_modules as $footer_module){?>
				  <?php if($footer_module == $footer_info['module_code']){?>
				    <option value="<?php echo $footer_module;?>" selected><?php echo $footer_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $footer_module;?>"><?php echo $footer_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="footer[<?php echo $i; ?>][sort_order]" value="<?php echo $footer_info['sort_order'];?>"></td>
			  <input type="hidden" name="footer[<?php echo $i; ?>][location_id]" value="<?php echo $footer_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('footer_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('footertable',<?php echo $footer_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
			  <td><?php echo $entry_module; ?></td>
			  <td><select name="pagebottom[<?php echo $i; ?>][module_code]">
			    <?php foreach($pagebottom_modules as $pagebottom_module){?>
				  <?php if($pagebottom_module == $pagebottom_info['module_code']){?>
				    <option value="<?php echo $pagebottom_module;?>" selected><?php echo $pagebottom_module;?></option>
				  <?php } else {?>
				    <option value="<?php echo $pagebottom_module;?>"><?php echo $pagebottom_module;?></option>
				  <?php }?>
				<?php }?>
		      </select></td>
			  <td><?php echo $entry_sortorder;?></td>
			  <td><input type="text" name="pagebottom[<?php echo $i; ?>][sort_order]" value="<?php echo $pagebottom_info['sort_order'];?>"></td>
			  <input type="hidden" name="pagebottom[<?php echo $i; ?>][location_id]" value="<?php echo $pagebottom_info['location_id']?>">
			  <td><input type="button" value="<?php echo $button_remove; ?>" onclick="removeModule('pagebottom_<?php echo $i;?>');"></td> 
		    </tr>
		    <?php $i++; ?>
		    <?php }?>
		  </table>
          <table>
            <tr>
              <td colspan="5"><input type="button" value="<?php echo $button_add; ?>" onclick="addModule('pagebottomtable',<?php echo $pagebottom_id;?>);"></td>
            </tr>
          </table>
		  <table><tr><td>
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
	set_column_right();
});  
function set_column_right(){
	var Columns;
	if($('#tpl_columns').val() > 0) {
		Columns = $('#tpl_columns').val();
	} else {
		Columns = $('#default_columns').val();
	}
	if(Columns == 2){
		$('#columnrighttable').empty();
		
		$('#columnrightbutton').hide();
	} else {
		$('#columnrighttable').show();
		$('#columnrightbutton').show();
	}
}
function addModule(Location,Location_id) {
	var Columns;
	if($('#tpl_columns').val() > 0) {
		Columns = $('#tpl_columns').val();
	} else {
		Columns = $('#default_columns').val();
	}
	$.ajax({
   		type:    'GET',
   		url:     'index.php?controller=template_manager&action=module&module_id='+$('#'+Location + ' tr').size()+'&location_id='+Location_id+'&tpl_controller='+$('#tpl_controller').val()+'&tpl_column='+Columns,
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