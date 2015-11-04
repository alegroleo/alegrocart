 <div class="headingbody"><h1><?php echo $heading_title; ?></h1></div>
 <div class="contentBody">
  <div class="<?php echo $this_controller; ?>_filter">
   <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data">
	<div class="top_entry">
	 <div class="left">
	  <?php echo $text_sort_by;?>
	  <div class="data">
	   <?php foreach($sort_filter as $filter){ ?>
	    <input type="radio" <?php if($default_filter == $filter){ ?>CHECKED <?php }?>name="sort_filter" value="<?php echo $filter;?>"><?php echo $filter;?>
	    <br>
	   <?php }?>
	  </div>
	 </div>
	 <div class="floatleft">
	  <?php echo $text_order;?>
	  <div class="data">
	   <?php foreach($sort_order as $order){ ?>
	    <input type="radio" <?php if($default_order == $order){ ?>CHECKED <?php }?>name="sort_order" value="<?php echo $order;?>"><?php echo $order;?>
	    <br>
	   <?php }?>
	  </div>
	 </div>
	<?php if($options_manufacturer){ ?>
	   <div class="man_filter">
	  <?php switch($this_controller){
	    case 'search':
	      include $shared_path . 'search_manufacturer.tpl';
	      break;
	    case 'category':
	      include $shared_path . 'category_manufacturer.tpl';
	      break;
	  } ?>
	   </div>
	<?php } ?>
	 <?php if($options_model){?>
	   <div class="right">
	    <table id="model">
	     <?php if (count($models_data ) > 1){ ?>
	         <tr><td><?php echo $text_model; ?></td></tr>
		     <tr><td>
		       <select name="model">
		         <option value="all" <?php if($model == "all"){?>selected <?php }?>><?php echo $text_all;?></option>
		         <?php foreach($models_data as $model_data){?>
		           <option value="<?php echo $model_data['model_value'];?>" <?php if($model_data['model'] == $model){?>selected <?php }?>><?php echo $model_data['model'];?></option>
		         <?php }?>
		       </select>
		     </td></tr>
	     <?php }?>
	    </table>
	   </div>
	 <?php }?>
	</div>
	<div class="divider"></div>
	<?php if($display_lock == False){ ?>
	<table><tr>
	 <td><div class="lowerleft">
	  <?php echo $text_page_rows;?>
	  <div class="data">
	   <input type="text" size="4" name="page_rows" id="page_rows" value="<?php echo $default_page_rows;?>" class="validate_int">
	  </div>
	 </div></td>
	 <td><div class="lowerleft">
	  <?php echo $text_max_rows;?>
	  <div class="data">
	   <input type="text" size="4" name="max_rows" id="max_rows" value="<?php echo $default_max_rows;?>" class="validate_int">
	  </div>
	 </div></td>	
	  <td><div class="lowerright">
	   <?php echo $text_columns;?>
	   <div class="data">
	    <select name="columns">
		 <?php foreach($number_columns as $number_column){
			if($columns == $number_column){
			  echo '<option value="' . $number_column . '" selected>' . $number_column . '</option>';
			} else {
			  echo '<option value="' . $number_column . '">' . $number_column . '</option>';
			}
		 }?>
		</select>
	   </div>
	  </div></td>
	</tr></table>
	<div class="divider"></div>
	<?php } ?>
	<?php if($this_controller == 'search'){ ?>
		<input type="hidden" name="description" value="<?php echo $description;?>"<?php if($description == "on"){?> CHECKED<?php }?>>
	<?php }?>
	<div class="entry">
	  <input type="submit" value="<?php echo $entry_submit;?>">
	 </div>	
   </form>
   <div class="clearfix"></div>
  </div>
 </div>
 <div class="contentBodyBottom"></div>
