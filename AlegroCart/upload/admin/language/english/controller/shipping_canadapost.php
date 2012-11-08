<?php
// Heading
$_['heading_title']       = 'Canada Post';
$_['heading_shipping']    = 'Shipping:';
$_['heading_description'] = 'You can edit Canada Post shipping here.';

// Text
$_['text_message']        = 'Success: You have updated shipping Canada post!';

// Entry
$_['entry_status']        	= 'Status:';
$_['entry_tax']           	= 'Tax Class:';
$_['entry_sort_order']   	= 'Sort Order:';
$_['entry_postcode']	  	= 'Shipping Postal Code:';
$_['entry_geo_zone']      	= 'Geo Zone:';
$_['entry_canadapost_ip'] 	= 'Canada Post Server:';
$_['entry_canadapost_port']	= 'Canada Post Port:';
$_['entry_merchant_id'] 	= 'Merchant ID:';
$_['entry_language']		= 'Response Language:';
$_['entry_turnaround']		= 'Turn Around Time:';
$_['entry_readytoship']     = 'Ready to Ship:';
$_['entry_package']			= 'Package Order:';

// Explanation
$_['explanation_postcode']		= 'Postal code where your shipments original from. Must be valid Canadian Post Code.<br>This is optional. If left blank, the default postal code in your merchant profile is used.';
$_['explanation_geo_zone']      = 'Geo Zone where this shipping method is usable.<br> Customers located in the selected Geo Zone can choose this shipping method.';
$_['explanation_tax']           = 'Add tax to shipping quote based on tax class selected.';
$_['explanation_canadapost_ip'] = 'Canada Post Server: The default is <b>* sellonline.canadapost.ca *</b>';
$_['explanation_canadapost_port'] = 'Canada Post Port: The default is <b>* 30000 *</b>';
$_['explanation_merchant_id'] = 'Canada Post Merchant ID is required. <a href="http://www.canadapost.ca/cpo/mc/business/solutions/sellonline.jsf">Sell Online Account Sign up</a><br>You can use <b>* CPC_DEMO_XML *</b> for testing purposes.';
$_['explanation_sort_order'] = 'Order this method appears in checkout shipping.';
$_['explanation_language']	 = 'This is the language used in the response from Canada Post.<br>The default is English if not specified.';
$_['explanation_turnaround']  = 'The time in hours from when you receive the order until it is shipped.';
$_['explanation_readytoship'] = 'If this is set to yes, Canada post will not package items.<br>The items will be assumed to be in shipping package.';
$_['explanation_package']	 = 'If set to yes, the cart will package items and submit packages to Canada post.<br>Ready To Ship must also be set to YES.';

// Tabs
$_['tab_required']          = 'Required Data';
$_['tab_optional']          = 'Optional Data';
// Error
$_['error_permission']    = 'Warning: You do not have permission to modify shipping canada post';
?>
