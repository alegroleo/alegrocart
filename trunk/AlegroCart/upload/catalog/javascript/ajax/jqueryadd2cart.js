(function($) {
	$.extend({

		add2cart: function(source_id, target_id, callback) {
		$('#' + source_id + '_shadow').remove();
		var source = $('#' + source_id );
		var target = $('#' + target_id );
		var display_image = $('#' + source_id).attr('src');
		var shadow = $('#' + source_id + '_shadow');
		if( !shadow.attr('id') ) {
			$('body').prepend('<img id="'+source.attr('id')+'_shadow" style="display: none; background-color: #ddd; border: solid 1px darkgray; position: static; top: 0px; z-index: 20;" src="' + display_image + '">');
			var shadow = $('#'+source.attr('id')+'_shadow');
		}
		if( !shadow ) {
			alert('Cannot create the shadow div');
		}
		shadow.width(source.css('width')).height(source.css('height')).css('top', source.offset().top).css('left', source.offset().left).css('opacity', 0.5).show();
		shadow.css('position', 'absolute');
		shadow.animate( { width: target.innerWidth(), height: target.innerHeight(), top: target.offset().top, left: target.offset().left }, 800,'linear'
		).animate({opacity: 0.5},300 , function(){shadow.hide();}
		).animate({opacity: 0},{duration: 0, complete: callback});
		}
	});
})(jQuery);
function ProductOptions(product_id,controller){
	var Product_id = product_id;
	var Controller = controller;
	var ProductWithOptions = product_id;
	var options = [];
	$('#'+Controller+'_options_'+Product_id+' select :selected').each(function(i, selected){
		options[i] = $(selected).val();
	});
	$('#'+Controller+'_options_'+Product_id+' :radio:checked').each(function(i, selected){
		options[i] = $(selected).val();
	});
	if(options.length!=undefined){
		var i;
		for (i in options){
				if(i == 0){ProductWithOptions +="\\:";}
				else {ProductWithOptions +="\\.";}
				ProductWithOptions += (options[i]);
		}
	}
	return ProductWithOptions;
}
function GetData(product_id, controller){
	var Product_id = product_id;
	var Controller = controller;
	var item = String("item=");
	var ProductWithOptions = ProductOptions(product_id,controller);
	var Max_qty = parseInt($('#'+Controller+'_max_qty_'+Product_id).val());
	var Min_qty = parseInt($('#'+Controller+'_min_qty_'+Product_id).val());
	var Multiple = parseInt($('#'+Controller+'_multiple_'+Product_id).val());
	var Quantity = parseInt($('#'+Controller+'_quantity_'+Product_id).val());
	var Cart_level = parseInt($('#'+Controller+'_cart_level_'+ProductWithOptions).val());
	if($('#'+Controller+'_quantity_'+Product_id)[0].tagName == "INPUT"){
		if((Quantity+Cart_level) < Min_qty){
			Quantity = Min_qty;
		}
		if(Max_qty != 0) {
			if((Quantity+Cart_level) > Max_qty){
				Quantity = Max_qty-Cart_level;
			}
		}
	}
	if(Multiple != 0) {
		if((Quantity+Cart_level) <= Multiple){
			Quantity = Multiple;
		} else {
			if((Quantity+Cart_level) % Multiple == 0){
				Quantity = Quantity;
			} else {
				var rest = ((Quantity+Cart_level) % Multiple);
				Quantity += Multiple-rest;
				if (Quantity+Cart_level > Max_qty) {
				Quantity -= Multiple; 
				}
			}
		}
	}
	ProductWithOptions= ProductWithOptions.replace(/\\/g,'');
	document.getElementById(Controller+'_cart_level_'+ProductWithOptions).value = Quantity+Cart_level;
	var quantity = String("&quantity="+Quantity);
	var data = String(item+ProductWithOptions+quantity);
	return data;
}
function UpdateAddToCartButton(product_id, controller, added_text, add_text){
	var Product_id = product_id;
	var Controller = controller;
	var Added_text = ' '+added_text;
	var Add_text = add_text || '';
	var ProductWithOptions = ProductOptions(product_id,controller);
	var Cart_level = parseInt($('#'+Controller+'_cart_level_'+ProductWithOptions).val());
	if (Cart_level>0){
		$('#'+Controller+'_add_'+Product_id).val(Cart_level+Added_text);
	} else {
		$('#'+Controller+'_add_'+Product_id).val(Add_text);
	}
}
function UpdateAddToCart(product_id, controller){
	var Product_id = product_id;
	var Controller = controller;
	var ProductWithOptions = ProductOptions(product_id,controller);
	var Multiple = parseInt($('#'+Controller+'_multiple_'+Product_id).val());
	var Max_qty = parseInt($('#'+Controller+'_max_qty_'+Product_id).val());
	var Cart_level = parseInt($('#'+Controller+'_cart_level_'+ProductWithOptions).val());
	var selectList, x;
	if($('#'+Controller+'_quantity_'+Product_id)[0].tagName == "INPUT"){
		$('#'+Controller+'_quantity_'+Product_id).val(Multiple == 0 ? 1 : Multiple);
		if (Max_qty != 0) {
			var diff = Max_qty-Cart_level;
			if (diff == 0 || diff < Multiple) {
				$('#'+Controller+'_quantity_'+Product_id).hide();
				$('#'+Controller+'_add_'+Product_id).attr("disabled","disabled");
			} else {
				$('#'+Controller+'_quantity_'+Product_id).show();
				$('#'+Controller+'_add_'+Product_id).removeAttr("disabled");
			}
		}
	} else {
		var lastValue = parseInt($('#'+Controller+'_quantity_'+Product_id+' option:last-child').val());
		var upto = Max_qty == 0 ? lastValue : Max_qty - Cart_level;
		if (upto == 0 || upto < Multiple) {
			selectList += '<option value="0" selected="selected">0</option>';
			$('#'+Controller+'_quantity_'+Product_id).hide();
			$('#'+Controller+'_add_'+Product_id).attr("disabled","disabled");
		} else {
			$('#'+Controller+'_quantity_'+Product_id).show();
			$('#'+Controller+'_add_'+Product_id).removeAttr("disabled");
			for (x = 1; x < upto +1; x++) {
				if ((Multiple != 0) && (x % Multiple !=0)) continue;
				selectList += '<option value="'+x+'">'+x+'</option>';}
		}
		$('#'+Controller+'_quantity_'+Product_id).html(selectList);
	}
}
function UpdateDimensions(product_id,controller){
	var Product_id = product_id;
	var Controller = controller;
	var ProductWithOptions = ProductOptions(product_id,controller);
	var dimension = $('#'+Controller+'_dimension_'+ProductWithOptions).val();
	if(dimension!=undefined){
		if(dimension.length > 0){
			document.getElementById(Controller+'_dimensions_'+Product_id).innerHTML = dimension;
		} else {
			document.getElementById(Controller+'_dimensions_'+Product_id).innerHTML = $('#dimension_'+Product_id).val();
		}
	}
}
function UpdateImage(product_id,controller){
	var Product_id = product_id;
	var Controller = controller;
	var ProductWithOptions = ProductOptions(product_id,controller);
	var popup = $('#'+Controller+'_popup_'+ProductWithOptions).val();
	var thumb = $('#'+Controller+'_thumb_'+ProductWithOptions).val();
	var Href = $('#'+Controller+Product_id).attr('href');
	var width = $('#magnifier_width').val();
	var height = $('#magnifier_height').val();

	if(thumb!=undefined){
		if(thumb.length > 0){
			document.getElementById(Controller+'_image'+Product_id).src = thumb;
			if(Href!=undefined){
				document.getElementById(Controller+Product_id).href = popup;
				document.getElementById(Controller+'_enlarge'+Product_id).href = popup;
			}
		} else {
			document.getElementById(Controller+'_image'+Product_id).src = $('#'+Controller+'_thumb_'+Product_id).val();
			if(Href!=undefined){
				document.getElementById(Controller+Product_id).href = $('#'+Controller+'_popup_'+Product_id).val();
				document.getElementById(Controller+'_enlarge'+Product_id).href = $('#'+Controller+'_popup_'+Product_id).val();
			}
		}
		if(width!=undefined && height!=undefined){
			$("#"+Controller+"_image"+Product_id).addpowerzoom({
				defaultpower: 2,
				powerrange: [2,10],
				largeimage: document.getElementById(Controller+Product_id).href,
				magnifiersize: [width,height]
			});
		}
	}
}
function UpdateModel(product_id,controller){
	var Product_id = product_id;
	var Controller = controller;
	var ProductWithOptions = ProductOptions(product_id,controller);
	var Model = $('#'+Controller+'_model_'+ProductWithOptions).val();
	if(Model!=undefined){
		document.getElementById(Controller+'_model_'+Product_id).innerHTML = Model;
	}
}
function UpdateQuantity(product_id,controller){
	var Product_id = product_id;
	var Controller = controller;
	var LowStock = $('#low_stock_warning').val();
	var ProductWithOptions = ProductOptions(product_id,controller);
	var onhand = $('#'+Controller+'_stock_level_'+ProductWithOptions).val();
	var ImgPath = '';
	if(onhand!=undefined){
		$('#'+Controller+'_stock_level_'+Product_id).text(onhand);
	}
	if(LowStock!=undefined && onhand!=undefined){
		onhand = (+onhand);
		LowStock = (+LowStock);
		if(onhand > 0 && onhand > LowStock){
			ImagPath = $('#stock_status_g').val();
		} else if(onhand > 0 && onhand <= LowStock){
			ImagPath = $('#stock_status_o').val();
		} else {
			ImagPath = $('#stock_status_r').val();
		}
		document.getElementById('stock_icon_'+Controller+'_'+Product_id).src = ImagPath;
	}
}
function UpdateBarcode(product_id,controller){
	var Product_id = product_id;
	var Controller = controller;
	var ProductWithOptions = ProductOptions(product_id,controller);
	var BarCode = $('#'+Controller+'_barcode_'+ProductWithOptions).val();
	if(BarCode!=undefined && BarCode > 0){
		var ImagPath = $('#'+Controller+'_barcode_url_'+ProductWithOptions).val();
		$('#'+Controller+'_barcode_'+Product_id).text(BarCode);
		document.getElementById('barcode_'+Controller+'_'+Product_id).src = ImagPath;
		$('#'+Controller+'_barcode_text_'+Product_id).attr('style','visibility:visible');
		$('#'+Controller+'_barcode_'+Product_id).attr('style','visibility:visible');
		$('#barcode_'+Controller+'_'+Product_id).attr('style','visibility:visible');
	} else {
		$('#'+Controller+'_barcode_text_'+Product_id).attr('style','visibility:hidden');
		$('#'+Controller+'_barcode_'+Product_id).attr('style','visibility:hidden');
		$('#barcode_'+Controller+'_'+Product_id).attr('style','visibility:hidden');
	}
}
function UpdateWeight(decimal_place, decimal_point, product_id,controller){
	var Decimal_Place = decimal_place;
	var Decimal_point = decimal_point;
	var Controller = controller;
	var Product_id = product_id;
	var Weight = $('#weight_'+Product_id).val();
	var options = [];
	$('#'+Controller+'_options_'+Product_id+' select :selected').each(function(i, selected){
		options[i] = $(selected).val();
	});
	$('#'+Controller+'_options_'+Product_id+' :radio:checked').each(function(i, selected){
		options[i] = $(selected).val();
	});
	if(Weight==undefined){
		Weight = 0;
	}
	Weight = parseFloat(Weight);
	if(options.length!=undefined){
		var i;
		for (i in options){
			TempWeight = $('#'+Controller+'_weight_'+options[i]).val();
			if(TempWeight!=undefined){
				Weight += parseFloat(TempWeight);
			}
		}
	}
	Weight_new = (((Weight*100)/100).toFixed([Decimal_Place]));
	Weight = Weight_new.replace('.', Decimal_point);
	$('#'+Controller+'_weights_'+Product_id).text(Weight);
}
function UpdateDiscounts(controller,product_id,decimal_place,decimal_point, price){
	var Decimal_Place = decimal_place;
	var Decimal_point = decimal_point;
	var Controller = controller;
	var Product_id = product_id;
	if(price==0){
		var price = $('#product_with_options_'+Product_id).text();
	}
	price = price.replace(",", ".");
	var Discounts = $('#'+Controller+'_discounts_'+Product_id).val();
	if(Discounts==undefined){
		return;
	}
	for (var i=0; i< Discounts; i++){
		var percent = $('#'+Controller+'_percent_'+Product_id+'_'+i).text();
		percent = percent.replace(",", ".");
		var amount = price * (percent / 100);
		var Discount_new = amount.toFixed([Decimal_Place]);
		Discount_new = Discount_new.replace('.', Decimal_point);
		$('#'+Controller+'_discount_'+Product_id+'_'+i).text(Discount_new);
	}
}
function UpdateTotal(decimal_place, weight_decimal, decimal_point, product_id, controller, added_text, add_text){
	var Decimal_Place = decimal_place;
	var Decimal_point = decimal_point;
	var Weight_decimal = weight_decimal;
	var Controller = controller;
	var Product_id = product_id;
	var Added_text = added_text;
	var Add_text = add_text || '';
	UpdateQuantity(Product_id, Controller);
	UpdateBarcode(product_id,controller)
	UpdateImage(product_id,controller);
	UpdateModel(product_id,controller);
	UpdateDimensions(product_id,controller);
	UpdateWeight(Weight_decimal, decimal_point, product_id,controller);
	UpdateAddToCartButton(product_id, controller, Added_text, Add_text);
	UpdateAddToCart(product_id, controller);

	var Price = $('#base_price_' + Product_id).val();
	var options= [];

	$('#'+Controller+'_options_'+Product_id+' select :selected').each(function(i, selected){
		options[i] = $(selected).attr('id');
	});
	$('#'+Controller+'_options_'+Product_id+' :radio:checked').each(function(i, checked){
		options[i] = $(checked).attr('id');
	});
	Price = parseFloat(Price);
	if(options.length!=undefined){
		var i;
		for (i in options){
			var OptionID = (options[i]);
			var OptionPrice = String("option_price"+OptionID);
			var OptionValue = $('#'+OptionPrice).attr('value');
			var mySplitResult = OptionValue.split("#");
			if(mySplitResult[0] == "+"){
				Price = Price + parseFloat(mySplitResult[1]);}
			else {Price = Price - parseFloat(mySplitResult[1]);
			}
		}
	}
	Price_new = (((Price*100)/100).toFixed([Decimal_Place]));
	Price = Price_new.replace('.', Decimal_point);
	$('#product_with_options_'+Product_id).fadeOut('slow',function(){
	$('#product_with_options_'+Product_id).fadeIn('normal').text(Price);
	});
	UpdateDiscounts(Controller,Product_id,Decimal_Place,Decimal_point,Price_new);
	return;
}
$(document).ready(function(){
	$('#manufacturer_list').focus(function(){
		resize($('#manufacturer_list').attr("id"));
	});
	$('#manufacturer_list').blur(function(){
		resize($('#manufacturer_list').attr("id"), 180);
	});
	$('#manufacturer_option').focus(function(){
		resize($('#manufacturer_option').attr("id"));
	});
	$('#manufacturer_option').blur(function(){
		resize($('#manufacturer_option').attr("id"), 180);
	});
});
function resize(selectId, size){
	BrowserisIE = (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) ? 1 : 0;
	if (BrowserisIE){
		var objSelect = document.getElementById(selectId);
	} else {
		var objSelect = $('#' + selectId).attr('id');
	}
	var maxlength = 0;
	if(objSelect){
	if(size){
			$('#' + selectId).width(size);
	} else {
		for (var i=0; i< objSelect.options.length; i++){
			if (objSelect[i].text.length > maxlength){
				maxlength = objSelect[i].text.length;
			}
		}
			if (BrowserisIE){
				objSelect.style.overflow = "visible";
				objSelect.style.whiteSpace = "normal";
				objSelect.style.zIndex = 1000;
				objSelect.style.width = maxlength * 7;

			} else {
				$('#' + selectId).focus(function(){$('#' + selectId).width(maxlength * 7)});
			}
	}
	}
}
$(document).ready(function(){
	var bg_color = $('#menu_breadcrumb').css('background-color');
	var text_color = $('#menu_breadcrumb').css('color');
	bg_color==undefined || bg_color=='transparent' ? bg_color = '#D9D9D9' : bg_color = bg_color;
	$('a[class*=active]').parents('li').each(function(i,li_parent){
		var str=$(li_parent).children('a').attr('class');
		if((str.search("active")) == -1){
		$(li_parent).children('a').css({'background-color': bg_color, 'color':text_color});
		}
	});
});
$(document).ready(function(){
	var bg_color = $('#menu_breadcrumb').css('background-color');
	var text_color = $('#menu_breadcrumb').css('color');
	bg_color==undefined || bg_color=='transparent' ? bg_color = '#D9D9D9' : bg_color = bg_color;
	$('#category_menu li').hover(function(){
		$(this).find('ul:first').attr('style','display:block');
		$(this).parents('li').each(function(i,li_parent){
			$(li_parent).children('a').css({'background-color': bg_color, 'color':text_color});
		});
	}, function() {
		var lilist = [];
		$(this).find('ul:first').each(function(i,selected){
			if ($(selected).attr('class') == "menu"){
				$(selected).parent('li').children('a').each(function(i,li_children){
					$(li_children).removeAttr('style','background-color');
				});
				$(selected).attr('style','display:none');
			}
		});
	});
});
