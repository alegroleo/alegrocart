  $(function() {

	var xOffset = 20;
	var yOffset = 10;
	var scrolled = 0;
	var posx = 0;
	var posy = 0;

	if (typeof window.innerHeight !== 'undefined') {effectiveHeight = window.innerHeight;}
	else if (typeof document.documentElement !== 'undefined' && typeof document.documentElement.clientHeight !=='undefined' && document.documentElement.clientHeight !== 0) {effectiveHeight = document.documentElement.clientHeight;}
	else {effectiveHeight = document.getElementsByTagName('body')[0].clientHeight;}

  $("#image_to_preview option, [id^=image_id] option, #get_products option, #wm_wmimage_id option, #manufacturer_id option, [id^=sliderimage_id] option").hover(function (e) {
	this.t = this.title;
	this.title = "";

	e = e || window.event;
	if (e.pageX || e.pageY) {
		posx = parseInt(e.pageX,10);
		posy = parseInt(e.pageY,10);
	}
	else if (e.clientX || e.clientY) {
		posx = parseInt(e.clientX + document.documentElement.scrollLeft,10);
		posy = parseInt(e.clientY + document.documentElement.scrollTop,10);
	}

	if (typeof window.pageYOffset === 'number') {scrolled = window.pageYOffset;}
	else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {scrolled = document.body.scrollTop;}
	else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {scrolled = document.documentElement.scrollTop;}
	if (this.t !== '') {
	$("body").append("<p id='preview'><img src='"+ this.t + "' >" + "</p>");
	$("#preview").css("top", (posy - scrolled < effectiveHeight/2 ? (posy - yOffset) : posy - yOffset - (this.t.substr(-7,3))) + "px").css("left",(posx + xOffset) + "px").fadeIn("fast");
        }
  },
  function() {
	this.title = this.t;
	$("#preview").remove();
  }
  );

  $("#image_to_preview option, [id^=image_id] option, #get_products option, #wm_wmimage_id option, #manufacturer_id option, [id^=sliderimage_id] option").mousemove(function(e){
	e = e || window.event;

	if (e.pageX || e.pageY) {
		posx = parseInt(e.pageX,10);
		posy = parseInt(e.pageY,10);
	}
	else if (e.clientX || e.clientY) {
		posx = parseInt(e.clientX + document.documentElement.scrollLeft,10);
		posy = parseInt(e.clientY + document.documentElement.scrollTop,10);
	}
	$("#preview").css("top",(posy - scrolled < effectiveHeight/2 ? (posy - yOffset) : posy - yOffset - (this.t.substr(-7,3))) + "px").css("left",(posx + xOffset) + "px");
  });
  });

function RegisterPreview() {

   var xOffset = 20;
   var yOffset = 10;
   var scrolled = 0;
   var posx = 0;
   var posy = 0;

   if (typeof window.innerHeight !== 'undefined') {effectiveHeight = window.innerHeight;}
   else if (typeof document.documentElement !== 'undefined' && typeof document.documentElement.clientHeight !=='undefined' && document.documentElement.clientHeight !== 0) {effectiveHeight = document.documentElement.clientHeight;}
   else {effectiveHeight = document.getElementsByTagName('body')[0].clientHeight;}

  $("#image_to_preview option, [id^=image_id] option, #get_products option, #wm_wmimage_id option, #manufacturer_id option, [id^=sliderimage_id] option").hover(function (e) {
   this.t = this.title;
   this.title = "";

   e = e || window.event;
   if (e.pageX || e.pageY) {
      posx = parseInt(e.pageX,10);
      posy = parseInt(e.pageY,10);
   }
   else if (e.clientX || e.clientY) {
      posx = parseInt(e.clientX + document.documentElement.scrollLeft,10);
      posy = parseInt(e.clientY + document.documentElement.scrollTop,10);
   }

   if (typeof window.pageYOffset === 'number') {scrolled = window.pageYOffset;}
   else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {scrolled = document.body.scrollTop;}
   else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {scrolled = document.documentElement.scrollTop;}
   if (this.t !== '') {
   $("body").append("<p id='preview'><img src='"+ this.t + "' >" + "</p>");
   $("#preview").css("top", (posy - scrolled < effectiveHeight/2 ? (posy - yOffset) : posy - yOffset - (this.t.substr(-7,3))) + "px").css("left",(posx + xOffset) + "px").fadeIn("fast");
        }
  },
  function() {
   this.title = this.t;
   $("#preview").remove();
  }
  );

  $("#image_to_preview option, [id^=image_id] option, #get_products option, #wm_wmimage_id option, #manufacturer_id option, [id^=sliderimage_id] option").mousemove(function(e){
   e = e || window.event;

   if (e.pageX || e.pageY) {
      posx = parseInt(e.pageX,10);
      posy = parseInt(e.pageY,10);
   }
   else if (e.clientX || e.clientY) {
      posx = parseInt(e.clientX + document.documentElement.scrollLeft,10);
      posy = parseInt(e.clientY + document.documentElement.scrollTop,10);
   }
   $("#preview").css("top",(posy - scrolled < effectiveHeight/2 ? (posy - yOffset) : posy - yOffset - (this.t.substr(-7,3))) + "px").css("left",(posx + xOffset) + "px");
  });
  }
