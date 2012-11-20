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
    $('#myMenuID li').hover(function(){
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
