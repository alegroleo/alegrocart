<?php if (count($manufacturers_data) > 1){?>
	<table>
		<tr><td><?php echo $text_manufacturer;?></td></tr>
		<tr><td>
		<select id="manufacturer_option" class="man_select" name="manufacturer" onchange="$('#model').load('index.php?controller=<?php echo $this_controller;?>&amp;action=model&amp;manufacturer='+this.value);">
			<option value="0_<?php echo $customer_id;?>" <?php if($manufacturer_id == "0_".$customer_id){?> selected <?php }?>><?php echo $text_manufacturer_all;?></option>
			<?php foreach($manufacturers_data as $manufacturer_data){?>
				<option value="<?php echo $manufacturer_data['manufacturer'];?>"<?php if($manufacturer_data['manufacturer'] == $manufacturer_id){?> selected <?php }?>><?php echo $manufacturer_data['name'];?></option>
			<?php }?>
		</select>
		</td></tr>
	</table>
	<div class="divider"></div>
<?php }?>
