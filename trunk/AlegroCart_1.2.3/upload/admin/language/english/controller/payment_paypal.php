<?php
// Heading
$_['heading_title']       = 'Payment PayPal';
$_['heading_description'] = 'You can edit payment PayPal account details here.';

// Text 
$_['text_message']        = 'Success: You have updated PayPal account details!';
$_['text_cad']            = 'Canadian Dollars (CAD)';
$_['text_eur']            = 'Euros (EUR)';
$_['text_gbp']            = 'Pounds Sterling (GBP)';
$_['text_usd']            = 'U.S. Dollars (USD)';
$_['text_jpy']            = 'Yen (JPY)';
$_['text_aud']            = 'Australian Dollars (AUD)';
$_['text_nzd']            = 'New Zealand Dollars (NZD)';
$_['text_chf']            = 'Swiss Francs (CHF)';
$_['text_hkd']            = 'Hong Kong Dollars (HKD)';
$_['text_sgd']            = 'Singapore Dollars (SGD)';
$_['text_sek']            = 'Swedish Kronor (SEK)';
$_['text_dkk']            = 'Danish Kroner (DKK)';
$_['text_pln']            = 'Polish Zloty (PLN)';
$_['text_nok']            = 'Norwegian Kroner (NOK)';
$_['text_huf']            = 'Hungarian Forint (HUF)';
$_['text_czk']            = 'Czech Koruna (CZK)';
$_['text_authorization']  = 'authorization';
$_['text_sale']           = 'sale';
$_['text_order']          = 'order';

// Entry
$_['entry_status']        = 'Status:';
$_['entry_geo_zone']      = 'Geo Zone:';
$_['entry_email']         = 'E-Mail:';
$_['entry_test']          = 'Test Mode:';
$_['entry_currency']      = 'Currency:';
$_['entry_sort_order']    = 'Sort Order:';
$_['entry_auth_type']     = 'Authorization:';
$_['entry_pdt_token']     = 'PDT Token:';
$_['entry_ipn_debug']     = 'Enable IPN Debug';
$_['entry_itemized']      = 'Show Paypal Itemized Cart';

// Extra
$_['extra_auth_type']     = 'Sale = Instant / Authorization = Manual accept';
$_['extra_pdt_token']     = 'Payment Data Transfer Token. (Please see support notes below). Leave blank to not use';
$_['extra_ipn_debug']     = 'Use to test IPN callbacks to your server. A file named "paypal_ipn_debug.txt" should be in your root';
$_['extra_itemized']      = 'Set to Yes to show customers an itemized cart list on Paypal\'s side. (Note: Only if no coupon code is in use)';


$_['text_support']        = '<strong>Support Notes:</strong> <br>' . "\n" .
                            'Follow these steps to setup your Paypal account correctly' . "\n" . 
                            '<ol>' . "\n" . 
                            '<li>Log into your PayPal account</li>' . "\n" .
                            '<li>Click on \'Profile\'</li>' . "\n" .
                            '<li>Click on \'Website Payment Preferences\'</li>' . "\n" .
                            '<li>Enable Auto Return</li>' . "\n" .
                            '<li>Set the Auto Return url to your site\'s callback page: https://my_store/index.php?controller=checkout_process&payment=paypal&method=return&action=callback</li>' . "\n" .
                            '<li>Enable Payment Data Transfer</li>' . "\n" .
                            '<li>Click Save</li>' . "\n" .
                            '<li>On the next screen, Copy and Paste the \'Identity Token\' from PayPal into the PDT Token field above.</li>' . "\n" .
							'<li>Note: No need to enable IPN as the paypal module automatically sets it.</li>' . "\n" .
                            '</ol>' . "\n";
                            


// Error
$_['error_permission']    = 'Warning: You do not have permission to modify payment PayPal';
$_['error_email']         = '* E-Mail Required!';
$_['error_pdt_token']     = '* PDT Token Required!';
?>