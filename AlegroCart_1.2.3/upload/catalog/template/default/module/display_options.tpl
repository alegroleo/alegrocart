<?php
 $head_def->setcss($this->style . "/css/display_options.css");
  $shared_path = 'catalog/template/' . $this->directory . '/shared/';
?>
<div class="headingcolumn"><?php echo $heading_title; ?></div>
<div class="display_options">
  <form action="<?php echo $action;?>" method="post" enctype="multipart/form-data"> 
    <table>
      <tr>
	   <td rowspan="<?php echo count($sort_filter);?>" style="width: 60px;"><?php echo $text_sort_by;?></td>
	  <?php foreach($sort_filter as $key => $filter){ ?>
		<?php if($key > 0){?><tr><?php } ?><td>
		  <input type="radio" <?php if($default_filter == $filter){ ?>CHECKED <?php }?>name="sort_filter" value="<?php echo $filter;?>"> <?php echo $filter;?>
		  </td></tr>
	  <?php }?>
	</table>
	<div class="divider"></div>
	<table>
	  <tr >
	    <td rowspan="<?php echo count($sort_order);?>" style="width: 60px;"><?php echo $text_order;?></td>
	  <?php foreach($sort_order as $key => $order){ ?>
		<?php if($key > 0){?><tr><?php } ?><td>
		  <input type="radio" <?php if($default_order == $order){ ?>CHECKED <?php }?>name="sort_order" value="<?php echo $order;?>"> <?php echo $order;?>
		  </td></tr>
	  <?php }?>
	</table>
	<div class="divider"></div>
	<?php switch($this_controller){
	 case 'search':
	  include $shared_path . 'search_manufacturer.tpl';
	  break;
	 case 'category':
	  include $shared_path . 'category_manufacturer.tpl';
	  break;
	} ?>
	<table id="model">
	  <?php if (count($models_data) > 1){?>
	    <tr><td><?php echo $text_model;?></td></tr>
	    <tr><td style="width: 190px;">
	      <select style="width: 180px;" name="model">
		    <option value="all" <?php if($model == "all"){?>selected <?php }?>><?php echo $text_all;?></option>
			<?php foreach($models_data as $model_data){?>
			  <option value="<?php echo $model_data['model_value'];?>" <?php if($model_data['model'] == $model){?>selected <?php }?>><?php echo $model_data['model'];?></option>
			<?php }?>
		  </select>
		  <div class="divider"></div>
	    </td></tr>
	  <?php }?>
	</table>
	<?php if($display_lock == False){ ?>
	<table>
	  <tr>
	    <td><?php echo $text_page_rows;?></td>
		<td><input type="text" size="4" name="page_rows" value="<?php echo $default_page_rows;?>"></td>
	  </tr>
	  <tr>
	    <td><?php echo $text_max_rows;?></td>
		<td><input type="text" size="4" name="max_rows" value="<?php echo $default_max_rows;?>"></td>
	  </tr>
	  <tr>
	    <td><?php echo $text_columns;?></td>
		<td><select name="columns">
		  <?php foreach($column_data as $column){?>
		    <?php if($column == $default_columns) {?>
			  <option value="<?php echo $column;?>" selected><?php echo $column;?></option>
			<?php } else {?>
			  <option value="<?php echo $column;?>"><?php echo $column;?></option>
			<?php }?>
		  <?php }?>
		  </select>
		</td>
	  </tr>
	</table>
	<?php }?>
	<div class="divider"></div>
	<?php if($this_controller == 'search'){ ?>
     <input type="hidden" name="description" value="<?php echo $description;?>"<?php if($description == "on"){?> CHECKED<?php }?>>
	<?php }?>
	<table>
	  <tr><td style="width: 190px; text-align: center;">
	    <input class="submit" type="submit" value="<?php echo $entry_submit;?>">
	  </td></tr>
    </table>
  </form>
</div>
<div class="display_optionsBottom"></div>