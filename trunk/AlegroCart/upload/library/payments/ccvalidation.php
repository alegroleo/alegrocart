<?php //ccValidation AlegroCart
class ccvalidation{
	var $errors = array();

	function validate_cvv($cvv, $card_type){
		if (strlen($cvv) == 0) {
            $this->errors[] = 'error_cc_cvv_missing';
			return TRUE;
		}
		$ccCVV = preg_replace('#[^0-9]#', '', $cvv);
		if(strlen($cvv) <> strlen($ccCVV)){
			$this->errors[] = 'error_cc_cvv_not_numeric';
			return TRUE;
		}
		if($card_type == 'amex'){
			if(!(preg_match('#^[0-9]{4}$#', $ccCVV))){
				$this->errors[] = 'error_invalid_cvv_amex';
				return TRUE;
			}
		} else {
			if(!(preg_match('#^[0-9]{3}$#', $ccCVV))){
				$this->errors[] = 'error_invalid_cvv';
				return TRUE;
			}
		}
		return FALSE;
	}
	
	function validate_date($Month, $Year){
		if (strlen($Month) == 0) {
            $this->errors[] = 'error_cc_month_missing';
			return TRUE;
        }
		if (strlen($Year) == 0) {
            $this->errors[] = 'error_cc_year_missing';
			return TRUE;
        }
		$ccMonth = preg_replace('#[^0-9]#', '', $Month);
        $ccYear = preg_replace('#[^0-9]#', '', $Year);
		if (strlen($ccMonth) <> strlen($Month)) {
            $this->errors[] = 'error_cc_month_not_numeric';
			return TRUE;
        }
        if (strlen($ccYear) <> strlen($Year)) {
            $this->errors[] = 'error_cc_year_not_numeric';
			return TRUE;
        }
		$ccMonth = (int)$ccMonth;
        if ($ccMonth < 1 || $ccMonth > 12) {
            $this->errors[] = 'error_cc_month_invalid_range';
			return TRUE;
        }
		$ccYear = (int)$ccYear;
        $currentYear = (int)date('y');
        if ($ccYear  > $currentYear + 10) {
            $this->errors[] = 'error_cc_year_invalid_range';
			return TRUE;
        }
		$margin = 4;
		$ccExpiresLimit = mktime(0,0,0, $ccMonth + 1,  1 - $margin, $ccYear);  
        $today = mktime(0,0,0);
        if ($today >= mktime(0,0,0, $ccMonth + 1,  1, $ccYear)) {
            $this->errors[] = 'error_cc_expired';
			return TRUE;
        } else if ($today >= $ccExpiresLimit) {
            $this->errors[] = 'error_cc_expires_too_soon';
			return TRUE;
        }
		
		return FALSE;
	}
	
	function get_cc_type($number){
		$ccNumber = preg_replace('#[^0-9 -]#', '',$number);
		if (strlen($ccNumber) == 0) {
            $this->errors[] = 'error_cc_number_missing';
			return FALSE;
        } else if (strlen($ccNumber) <> strlen($number)) {
            $this->errors[] = 'error_cc_number_not_numeric';
			return FALSE;
        }
		$ccNumber = preg_replace('#[ -]#', '', $ccNumber);
		
		$card_type = "";
		$card_matches = array(
			'#^4[0-9]{12}([0-9]{3})?$#' 	=> 'visa',
			'#^5[1-5][0-9]{14}$#'       	=> 'mastercard',
			'#^3[47][0-9]{13}$#'          	=> 'amex',
			'#^6011[0-9]{12}$#'           	=> 'discover',
			'#^3(0[0-5]|[68][0-9])[0-9]{11}$#'     => 'diners',
			'#^(5018|5020|5038|6304|6759|6761)[0-9]{8,12}$#' 	=> 'maestro',
			'#^(5018|5020|5038|6304|6759|6761)[0-9]{14,15}$#' 	=> 'maestro',
		);
		foreach ($card_matches as $match => $type) {
			if (preg_match($match, $ccNumber)) {
				$card_type = $type;
				
				break;
			}
		}
		if(!$this->checksum($ccNumber) || !$card_type){
			$this->errors[] = 'error_cc_number_invalid';
			return FALSE;
		}
		return $card_type;
	}
	
	function checksum($cardNumber){
        $cardNumber = strrev($cardNumber);
        $numSum = 0;
        for($i = 0; $i < strlen($cardNumber); $i++) {
            $currentNum = substr($cardNumber, $i, 1);
            if ($i % 2 == 1) {
                $currentNum *= 2;
            }
            if ($currentNum > 9) {
                $firstNum = $currentNum % 10;
                $secondNum = ($currentNum - $firstNum) / 10;
                $currentNum = $firstNum + $secondNum;
            }
            $numSum += $currentNum;
        }
        return ($numSum % 10 == 0);
    }
	
	function mask_approval($approval, $value){
		$newapproval = substr('XXXXXXXXXXXX', strlen($approval) *-1);
		$newvalue = str_replace($approval, $newapproval, $value);
		return $newvalue;
	}
	
	function mask_expiry($expiry, $value){
		$newexpiry = substr('XXXXXXXX', strlen($expiry) *-1);
		$newvalue = str_replace($expiry, $newexpiry, $value);
		return $newvalue;
	}
	
	function mask_cvc($cvc, $value){
		$newcvc = substr('XXXX', strlen($cvc) *-1);
		$newvalue = str_replace($cvc, $newcvc, $value);
		return $newvalue;
	}
	
	function mask_cc($cc, $value){
		$newcc = 'XXXX...' . substr($cc, -4);
		$newvalue = str_replace($cc, $newcc, $value);
		return $newvalue;
	}

	function enCrypt($value, $key = ''){ // 8-32 characters without spaces
		if($key==''){return $value;}
		return $this->conversion($value, $key);
	}
	
	function deCrypt($value, $key = ''){
		if($key==''){return $value;}
		return $this->conversion($value, $key);
	}

	function conversion($value,$key){ // Original Author: halojoy
		if($key == ''){
			return $value;
		}
		$key = str_replace(chr(32),'',$key);
		if(strlen($key)<8){
			return('key error');
		}
		$kl = strlen($key)<32 ? strlen($key ): 32;
		$k = array();
		for($i=0;$i<$kl;$i++){
			$k[$i] = ord($key{$i})&0x1F;
		}
		$j = 0;
		for($i=0;$i<strlen($value);$i++){
			$e = ord($value{$i});
			$value{$i} = $e&0xE0 ? chr($e^$k[$j]) : chr($e);
			$j++;
			$j = $j==$kl ? 0 : $j;
		}
		return $value;
	}
}
?>