  $(function() {

	var xOffset = 10;
	var yOffset = 20;

	if (typeof window.innerHeight !== 'undefined') {effectiveHeight = window.innerHeight;}
	else if (typeof document.documentElement !== 'undefined' && typeof document.documentElement.clientHeight !=='undefined' && document.documentElement.clientHeight !== 0) {effectiveHeight = document.documentElement.clientHeight;}
	else {effectiveHeight = document.getElementsByTagName('body')[0].clientHeight;}

	var scrolled = 0;

  $("#image_to_preview option, [id^=image_id] option, #get_products option, #wm_wmimage_id option, #manufacturer_id option").hover(function (event) {
	this.t = this.title;
	this.title = "";
	
	if (typeof window.pageYOffset === 'number') {scrolled = window.pageYOffset;}
	else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {scrolled = document.body.scrollTop;}
	else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {scrolled = document.documentElement.scrollTop;}
	if (this.t !== '') {
	$("body").append("<p id='preview'><img src='"+ this.t + "' >" + "</p>");								 
	$("#preview").css("top", (event.pageY - scrolled < effectiveHeight/2 ? (event.pageY - xOffset) : event.pageY - xOffset - (this.t.substr(-7,3))) + "px").css("left",(event.pageX + yOffset) + "px").fadeIn("fast");
        }
  },
  function() {
	this.title = this.t;
	$("#preview").remove();
  }
  );

  $("#image_to_preview option, [id^=image_id] option, #get_products option, #wm_wmimage_id option, #manufacturer_id option").mousemove(function(event){
	$("#preview").css("top",(event.pageY - scrolled < effectiveHeight/2 ? (event.pageY - xOffset) : event.pageY - xOffset - (this.t.substr(-7,3))) + "px").css("left",(event.pageX + yOffset) + "px");
  });
  });
