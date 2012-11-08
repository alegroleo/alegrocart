<?php
// Heading
$_['heading_title']        = 'Currencies';
$_['heading_form_title']   = 'Currency:';
$_['heading_description']  = 'You can edit the different currencies here. Conversion rates will reflect a %s surcharge.<br>Checkbox <b>All</b> will include disabled currencies in automatic update';

// Text
$_['text_message']        = 'Success: You have updated currencies!';
$_['text_lock_rate']      = 'This will disable Automatic Update!';
$_['text_default_rate']   = 'Set your default currency to 1.0000'; 

// Buttons
$_['button_rate']         = 'Update Rates';
$_['button_enable_disable']   = 'Enable/Disable Currencies';
$_['checkbox_value']       = 'All';

// Column
$_['column_title']         = 'Currency Title';
$_['column_code']          = 'Code';
$_['column_status']        = 'Status';
$_['column_lock_rate']     = 'Auto Update<br>Enabled';
$_['column_value']         = 'Value';
$_['column_date_modified'] = 'Last Updated';
$_['column_action']        = 'Action';

// Entry
$_['entry_title']          = 'Currency Title:';
$_['entry_code']           = 'Code:';
$_['entry_status']         = 'Status:';
$_['entry_lock_rate']      = 'Lock Rate:';
$_['entry_value']          = 'Value:';
$_['entry_symbol_left']    = 'Symbol Left:';
$_['entry_symbol_right']   = 'Symbol Right:';
$_['entry_decimal_place']  = 'Decimal Places:';

// Error
$_['error_time']           = '** Automatic update did not complete due to timeout from slow connection **';
$_['error_permission']     = 'Warning: You do not have permission to modify currencies';
$_['error_title']          = '* Currency Title must be between 1 and 32 characters!';
$_['error_code']           = '* Currency Code must contain exactly 3 characters!';
$_['error_disable']        = '* You cannot disable default currency!';// Change to error_disable
$_['error_default']        = 'Warning: This Currency cannot be deleted as it is currently assigned as the default store currency!';
?>
