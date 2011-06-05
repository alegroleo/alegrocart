<?php // Credit Card validation Language


//Error
$_['error_cc_cvv_missing']          	= 'A numeric value must be entered for "CVV".';
$_['error_cc_cvv_not_numeric']          = '"CVV" must be numeric.';
$_['error_invalid_cvv_amex']            = 'The "CVV" for American Express must be a 4 digit number. eg: 3546';
$_['error_invalid_cvv']                 = 'The "CVV" for this card must be a 3 digit number. eg: 546';

$_['error_cc_month_missing']       		= 'A 2 digit value must be entered for "Expiration" (month). eg: 12';
$_['error_cc_month_not_numeric']   		= '"Expiration" (Month) must be numeric. eg: 07';
$_['error_cc_month_invalid_range'] 		= '"Expiration" (Month) must be in the range 1 to 12.';
$_['error_cc_year_not_numeric']    		= '"Expiration" (Year) must be numeric eg: 14';
$_['error_cc_year_missing']        		= 'A 2 digit value must be entered for "Expiration" (Year). eg: 13';
$_['error_cc_year_invalid_range']  		= '"Expiration" (Year) cannot be more than 10 years into the future.';
$_['error_cc_expired']                  = 'Your card has expired.';
$_['error_cc_expires_too_soon']         = 'Your card will expire too soon to complete this transaction.';

$_['error_cc_number_missing']           = 'A value must be entered for "Card Number"';
$_['error_cc_number_not_numeric']       = '"Card Number" must be numeric. (you can have spaces)';
$_['error_cc_number_invalid']           = 'The value entered for "Card Number" is not a valid credit card number.';



?>