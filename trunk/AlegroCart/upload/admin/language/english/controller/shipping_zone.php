<?php
// Heading
$_['heading_title']       = 'Zone Shipping';
$_['heading_description'] = 'You can edit zone shipping rates here.';

// Text
$_['text_message']        = 'Success: You have updated zone shipping rates!';
$_['text_select_geozone'] = 'Select GeoZone';
$_['text_instruction']= 'Cost format is <b>WEIGHT COLON AMOUNT</b> separated by a <b>COMMA.</b><br>Example: <b>5:5.00, 10: 8.00</b><br>0 to 5.0 lbs. cost is $5.00<br>5.01 to 10 lbs. cost is $8.00<br>Over 10.01 lbs. This order is not eligible for shipping and weight error for this quote will be displayed on shipping page.<br><br>If you enter an amount in <b>Free Freight</b>, freight cost for this method will be 0.00 if the order total exceeds this amount.<br><br>The <b>Message</b> is an optional message you can use to describe this quote or explain limitations on the shipping page.';
$_['text_zone_info']      = 'Information';

// Entry
$_['entry_module_status'] = 'Module Status:';
$_['entry_status']        = 'Status';
$_['entry_geo_zone']      = 'Geo Zone';
$_['entry_cost']          = 'Cost';
$_['entry_tax']           = 'Tax Class:';
$_['entry_sort_order']    = 'Sort Order:';
$_['entry_free_amount']   = 'Free Freight';
$_['entry_message']  	  = 'Message';

//Buttons
$_['button_add']		  = 'Add GeoZone';
$_['button_remove']		  = 'Delete GeoZone';

//Tabs
$_['tab_module']		  = 'Module';
$_['tab_data']			  = 'Data';

// Error
$_['error_permission']    = 'Warning: You do not have permission to modify shipping zone';
?>