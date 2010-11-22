<?php 
  $head_def->setcss( $this->style . "/css/currency_converter.css");
  $head_def->set_javascript("ajax/jquery.js");
?>
<?php if($location == 'header'){?>
	<div id="converter" class="converter" style="position: absolute; left: 80px;">
<?php } else {?>
	<div id="converter" class="converter">
<?php }?>
  <div class="headingcolumn" style="cursor: pointer" onclick="OpenConversion()"><h1><?php echo $heading_title; ?></h1></div>
  
  <div class="module_column" style="padding-left: 0px;">
	<div id="c_data">
	  <table>
	    <tr><td>
	      <select id="from" class="from">
		    <option value="" selected><?php echo $text_base;?></option>
		    <?php foreach($currencies as $currency) {?>
		      <option value="<?php echo $currency['code'];?>"><?php echo $currency['title'];?></option>
		    <?php }?>
	      </select>
	    </td></tr>
	    <tr><td>
	      <select id="to" class="to">
		    <option value="" selected><?php echo $text_convert_to;?></option>
		    <?php foreach($currencies as $currency) {?>
		      <option value="<?php echo $currency['code'];?>"><?php echo $currency['title'];?></option>
		    <?php }?>
	      </select> 
	    </td></tr>
		<tr><td>
		  <?php echo $text_amount;?>
		  <input size="10" type="text" name="amount" id="amount" value="1.00">
		</td></tr>
	    <tr><td>
	      <input class="button" type="button" value="<?php echo $text_button;?>" onclick="$('#conversion').load('index.php?controller=tools&amp;action=convert_currency&amp'+ConversionData());">
	    </td></tr>
		<tr><td id="conversion">
		</td></tr>
	  </table>
    </div>
	<div class="clearfix"></div>
  </div>
  <div class="columnBottom"></div>
</div>
<script type="text/javascript"><!--
$(document).ready(function(){
	$('#c_data').hide(0);

});
function OpenConversion(){
	$('#c_data').toggle('slow');
}
function ConversionData(){
	var Amount = $('#amount').val();
	var From = $('#from').val();
	var To = $('#to').val();
	Data = 'from='+From+'&to='+To+'&amount='+Amount;
	return Data;
}
//--></script>