<?php
// Text
$_['text_authnetaim_title']                 = 'Credit Card (Auth.net AIM)';
$_['heading_title']                         = 'Credit Card (Auth.net AIM)';
$_['text_testing']                          = 'The Authorize.net payment extension is in test mode.<ul type="disc"><li />This payment will be validated through the Authorize.net TEST payment gateway.<li />No money will be taken from the credit card.<li />The order you are placing will be saved in your order history but it will not be delivered.</ul>';

$_['text_creditcard']                       = 'Credit Card Details';
$_['text_mastercard']                       = 'MasterCard';
$_['text_visa']                             = 'Visa';
$_['text_mastercard_cvv_location']          = 'On the signature strip on the back of the card,<br />the last digits of the card number are reprinted in reverse italics, followed by 3-digit CVV.';
$_['text_visa_cvv_location']                = 'On the signature strip on the back of the card,<br />the last digits of the card number are reprinted in reverse italics, followed by 3-digit CVV.';
$_['text_testmode']	                       	= 'ATTENTION!!! Authorize.net is in \'Test Mode\'. Your credit card will not actually be charged.';

$_['entry_cc_type']                 		= 'Card Type:';
$_['entry_cc_number']               		= 'Card Number:';
$_['entry_cc_expire']             			= 'Exp (mm/yy):';
$_['entry_cc_cvv']                  		= 'CVV:';

$_['error_cc_number_missing']               = 'A value must be entered for "Card Number"';
$_['error_cc_month_missing']       			= 'A value must be entered for "Expiration" (month). eg: 12';
$_['error_cc_year_missing']        			= 'A value must be entered for "Expiration" (year). eg: 09';
$_['error_cc_cvv_missing']          		= 'A value must be entered for "CVV".';
$_['error_cc_number_not_numeric']           = '"Card Number" must be numeric. (you can have spaces)';
$_['error_cc_number_invalid']               = 'The value entered for "Card Number" is not a valid credit card number.';
$_['error_cc_number_invalid_for_type']      = 'The value entered for "Card Number" is a valid credit card number. However, it is not valid for the "Card Type" selected. Please check that the "Card Type" selected is the same as your credit card.';
$_['error_cc_month_not_numeric']   			= '"Expiration" (month) must be numeric. eg: 07';
$_['error_cc_year_not_numeric']    			= '"Expiration" (year) must be numeric eg: 08';
$_['error_cc_month_invalid_range'] 			= '"Expiration" (month) must be in the range 1 to 12.';
$_['error_cc_year_invalid_range']  			= '"Expiration" (year) cannot be more than 10 years into the future.';
$_['error_cc_expires_too_soon']             = 'Your card will expire too soon to complete this transaction.';
$_['error_cc_expired']                      = 'Your card has expired.';
$_['error_mastercard_cvv']                  = 'The "CVV" for MasterCard must be a 3 digit number. eg: 546';
$_['error_visa_cvv']                        = 'The "CVV" for Visa must be a 3 digit number. eg: 546';
$_['error_cc_expired']                      = 'Your card has expired.';
$_['error_authnetaim_response']            	= 'Sorry, the payment has failed. The payment gateway has returned the error status ';
$_['error_authnetaim_down']                 = 'Sorry, the payment has failed. A network connection could not be made to the Authorize.net gateway.';
$_['error_authnetaim_invalid_message']      = 'Sorry, the payment has failed. The reply from the Authorize.net gateway could not be understood.';
$_['error_ret_code']          				= 'Your credit card could not be processed. The payment gateway returned this error message: ';                                                                                    
?>