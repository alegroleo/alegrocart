/* Validate Form Input
Required: <script type="text/javascript" src="javascript/ajax/jquery.js"></script>
To use these functions, you must define after jquery in template header.
  <script type="text/javascript" src="javascript/ajax/validateforms.js"></script>

The following code should be included at the bottom of your tpl file to register validation.
  <script type="text/javascript"><!--
    $(document).ready(function() {
	  RegisterValidation();
    });
  //--></script>

To implement, include the appropriate class in your html input. You must ensure each input has a unique id as per sample.
<input type="text" class="validate_float" id="myproduct1" name="myproduct1" value="$10.00>

*/
function RegisterValidation(){ //register functions on document ready
     $('.validate_float').keyup (function(){ //class="validate_float"
	   ValidateFloat(this.id);
     });
	 $('.validate_float').on("copy paste", function(){  
	   ValidateFloat(this.id);
     });
	 $('.validate_float_n').keyup (function(){  //class="validate_float_n"
	   ValidateFloatNeg(this.id);
     });
	 $('.validate_float_n').on("copy paste", function(){  
	   ValidateFloatNeg(this.id);
     });
	 $('.validate_int').keyup (function(){  //class="validate_int"
	   ValidateInt(this.id);
     });
	 $('.validate_int').on("copy paste", function(){  
	   ValidateInt(this.id);
     });
	 $('.validate_int_n').keyup (function(){  //class="validate_int_n"
	   ValidateIntNeg(this.id);
     });
	 $('.validate_int_n').on("copy paste", function(){  
	   ValidateIntNeg(this.id);
     });
	 $('.validate_zone').keyup (function(){  //class="validate_alpha_num"
	   ValidateZone(this.id);
	 });
	 $('.validate_zone').on("copy paste", function(){  
	   ValidateZone(this.id);
     });
	 $('.validate_alpha_num').keyup (function(){  //class="validate_alpha_num"
	   ValidateAlphaNumeric(this.id);
	 });
	 $('.validate_alpha_num').on("copy paste", function(){  
	   ValidateAlphaNumeric(this.id);
     });
	 $('.validate_alpha').keyup (function(){    //class="validate_alpha"
	   ValidateAlpha(this.id);
	 });
	 $('.validate_alpha').on("copy paste", function(){  
	   ValidateAlpha(this.id);
     });
	 $('.validate_phone').keyup (function(){  //class="validate_phone"
	   ValidatePhone(this.id);
	 });
	 $('.validate_phone').on("copy paste", function(){  
	   ValidatePhone(this.id);
	 });
	 $('.validate_ip').keyup (function(){  // class="validate_ip"
	   ValidateIP(this.id);
	 });
	 $('.validate_ip').on("copy paste", function(){
	   ValidateIP(this.id);
	 });
	 $('.validate_mail').keyup (function(){  // class="validate_mail"
	   ValidateMail(this.id);
	 });
	 $('.validate_mail').on("copy paste", function(){
	   ValidateMail(this.id);
	 });
	 $('.validate_hex').keyup (function(){  // class="validate_hex"
	   ValidateHexadecimal(this.id);
	 });
	 $('.validate_hex').on("copy paste", function(){
	   ValidateHexadecimal(this.id);
	 });
	 $('.validate_file').keyup (function(){  // class="validate_file"
	   ValidateFilename(this.id);
	 });
	 $('.validate_file').on("copy paste", function(){
	   ValidateFilename(this.id);
	 });
	 $('.validate_meta').keyup (function(){ // class="validate_meta"
	   ValidateMetaTags(this.id);
	 });
	 $('.validate_meta').on("copy paste", function(){
	   ValidateMetaTags(this.id);
	 });
	 $('.validate_url').keyup (function(){ // class="validate_url"
	   ValidateURL(this.id);
	 });
	 $('.validate_url').on("copy paste", function(){
	   ValidateURL(this.id);
	 });
	 $('.validate_shipping').keyup (function(){ // class="validate_shipping"
	   ValidateShipping(this.id);
	 });
	 $('.validate_shipping').on("copy paste", function(){
	   ValidateShipping(this.id);
	 });
	 $('.validate_cc').keyup (function(){ // class="validate_cc"
	   ValidateCreditCard(this.id);
	 });
	 $('.validate_cc').on("copy paste", function(){
	   ValidateCreditCard(this.id);
	 });
}
function ValidateShipping(form_id){  //matches valid characters for zone shipping
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^0-9:\.,]/g) != -1){
		var str = Input_value.match(/[0-9:\.,]/g);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateURL(form_id){ //matches valid URL characters
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^htps]*[^:]?[^\/]{0,2}[^w]{0,3}[\.]?[^\w]*[^\.]?[^a-z]{0,3}[^\w\/\.]*/) != -1){
		var str = Input_value.match(/[htps]*[:]?[\/]{0,2}[w]{0,3}[\.]?[\w]*[\.]?[a-z]{0,3}[\w\/\.]*/);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateMetaTags(form_id){  //matches valid metatag characters.
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^\w]/g) != -1){
		var str = Input_value.match(/[\w\-,\*\?\+&\$#\(\)\s]/gi);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateFilename(form_id){ //matches valid filename characters.
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^a-z]/g) != -1){
		var str = Input_value.match(/^[a-zA-Z]{1}[\w\-]*\.?[a-zA-Z]*/);
		if(str != undefined){
			str = str.join("");
			str = str.toLowerCase();
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateHexadecimal(form_id){  //matches hexadecimal valid characters. Converts alpha to uppercase
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^\dA-F]/g) != -1){
		var str = Input_value.match(/[\dA-F]/gi);
		if(str != undefined){
			str = str.join("");
			str = str.toUpperCase();
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateMail(form_id){ //matches email for valid characters
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^\w\.\-@]/gi) != -1){
		var str = Input_value.match(/[\w\.\-@]/gi);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateIP(form_id){   //matches IP address for valid characters
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^\da-f\.:]/gi) != -1){
		var str = Input_value.match(/[\da-f\.:]/gi);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidatePhone(form_id){   //returns valid phone number characters
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^0-9\-\+\s\/\(\)]/gi) != -1){
		var str = Input_value.match(/[0-9\-\+\s\/\(\)]/gi);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
function ValidateFloatNeg(form_id){ //returns floating number, type="text"
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/^[0-9]+$/) == -1){
		var str = Input_value.match(/[-+]?[0-9]*\.?,?[0-9]*/);
		if(str != undefined){
			str = str.join("");
			str = str.replace(",",".");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	} 
	return;
}
function ValidateFloat(form_id){  //returns floating number, positive only
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/^[0-9]+$/) == -1){
		var str = Input_value.match(/[0-9]*\.?,?[0-9]*/);
		if(str != undefined){
			str = str.join("");
			str = str.replace(",",".");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	} 
	return;
}
function ValidateIntNeg(form_id){ //returns integer, type="text"
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/^[0-9]+$/) == -1){
		var str = Input_value.match(/[-+]?[0-9]*/);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	} 
	return;
}
function ValidateInt(form_id){  //returns integer, positive only
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/^[0-9]+$/) == -1){
		var str = Input_value.match(/[0-9]*/);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	} 
	return;
}
function ValidateZone(form_id){  //returns alpha numeric string
	var Input_value = $('#' + form_id).val();
	var Not_alpha_num = /[\\\|\+\$\[\]\^\(\)\*\?";&%#@~`,={}!<>:§¶€ŧÍ„”÷×]/g;
	var str = Input_value.replace(Not_alpha_num, '');
	if(str != undefined){
		$('#'+form_id).val(str);
	} else {
		$('#'+form_id).val("");
	}
}
function ValidateAlphaNumeric(form_id){  //returns alpha numeric string
	var Input_value = $('#' + form_id).val();
	var Not_alpha_num = /[\/\\\|\+\$\[\]\^\(\)\*\?";&%#@~`,={}!<>:§¶€ŧÍ„”÷×]/g;
	var str = Input_value.replace(Not_alpha_num, '');
	if(str != undefined){
		$('#'+form_id).val(str);
	} else {
		$('#'+form_id).val("");
	}
}
function ValidateAlpha(form_id){  //returns alpha only string 
	var Input_value = $('#' + form_id).val();
	var Not_alpha = /[0-9\/\\\|\+\$\[\]\^\(\)\*\?";&%#@~`,={}!<>:§¶€ŧÍ„”÷×]/g;
	var str = Input_value.replace(Not_alpha, '');	

	if(str != undefined){
		$('#'+form_id).val(str);
	} else {
		$('#'+form_id).val("");
	}
}
function ValidateCreditCard(form_id){   //matches valid credit card numbers
	var Input_value = $('#' + form_id).val();
	if(Input_value.search(/[^0-9\-\s]/gi) != -1){
		var str = Input_value.match(/[0-9\-\s]/gi);
		if(str != undefined){
			str = str.join("");
			$('#'+form_id).val(str);
		} else {
			$('#'+form_id).val("");
		}
	}
}
