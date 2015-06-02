$(document).ready(function () {

	$('.vert-flexmenu .opener').click(function(){
		var el = $(this).next('.dd-section');
		var switcher = $(this);
		var wdth = $( window ).width();			
		if (wdth < 769) {
	        el.animate({
	            "height": "toggle"
	        }, 
	        500,
	        function(){
	        	if (el.is(':visible')) {
	                el.addClass("act");
	                switcher.addClass('opn');
	            } else {
	            	switcher.removeClass('opn');
	                el.removeClass("act");
	            }
	        });
		}
		return false;
	});

	var wdth = $( window ).width();	
	if (wdth > 768) {

		$( ".main-section-sublinks > li" ).hover(
		  function() {
		    $(this).find("ul").stop().slideDown("fast");
		  }, function() {
		    $(this).find("ul").stop().delay(100).slideUp("fast");
		  }
		);				

		$( ".v-flexmenuitem" ).hover(
	  	function() {
		    $(this).find('.submenu').css({"display":"block"}).addClass("showmenu");
		  }, function() {
			$(this).find('.submenu').delay(100).slideUp(0).removeClass("showmenu");
		  }
		);
	}

	// carousels

	var vm_rp = $(".v-right-section-products").data("pquant");
	if (vm_rp > 1) {
		$(".v-right-section-products").flexisel({
          pref: "vm-pr",
          visibleItems: 1,
          animationSpeed: 500,
          autoPlay: true,
          autoPlaySpeed: 3500,            
          pauseOnHover: true,
          enableResponsiveBreakpoints: false,
          clone : true
      });  
    }  

    /*var vm_rp = $(".v-bottom-section-links").data("manuquant");
    if (vm_rp > 5) {
		$(".v-bottom-section-links").flexisel({
          pref: "vm-man",
          visibleItems: 5,
          animationSpeed: 500,
          autoPlay: true,
          autoPlaySpeed: 3800,            
          pauseOnHover: true,
          enableResponsiveBreakpoints: false,
          clone : true
      });  
    } */   
   
	
});