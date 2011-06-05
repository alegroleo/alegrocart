<script type="text/javascript" src="javascript/ajax/jquery.js"></script>

<?php if(!$log_print){?>
<div class="heading"><?php echo $heading_title; ?></div>
<div class="description"><?php echo $heading_description; ?></div>
<hr>
<div style="min-height: 400px;">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<table>
  <tr>
    <td style="width:185px;" class="set"><?php echo $entry_dir; ?></td>
    <td><select name="directory" onchange="$('#file_name').load('index.php?controller=report_logs&action=get_files&directory='+this.value);">
		<option value=""><?php echo $text_select_dir;?></option>
		<?php foreach($log_directories as $directory){?>
			<option value="<?php echo $directory['directory'];?>"<?php if($directory['directory'] == $log_directory) {echo ' selected';}?>><?php echo $directory['directory'];?></option>
		<?php }?>
	</select></td>
  </tr>
</table>
<table id="file_name" style="height: 24px;">
  <?php if(isset($log_files)){ echo $log_files;}?>
</table>
<table id="encrypt">
  <tr>
    <td class="set" style="width: 185px;"><?php echo $entry_decrytion; ?></td>
	<td><?php if ($decrytion) { ?>
       <input type="radio" name="decrytion" value="1" id="decyes" checked>
       <label for="decyes"><?php echo $text_yes; ?></label>
       <input type="radio" name="decrytion" value="0" id="decno">
       <label for="decno"><?php echo $text_no; ?></label>
                <?php } else { ?>
       <input type="radio" name="decrytion" value="1" id="decyes">
       <label for="decyes"><?php echo $text_yes; ?></label>
       <input type="radio" name="decrytion" value="0" id="decno" checked>
       <label for="decno"><?php echo $text_no; ?></label>
                <?php } ?></td>
	<td class="expl">
	  <?php echo $text_dycrypt_exp; ?> 
	</td>			
  </tr>
  <tr><td><input type="submit" class="submit" value="<?php echo $button_submit; ?>"></td>
  <?php if($log_file && !$log_print){?>
    <script type="text/javascript"><!-- 
	  function print_form(){
		$('#log_print').val('1');
		$('#form').submit();
	  }
	//--></script>
    <td></td><td>
	   <input type="hidden" name="log_print" id="log_print" value="">
	   <img id="print_log" src="template/<?php echo $this->directory?>/image/print32.png" alt="print" title="print" onclick="print_form();">
    </td>
  <?php }?>
  </tr>
</table>
</form>
<?php }?>
<?php if($log_file){?>
  <?php if($log_print){?>
	<script type="text/javascript"><!--
      $(document).ready(function() {
	    $('#printMe').click(function() {
		  window.print();
		  return false;
	    });
	  });
  //--></script>
    <h1><?php echo $log_directory;?></h1>
	<div style="padding-left: 50px; margin-bottom: 10px;"><a href="<?php echo $continue;?>"><img class="back_button" src="template/<?php echo $this->directory?>/image/button_back.png" alt="back" title="back"></a>
	<img class="print_button" id="printMe" src="template/<?php echo $this->directory?>/image/print32.png" alt="print" title="print">
  
	</div>
	
  <?php }?>
<table style="border: 1px solid #000000; width: 100%; padding: 2px;">
  <tr><td>
  <?php echo $log_file;?>
  </td></tr>
</table>
<?php }?>
<?php if(!$log_print){?>
</div>
<?php }?>