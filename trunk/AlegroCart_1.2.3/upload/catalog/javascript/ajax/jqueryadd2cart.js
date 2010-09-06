(function($) {
	$.extend({

		add2cart: function(source_id, target_id, callback) {
	  $('#cart_products').show(); // Hide mini_cart contents
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
	  ).animate({opacity: 0.5},100 , function(){shadow.hide();}
	  ).animate({opacity: 0},{duration: 0, complete: callback});
	  $('#cart_products').hide(5000); // show mini_cart contents
	  }
	});
})(jQuery);

function GetData(product_id, controller){
	var Product_id = product_id;
	var Controller = controller;
	var item = String("item=" + product_id); //String("item=" + $('#product_id_'+Product_id).val());
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
				if(i == 0){item +=":";}
				else {item +=".";}
				item += (options[i]);
		}
	}
	if($('#'+Controller+'_quantity_'+Product_id)[0].tagName == "INPUT"){
		if(parseInt($('#'+Controller+'_quantity_'+Product_id).val()) < parseInt($('#'+Controller+'_min_qty_'+Product_id).val())){
		  $('#'+Controller+'_quantity_'+Product_id).val($('#'+Controller+'_min_qty_'+Product_id).val());
		}
	}
	var quantity = String("&quantity=" + $('#'+Controller+'_quantity_'+Product_id).val());
	var data = String(item + quantity);
	return data;
}
function UpdateTotal(decimal_place, decimal_point, product_id, controller){
	var Decimal_Place = decimal_place;
	var Decimal_point = decimal_point;
	var Controller = controller;
	var Product_id = product_id;
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
	$('a[class*=active]').parents('li').each(function(i,li_parent){
		var str=$(li_parent).children('a').attr('class');
		if((str.search("active")) == -1){
		$(li_parent).children('a').css({'background-color': '#EEEEEE', 'color':'#000000'});
		}
	});
});
$(document).ready(function(){
    $('#category_menu li').hover(function(){
 		$(this).find('ul:first').attr('style','display:block');
		$(this).parents('li').each(function(i,li_parent){
			$(li_parent).children('a').css({'background-color': '#EEEEEE', 'color':'#000000'});
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