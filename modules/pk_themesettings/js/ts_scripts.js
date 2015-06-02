$(document).ready(function(){

	$(".sortable").sortable({
		forcePlaceholderSize: true
			}).bind("sortupdate", function(e, ui) {
				var ids = ui.item.attr("id").split("_");
				var way = (ui.start_index < ui.end_index)? 1 : 0;
				var data = ids[0]+"[]=";

				$.each(e.target.children, function(index, element) {
					$(element).find("input[type=hidden]").val(index+1);
					data += "&"+ids[0]+"[]="+$(element).attr("id");
				});

				$.ajax({
					type: "POST",
					headers: { "cache-control": "no-cache" },
					async: false,
					url: tsDir + 'ajax.php',
					//url: currentIndex + "&token=" + token + "&" + "rand=" + new Date().getTime(),
					data: data + "&action=updatePositions&id_hook="+ids[0]+"&id_module="+ids[1]+"&way="+way,
					success: function(data) {
						start = 0;
						//console.log(data);
						$.each(e.target.children, function(index, element) {
							$(element).find(".positions").html(++start);
						});

						showSuccessMessage(update_success_msg);
					}
				});
			});

	$(".tabs .tab[id^=tab_menu]").click(function() {
        var curMenu=$(this);
        if (!$(this).hasClass("selected")) { 
	        $(".tabs .tab[id^=tab_menu]").removeClass("selected");
	        curMenu.addClass("selected");
	 
	        var index=curMenu.attr("id").split("tab_menu_")[1];

	        $(".curvedContainer .tabcontent").animate({ opacity: 0 }, 0).css("display","none");
	        $(".curvedContainer #tab_content_"+index).css("display","block").animate({ opacity: 1 }, 200);
	        $(".curvedContainer input[name=\'tab_number\']").removeAttr("checked");
	        $(".curvedContainer #tab_"+index).attr("checked", "checked");
	        
	    }
    });
	// remove the waste
	$("style#logofont_styles").remove();
	$("style#textfont_styles").remove();
	$("style#headingsfont_style").remove();
	$("style#subheadingsfont_style").remove(); // remove old <style> tag from <head>

	// ##########
	$('#logosize').change(function(){ // changing logo fonts
		var size = $("option:selected", this).val();
		$("#logofont_example").css({'fontSize': size+'px'});
	});
	$('#logolh').change(function(){ // changing logo fonts
		var size = $("option:selected", this).val();
		$("#logofont_example").css({'line-height': size+'px'});
	});
	$('#slogansize').change(function(){ // changing logo fonts
		var size = $("option:selected", this).val();
		$("#sloganfont_example").css({'fontSize': size+'px'});
	});
	$('#logofont').change(function(){ // changing logo fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#logofont_link').length < 1){
			$('head').append('<link id="logofont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#logofont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});		// put <link> tag to <head>	

		$("style#logofont_styles").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="logofont_styles" type="text/css">#logofont_example{ font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});
	$('#sloganfont').change(function(){ // changing logo fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#sloganfont_link').length < 1){
			$('head').append('<link id="sloganfont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#sloganfont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});		// put <link> tag to <head>	

		$("style#sloganfont_styles").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="sloganfont_styles" type="text/css">#sloganfont_example{ font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});

	$('#heading_font').change(function(){ // changing heading fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#headingsfont_link').length < 1){
			$('head').append('<link id="headingsfont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#headingsfont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});		// put <link> tag to <head>	

		$("style#headingsfont_style").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="headingsfont_style" type="text/css">#heading-example h5{ font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});	

	$('#subheading_font').change(function(){ // changing subheading fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#subheadingsfont_link').length < 1){
			$('head').append('<link id="subheadingsfont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#subheadingsfont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});		// put <link> tag to <head>	

		$("style#subheadingsfont_style").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="subheadingsfont_style" type="text/css">#subheading-example h6{ font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});	

	$('#text_font').change(function(){ // changing text fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#textfont_link').length < 1){
			$('head').append('<link id="textfont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#textfont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});		// put <link> tag to <head>	

		$("style#textfont_style").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="textfont_style" type="text/css">#text-example { font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});

	$('#price_font').change(function(){ // changing text fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#pricefont_link').length < 1){
			$('head').append('<link id="pricefont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#pricefont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});		// put <link> tag to <head>	

		$("style#pricefont_style").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="pricefont_style" type="text/css">#price-example { font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});

	$('#buttons_font').change(function(){ // changing logo fonts
	    var gFontVal = $("option:selected", this).val();
		var gFontName = gFontVal.split(':'); // get font name
		if ($('head').find('link#buttonsfont_link').length < 1){
			$('head').append('<link id="buttonsfont_link" rel="stylesheet" type="text/css" href="" />');
		}
		$('link#buttonsfont_link').attr({href:'//fonts.googleapis.com/css?family=' + gFontName});	// put <link> tag to <head>	

		$("style#buttonsfont_style").remove(); // remove old <style> tag from <head>
		$('head').append('<style id="buttonsfont_style" type="text/css">#page .button { font-family:' + gFontVal + ' !important; }</style>'); // add new <style>
	});	

	$('.preset-list').change(function(){ // changing text fonts
	    var num = $(this).val();
	    $("#preset-preview").removeClass().addClass("preset"+num);
	});

	$('#logo_color').bind('change', function () {
      $('#logofont_example').css('color', $(this).val());
    });	
    $('#slogan_color').bind('change', function () {
      $('#sloganfont_example').css('color', $(this).val());
    });

	//$('#back_color').bind('colorpicked', function () {
	 //   $(".cell").css({ backgroundColor: $(this).val() });
	//});
	$('#buttons_color').bind('change', function () {
	    $("#page .button").css('backgroundColor', $(this).val() );
	});
	$('#buttons_text_color').bind('change', function () {
	    $("#page .button").css({ color: $(this).val() });
	});
	$('#buttons_hover_color').bind('change', function () {
	    $("#page .button").hover(function() {
            $(this).css({ backgroundColor: $("#buttons_hover_color").val() });
        },function(){
  			$(this).css({ backgroundColor: $("#buttons_color").val() });
  		});
	});
	$('#buttons_hover_text_color').bind('change', function () {
	    $("#page .button").hover(function() {
            $(this).css({ color: $("#buttons_hover_text_color").val() });
        },function(){
  			$(this).css({ color: $("#buttons_text_color").val() });
  		});
	});
	$('#headings_color').bind('change', function () {
	    $("#heading-example h5").css({ color: $(this).val() });
	});
	$('#subheadings_color').bind('change', function () {
	    $("#subheading-example h6").css({ color: $(this).val() });
	});
	$('#text_color').bind('change', function () {
	    $("#text-example").css({ color: $(this).val() });
	});
	$('#links_color').bind('change', function () {
	    $("#link-example a").css({ color: $(this).val() });
	});
	$('#hover_links_color').bind('change', function () {
		$("#link-example a").hover(function() {
            $(this).css({ color: $("#hover_links_color").val() });
        },function(){
  			$(this).css({ color: $("#links_color").val() });
  		});
	});

	$("#logo_type_image").click(function() {
		if($("input[name=logo_type]:checked").val() == 0) {
			$("#logotype-text").removeClass("display").addClass("hide");
			$("#logotype-image").removeClass("hide").addClass("display");
			$(".logo-container img").removeClass("hide").addClass("display");
			$("#logofont_example").removeClass("display").addClass("hide");
			$("#sloganfont_example").removeClass("display").addClass("hide");
		}
	});
	$("#logo_type_text").click(function() {
		if($("input[name=logo_type]:checked").val() == 1) {
			$("#logotype-text").removeClass("hide").addClass("display");
			$("#logotype-image").removeClass("display").addClass("hide");
			$(".logo-container img").removeClass("display").addClass("hide");
			$("#logofont_example").removeClass("hide").addClass("display");
			$("#sloganfont_example").removeClass("hide").addClass("display");
		} 
	});
	$("#logo_text").live("keyup", function(event, ui) { // button changer		
		var txt = $(this).val();
        $(this).closest('#logotype-text').find('#logofont_example').text(txt);
    });
    $("#slogan").live("keyup", function(event, ui) { // button changer		
		var txt = $(this).val();
        $(this).closest('#logotype-text').find('#sloganfont_example').text(txt);
    });
    $("#slogan").focus(function() {
	  $(this).animate({ width: "350px" }, "slow");
	});
	$("#slogan").blur(function() {
	  $(this).animate({ width: "127px" }, "slow");
	});	
	$(".logo_top").click(function() {
		var mar = parseInt($(".logo-container").css('top').replace("px", ""));
		$(".logo-container").css('top', (mar+1)+'px');
		$("#logo_top").val((mar+1));
	});
	$(".logo_bottom").click(function() {
		var mar = parseInt($(".logo-container").css('top').replace("px", ""));
		$(".logo-container").css('top', (mar-1)+'px');
		$("#logo_top").val((mar-1));
	});
	$(".logo_left").click(function() {
		var mar = parseInt($(".logo-container").css('left').replace("px", ""));
		$(".logo-container").css('left', (mar+1)+'px');
		$("#logo_left").val((mar+1));
	});
	$(".logo_right").click(function() {
		var mar = parseInt($(".logo-container").css('left').replace("px", ""));		
		$(".logo-container").css('left', (mar-1)+'px');
		$("#logo_left").val((mar-1));
	});

	$("#view_list_lbl").click(function() {
		if($("input[name=view]:checked").val() == 0) {
			$("#images").removeClass("display").addClass("hide");
		}
	});
	$("#view_grid_lbl").click(function() {
		if($("input[name=view]:checked").val() == 1) {
			$("#images").removeClass("hide").addClass("display");	
		} 
	});

	$(".confirm").delay(2000).fadeOut(1000);

	$(".swt_on").click(function() {
		$(this).parent().find('.swt_container').removeClass("hide");
	});
	$(".swt_off").click(function() {
		$(this).parent().find('.swt_container').addClass("hide");
	});	

	/*	ui switcher	*/

    $(".cb-enable").click(function(){
    	$(this).closest(".switch").removeClass('mod-disabled');
        var parent = $(this).parents(".switch");
        $(".cb-disable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", true);
    });
    $(".cb-disable").click(function(){
    	$(this).closest(".switch").addClass('mod-disabled');
        var parent = $(this).parents(".switch");
        $(".cb-enable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", false);
    });
    $(".site-structure .cb-enable").click(function(){
        var parent = $(this).parents(".switch");
        $(".cb-disable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", true);
        $(this).closest(".module-wrapper").removeClass("hidden-module");
    });
    $(".site-structure .cb-disable").click(function(){
        var parent = $(this).parents(".switch");
        $(".cb-enable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", false);
        $(this).closest(".module-wrapper").addClass("hidden-module");
    });
    $(".site-structure .homepage_column .cb-enable").click(function(){
        var parent = $(this).parents(".switch");
        $(".cb-disable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", true);
        $(this).closest(".tabcontent").addClass("columns");
    });
    $(".site-structure .homepage_column .cb-disable").click(function(){
        var parent = $(this).parents(".switch");
        $(".cb-enable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", false);
        $(this).closest(".tabcontent").removeClass("columns");
    });
    $("#coordinates .cb-enable").click(function(){
        var parent = $(this).parents(".switch");
        $(".cb-disable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", true);
        $(this).closest(".tabcontent").addClass("columns");
        $("#coordinates-info").removeClass("hide").addClass("display");
    });
    $("#scroll_cat_icons_on").click(function(){
        var parent = $(this).parents(".switch");
        var form = $(this).closest(".form").find('div.switch');
        $(".cb-disable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(form).removeClass("hide");
        $(".checkbox",parent).attr("checked", true);
    });
    $("#scroll_cat_icons_off").click(function(){
        var parent = $(this).parents(".switch");
        var form = $(this).closest(".form").find('div.switch');
        $(".cb-enable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(form).addClass("hide");
        $(".checkbox",parent).attr("checked", false);
    });
    $("#coordinates .cb-disable").click(function(){
        var parent = $(this).parents(".switch");
        $(".cb-enable",parent).removeClass("sel");
        $(this).addClass("sel");
        $(".checkbox",parent).attr("checked", false);
        $(this).closest(".tabcontent").removeClass("columns");
		$("#coordinates-info").removeClass("display").addClass("hide");
    });
    $("#location").live("keyup", function(event, ui) { // button changer		
		var name = $(this).val();       
        $.getJSON("//maps.google.com/maps/api/geocode/json?address="+name+"&sensor=false",function(data) {
			var location = data.results[0].geometry.location;
		    $("#coordinates").find('.lat').text(location.lat);
		    $("#coordinates").find('.lon').text(location.lng);
		    $("#location_lat").val(location.lat);
		    $("#location_lng").val(location.lng);
		});
    });
    $(".show_the_list").click(function(){
    	$(".email-list").toggle(function(){
    		if ($(".show_the_list").hasClass("shown")) { 
	    		$(".show_the_list").removeClass("shown");
	    	} else {
	    		$(".show_the_list").addClass("shown");
	    	}
    	});	    	
    });
    $(".module-icon").hover(function() {
            $(this).parent().find(".module-preview").removeClass('hide');
        },function(){
  			$(this).parent().find(".module-preview").addClass('hide');
  		});
    $(".preset-image").click(function(){
    	$('.preset-image').removeClass('selected');
    	$(this).addClass('selected');
    }); 
    $("#scroll_cat_icons_off").click(function(){
    	$('.preset-image').removeClass('selected');
    	$(this).addClass('selected');
    });

});