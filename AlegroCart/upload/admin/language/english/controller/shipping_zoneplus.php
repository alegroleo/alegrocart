<?php
// Heading
$_['heading_title']       = 'Zone Plus';
$_['heading_shipping']    = 'Shipping:';
$_['heading_description'] = 'You can edit zone plus shipping rates here.';

// Text
$_['text_message']        = 'Success: You have updated zone plus shipping rates!';
$_['text_select_geozone'] = 'Select GeoZone';
$_['text_zone_explantion']= '<b>Base Cost</b> is the starting amount for rate calculation.<br><b>Additional Cost</b> is calculated on weights over <b>Base Weight</b>.<br>If <b>Base Weight</b> is zero(0), <b>Additional Cost</b> calculation is started at 0.<br><b>Additional Cost</b> is amount per <b>Weight Unit</b>.<br><b>Weight Unit</b> is the increment you want <b>Additional Cost</b> to be multiplied by.<br> E.G. Weight unit is 1 kg. Amount is $2.00. Package weight of 10 kg X $2.00 per kg = $20.00 <b>Additional Cost</b>.<br><b>Total Additional Cost</b> is added to <b>Base Cost</b> to get <b>Total Shipping Cost</b>.<br><b>Maximum Weight</b> is the limit set for this quote. If cart weight exceeds this, weight error for this quote will be displayed.<br><br>If you enter an amount in <b>Free Freight</b>, freight cost for this method will be 0.00 if the order total exceeds this amount.<br><br>The <b>Message</b> is an optional message you can use to describe this quote or explain limitations on the shipping page.';
$_['text_zone_info']      = 'Information';

// Entry
$_['entry_module_status'] = 'Module Status:';
$_['entry_tax']           = 'Tax Class:';
$_['entry_sort_order']    = 'Sort Order:';
$_['entry_status']        = 'Status';
$_['entry_geo_zone']      = 'Geo Zone';
$_['entry_base_cost']     = 'Base cost';
$_['entry_base_weight']	  = 'Base Weight';
$_['entry_added_cost']    = 'Additional<br>Cost';
$_['entry_added_weight']  = 'Per Weight<br>unit ';
$_['entry_max_weight']    = 'Maximum<br>Weight';
$_['entry_free_amount']   = 'Free<br>Freight';
$_['entry_message']  	  = 'Message';

//Buttons
$_['button_add']		  = 'Add GeoZone';
$_['button_remove']		  = 'Delete GeoZone';

//Tabs
$_['tab_module']		  = 'Module';
$_['tab_data']			  = 'Data';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify shipping zone plus';
?>
