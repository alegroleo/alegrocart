<div class="task">
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $list; ?>'"><img src="template/<?php echo $this->directory?>/image/list_enabled.png" alt="<?php echo $button_list; ?>" class="png"><?php echo $button_list; ?></div>
  <?php if (@$insert) { ?>
  <div class="enabled" onmouseover="className='hover'" onmouseout="className='enabled'" onclick="location='<?php echo $insert; ?>'"><img src="template/<?php echo $this->directory?>/image/insert_enabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php } else { ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/insert_disabled.png" alt="<?php echo $button_insert; ?>" class="png"><?php echo $button_insert; ?></div>
  <?php } ?>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/update_disabled.png" alt="<?php echo $button_update; ?>" class="png"><?php echo $button_update; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/delete_disabled.png" alt="<?php echo $button_delete; ?>" class="png"><?php echo $button_delete; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/save_disabled.png" alt="<?php echo $button_save; ?>" class="png"><?php echo $button_save; ?></div>
  <div class="disabled"><img src="template/<?php echo $this->directory?>/image/cancel_disabled.png" alt="<?php echo $button_cancel; ?>" class="png"><?php echo $button_cancel; ?></div>
</div>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<?php if ($message) { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<div id="list">
  <table class="a">
    <tr>
      <td class="c"><?php echo $text_results; ?></td>
      <td class="d"><form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <?php echo $entry_page; ?>
          <select name="page" onchange="this.form.submit();">
		<?php foreach ($pages as $p) { ?>
		<option value="<?php echo $p['value']; ?>"<?php if ($p['value'] == $page) { ?>SELECTED<?php } ?>><?php echo $p['text']; ?></option>
		<?php } ?>
          </select>
        </form></td>
	  <?php if(isset($action_refresh)){?>
	    <td class="d">
		  <form action="<?php echo $action_refresh; ?>" method="post" enctype="multipart/form-data">
		  <input class="submit" type="submit" value="<?php echo $button_refresh; ?>">
		  </form>
		</td>
	  <?php }?>
	  <?php if(isset($action_delete)){?>
		<td class="d">
		  <form action="<?php echo $action_delete; ?>" method="post" enctype="multipart/form-data">
		  <input class="submit" type="submit" value="<?php echo $button_enable_delete; ?>">
		  </form>
		</td>
	  <?php }?>
      <td class="e"><form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <?php echo $entry_search; ?>
          <input type="input" name="search" value="<?php echo $search; ?>">
        </form></td>
    </tr>
  </table>
  <table class="list">
    <tr>
      <?php foreach ($cols as $col) { ?>
      <th class="<?php echo $col['align']; ?>"><?php if (!isset($col['sort'])) { ?>
        <?php if (isset($col['name'])) { ?>
        <?php echo $col['name']; ?>
        <?php } ?>
        <?php } else { ?>
        <?php if (isset($col['sort'])) { ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" onclick="this.submit();">
          <?php echo $col['name']; ?>
          <?php if ($sort == $col['sort']) { ?>
          <?php if ($order == 'asc') { ?>
          &nbsp;<img src="template/<?php echo $this->directory?>/image/asc.png" class="png">
          <?php } else { ?>
          &nbsp;<img src="template/<?php echo $this->directory?>/image/desc.png" class="png">
          <?php } ?>
          <?php } ?>
          <input type="hidden" name="sort" value="<?php echo $col['sort']; ?>">
        </form>
        <?php } ?>
        <?php } ?>
      </th>
      <?php } ?>
    </tr>
    <?php $j = 1; ?>
    <?php if (isset($previous)) { ?>
    <tr class="row1" onmouseover="this.className='previous'" onmouseout="this.className='row1'" onclick="self.location.href='<?php echo $previous; ?>'">
      <td><img src="template/<?php echo $this->directory?>/image/previous.png" class="png">&nbsp;<?php echo $text_previous; ?></td>
      <?php for ($i = 1; $i < sizeof($cols); $i++) { ?>
      <td>&nbsp;</td>
      <?php $j = 0; ?>
      <?php } ?>
    </tr>
    <?php } ?>
    <?php foreach ($rows as $row) { ?>
    <?php  
    if ($j != 1) {
    	$j = 1;
    } else {
    	$j = 0;
    }
    
    if ($j == 0) {
    	$class = 'row1';
    } elseif ($j == 1) {
     	$class = 'row2';
    }
    ?>
    <tr class="<?php echo $class; ?>" onmouseover="this.className='highlight'" onmouseout="this.className='<?php echo $class; ?>'">
      <?php foreach ($row['cell'] as $cell) { ?>
      <?php if (isset($cell['value'])) { ?>
      <td class="<?php echo $cell['align']; ?>"><?php echo $cell['value']; ?>
        <?php if (@$cell['default']) { ?>
        <b>(<?php echo $text_default; ?>)</b>
        <?php } ?></td>
      <?php } elseif (isset($cell['image'])) { ?>
      <td class="<?php echo $cell['align']; ?>"><img src="<?php echo $cell['image']; ?>"></td>
      <?php } elseif (isset($cell['path'])) { ?>
      <td width="30" class="<?php echo $cell['align']; ?>"><a href="<?php echo $cell['path']; ?>"><img src="template/<?php echo $this->directory?>/image/<?php echo $cell['icon']; ?>" class="png"></a></td>
      <?php } elseif (isset($cell['icon'])) { ?>
      <td class="<?php echo $cell['align']; ?>"><?php if (isset($cell['href'])) { ?>
        <a href="<?php echo $cell['href']; ?>"><img src="template/<?php echo $this->directory?>/image/<?php echo $cell['icon']; ?>" class="png"></a>
        <?php } else { ?>
        <img src="template/<?php echo $this->directory?>/image/<?php echo $cell['icon']; ?>" class="png">
        <?php } ?>
      </td>
      <?php } elseif (isset($cell['action'])) { ?>
      <td class="<?php echo $cell['align']; ?>"><?php foreach ($cell['action'] as $action) { 
      	//See http://forum.opencart.com/index.php/topic,1179.msg5716.html#msg5716
		if (preg_match('/action=delete/',$action['href'])) { $action['href'].='" onClick="return confirm(\'Are you sure you want to delete?\')'; }
      ?>
        <a href="<?php echo $action['href']; ?>"><img src="template/<?php echo $this->directory?>/image/<?php echo $action['icon']; ?>" alt="<?php echo $action['text']; ?>" title="<?php echo $action['text']; ?>" class="png"></a>
        <?php } ?></td>
      <?php } ?>
      <?php } ?>
    </tr>
    <?php } ?>
  </table>
</div>
