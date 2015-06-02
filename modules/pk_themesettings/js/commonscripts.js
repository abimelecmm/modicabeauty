(function($){

    /**
     * Copyright 2012, Digital Fusion
     * Licensed under the MIT license.
     * http://teamdf.com/jquery-plugins/license/
     *
     * @author Sam Sehnert
     * @desc A small plugin that checks whether elements are within
     *       the user visible viewport of a web browser.
     *       only accounts for vertical position, not horizontal.
     */
    var $w = $(window);
    $.fn.visible = function(partial,hidden,direction){

        if (this.length < 1)
            return;

        var $t        = this.length > 1 ? this.eq(0) : this,
            t         = $t.get(0),
            vpWidth   = $w.width(),
            vpHeight  = $w.height(),
            direction = (direction) ? direction : 'both',
            clientSize = hidden === true ? t.offsetWidth * t.offsetHeight : true;

        if (typeof t.getBoundingClientRect === 'function'){

            // Use this native browser method, if available.
            var rec = t.getBoundingClientRect(),
                tViz = rec.top    >= 0 && rec.top    <  vpHeight,
                bViz = rec.bottom >  0 && rec.bottom <= vpHeight,
                lViz = rec.left   >= 0 && rec.left   <  vpWidth,
                rViz = rec.right  >  0 && rec.right  <= vpWidth,
                vVisible   = partial ? tViz || bViz : tViz && bViz,
                hVisible   = partial ? lViz || rViz : lViz && rViz;

            if(direction === 'both')
                return clientSize && vVisible && hVisible;
            else if(direction === 'vertical')
                return clientSize && vVisible;
            else if(direction === 'horizontal')
                return clientSize && hVisible;
        } else {

            var viewTop         = $w.scrollTop(),
                viewBottom      = viewTop + vpHeight,
                viewLeft        = $w.scrollLeft(),
                viewRight       = viewLeft + vpWidth,
                offset          = $t.offset(),
                _top            = offset.top,
                _bottom         = _top + $t.height(),
                _left           = offset.left,
                _right          = _left + $t.width(),
                compareTop      = partial === true ? _bottom : _top,
                compareBottom   = partial === true ? _top : _bottom,
                compareLeft     = partial === true ? _right : _left,
                compareRight    = partial === true ? _left : _right;

            if(direction === 'both')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop)) && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
            else if(direction === 'vertical')
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
            else if(direction === 'horizontal')
                return !!clientSize && ((compareRight <= viewRight) && (compareLeft >= viewLeft));
        }
    };

})(jQuery);

/*!
 * jQuery Simple Cookie Notice Plugin v1.1
 *  *
 * Copyright 2013, Periscopix
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */

(function( $ ) {
  $.fn.simpleCookieNotice = function(options) {

    var settings = $.extend( {
          setImpDate: 'May 25, 2012',
          setURL : /#/,
          fadeInTime: 300,
          showTime: 7500,
          fadeOutTime: 3000,
          cookieText: 'We set cookies by default. For more information ',
          setCSS: {'width':'280px','padding':'10px','position': 'absolute', 'bottom': '10px', 'right': '10px', 'background': '#000', 'color': '#fff','display': 'none', 'z-index': '99'},
          cookiePageURL: '#',
        }, options);
    
    var firstPageCheck = settings.setURL.test(document.referrer);
    var impDate = parseFloat(Date.parse(settings.setImpDate).toString().slice(0,10));
    var nowDate = new Date();
    nowDate = parseFloat(Date.parse(nowDate).toString().slice(0,10));
    var prevSession 
    
    if($.cookie('__utma') != null){
        var arrCookie = $.cookie('__utma').split('.');
        if(arrCookie[5] == 1 && nowDate - arrCookie[3] < 5){
            prevSession = 0;
        } else{prevSession = arrCookie[3];}
    }
    else{prevSession = 0}

    if(firstPageCheck == false){        
        //if(impDate > prevSession){
            cookInfo();
        //}
    }
    
    function cookInfo(){
        $('body').append('<div id="cookieInfo"><div class="indent">' + settings.cookieText + ' <a class="accept_cookies button" href="#">'+cookie_accept+'</a></div></div>');
        $('#cookieInfo').css(settings.setCSS);
        $('#cookieInfo').slideDown(settings.fadeInTime);        
        //$('#cookieInfo').delay(settings.showTime).fadeOut(settings.fadeOutTime);
    }
    
  };
  
})( jQuery );

// equal height plugin
(function($) {
    $.fn.equalHeights = function() {
        var maxHeight = 0,
            $this = $(this);
        $this.each( function() {
            var height = $(this).innerHeight();
            if ( height > maxHeight ) { maxHeight = height; }
        });
        return $this.css('height', maxHeight);
    };
    // auto-initialize plugin
    $('[data-equal]').each(function(){
        var $this = $(this),
            target = $this.data('equal');
        $this.find(target).equalHeights();
    });
})(jQuery);

//plugin home http://itsmeara.com/jquery/atooltip/
 (function($) {
    $.fn.aToolTip = function(options) {
        /**
            setup default settings
        */
        var defaults = {
            // no need to change/override
            closeTipBtn: 'aToolTipCloseBtn',
            toolTipId: 'aToolTip',
            // ok to override
            fixed: false,
            clickIt: false,
            inSpeed: 200,
            outSpeed: 0,
            tipContent: '',
            toolTipClass: 'defaultTheme',
            xOffset: 5,
            yOffset: 5,
            onShow: null,
            onHide: null
        },
        // This makes it so the users custom options overrides the default ones
        settings = $.extend({}, defaults, options);
    
        return this.each(function() {
            var obj = $(this);
            /**
                Decide weather to use a title attr as the tooltip content
            */
            if(obj.attr('title')){
                // set the tooltip content/text to be the obj title attribute
                var tipContent = obj.attr('title');  
            } else {
                // if no title attribute set it to the tipContent option in settings
                var tipContent = settings.tipContent;
            }
            
            /**
                Build the markup for aToolTip
            */
            var buildaToolTip = function(){
                $('body').append("<div id='"+settings.toolTipId+"' class='"+settings.toolTipClass+"'><p class='aToolTipContent'>"+tipContent+"</p></div>");
                
                if(tipContent && settings.clickIt){
                    $('#'+settings.toolTipId+' p.aToolTipContent')
                    .append("<a id='"+settings.closeTipBtn+"' href='#' alt='close'>close</a>");
                }
            },
            /**
                Position aToolTip
            */
            positionaToolTip = function(){                
                var postop = obj.offset().top - $('#'+settings.toolTipId).outerHeight() - settings.yOffset;
                if (postop < 0)
                    postop = obj.offset().top + $('#'+settings.toolTipId).outerHeight()-10;
                var posleft = obj.offset().left + obj.outerWidth() + settings.xOffset;
                $('#'+settings.toolTipId).css({                
                    top: postop + 'px',
                    left: posleft + 'px'
                })
                .stop().fadeIn(settings.inSpeed, function(){
                    if ($.isFunction(settings.onShow)){
                        settings.onShow(obj);
                    }
                });             
            },
            /**
                Remove aToolTip
            */
            removeaToolTip = function(){
                // Fade out
                $('#'+settings.toolTipId).stop().fadeOut(settings.outSpeed, function(){
                    $(this).remove();
                    if($.isFunction(settings.onHide)){
                        settings.onHide(obj);
                    }
                });             
            };
            
            /**
                Decide what kind of tooltips to display
            */
            // Regular aToolTip
            if(tipContent && !settings.clickIt){    
                // Activate on hover    
                obj.hover(function(){
                    // remove already existing tooltip
                    $('#'+settings.toolTipId).remove();
                    obj.attr({title: ''});
                    buildaToolTip();
                    positionaToolTip();
                }, function(){ 
                    removeaToolTip();
                }); 
            }           
            
            // Click activated aToolTip
            if(tipContent && settings.clickIt){
                // Activate on click    
                obj.click(function(el){
                    // remove already existing tooltip
                    $('#'+settings.toolTipId).remove();
                    obj.attr({title: ''});
                    buildaToolTip();
                    positionaToolTip();
                    // Click to close tooltip
                    $('#'+settings.closeTipBtn).click(function(){
                        removeaToolTip();
                        return false;
                    });      
                    return false;           
                });
            }
            
            // Follow mouse if enabled
            if(!settings.fixed && !settings.clickIt){
                obj.mousemove(function(el){
                    var postop = el.pageY - $('#'+settings.toolTipId).outerHeight() - settings.yOffset;
                    if (postop < 0)
                        postop = el.pageY + $('#'+settings.toolTipId).outerHeight()-10;
                    var posleft = el.pageX + settings.xOffset;
                    $('#'+settings.toolTipId).css({
                        top: postop,
                        left: posleft
                    });
                });         
            }           
          
        }); // END: return this
    };
})(jQuery);

/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function (key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};


$(window).load(function () {

    var is_touch_device = 'ontouchstart' in document.documentElement;
    var wWidth = $(window).width();
    //console.log(wWidth);
    var currentBreakpoint; // default's to blank so it's always analysed on first load
    var didResize  = true; // default's to true so it's always analysed on first load
    // on window resize, set the didResize to true
    $(window).resize(function() { didResize = true; });

    // every 1/2 second, check if the browser was resized
    // we throttled this because some browsers fire the resize even continuously during resize
    // that causes excessive processing, this helps limit that
    setInterval(function() {
        if(didResize) {

            didResize = false;
            var newBreakpoint = $(window).width();

            if (newBreakpoint > 1199) 
                newBreakpoint = "breakpoint_1";
            else if ((newBreakpoint <= 1199) && (newBreakpoint >= 992)) 
                newBreakpoint = "breakpoint_2";
            else if ((newBreakpoint <= 991) && (newBreakpoint >= 768)) 
                newBreakpoint = "breakpoint_3";
            else if ((newBreakpoint <= 767) && (newBreakpoint >= 400)) 
                newBreakpoint = "breakpoint_4";
            else if (newBreakpoint <= 399) 
                newBreakpoint = "breakpoint_5";

            if (currentBreakpoint != newBreakpoint) {            

                if (newBreakpoint === 'breakpoint_1') {// min-width: 1200px
                    currentBreakpoint = 'breakpoint_1';

                    if (page_name == "product") { // PRODUCT PAGE ONLY
                        var blockHeight = $("#image-block").height();
                        $("#views_block").height(blockHeight);                     
                    }
                    $("body").addClass("bigscr").removeClass("smallscr");
                    $("#footer .dd_container_mobile").removeAttr("style");
                    $(".flexmenu_ul").removeAttr("style");

                }            
                if (newBreakpoint === 'breakpoint_2') {//max-width: 1199px
                    currentBreakpoint = 'breakpoint_2';

                    if (page_name == "product") { // PRODUCT PAGE ONLY
                        var blockHeight = $("#image-block").height();
                        $("#views_block").height(blockHeight);           
                    }
                    $("body").addClass("bigscr").removeClass("smallscr");
                    $("#footer .dd_container_mobile").removeAttr("style");
                    $(".flexmenu_ul").removeAttr("style");
                }               
                if (newBreakpoint === 'breakpoint_3') {// max-width: 991px
                    currentBreakpoint = 'breakpoint_3';                       

                    if (page_name == "product") { // PRODUCT PAGE ONLY
                        var blockHeight = $("#image-block").height();
                        $("#views_block").height(blockHeight);  
                    }
                    $("body").addClass("bigscr").removeClass("smallscr");
                    $("#footer .dd_container_mobile").removeAttr("style");
                    $(".flexmenu_ul").removeAttr("style");

                }               
                if (newBreakpoint === 'breakpoint_4') {//max-width: 768px
                    currentBreakpoint = 'breakpoint_4';
                    $("body").addClass("smallscr").removeClass("bigscr");
                    
                }
                if (newBreakpoint === 'breakpoint_5') {//max-width: 399px
                    currentBreakpoint = 'breakpoint_5';
                    $("body").addClass("smallscr").removeClass("bigscr");
                   
                }   
            }
        }
      }, 500);                                   
    
    if (page_name == "product") { // PRODUCT PAGE ONLY
        if (wWidth >= 768) {
            var blockHeight = $("#image-block").height();
            $("#views_block").height(blockHeight);
        }
        $(".no-radiobuttons li").click(function(){
            $(".no-radiobuttons").find("li").removeClass("activeSize");
            $(this).addClass("activeSize");
        });
    }

         
    $(".page-product-box .page-product-heading").click(function(){
        $('.page-product-box').addClass('d-hidden');
        $(this).parent().removeClass('d-hidden');
    });
    
    
    $('.dd_el').hover(
        function () {$(this).find('.dd_container').stop().slideDown(500, 'easeOutQuint');}, 
        function () {$(this).find('.dd_container').stop().slideUp(200, 'easeOutQuint');
    });
    var w = $(window).width();
        //if (w < 729) {
            $('.smallscr .dd_el_mobile').live('click', function() {
                el = $(this).parent();
                if (el.hasClass('active')) {
                    $(this).parent().removeClass('active');
                    $(this).parent().find('.dd_container_mobile').stop().slideUp(200, 'easeOutQuint');
                } else {
                    $(this).parent().addClass('active');
                    $(this).parent().find('.dd_container_mobile').stop().slideDown(500, 'easeOutQuint'); 
                }
            });
            /*$('.smallscr .dd_el_mobile').toggle(
                function () {
                    $(this).parent().addClass('active')
                    $(this).parent().find('.dd_container_mobile').stop().slideDown(500, 'easeOutQuint');}, 
                function () {
                    $(this).parent().removeClass('active');
                    $(this).parent().find('.dd_container_mobile').stop().slideUp(200, 'easeOutQuint');
            });   */
        //}
    
    // compare (product-list)
    $(".product_compare label").live('click', function() {    
	    $(this).parent().toggleClass('active', $(this).attr('checked'));
	});

	// grid/list view. change DOM
//    if (listing_view_buttons == true) {
        $("#view_grid").click(function() {
             $("#view_list").removeClass("active");
             $(this).addClass("active");
             $("#listing_view").addClass("view_grid").removeClass("view_list"); // set class            
             $.cookie("listingView", "view_grid"); // set cookie
             $("#listing_view").animate({opacity: "0"}, 0);// set class         
             $("#listing_view").delay(200).animate({opacity: "1"}, 300);// set class      
        });
        $("#view_list").click(function() {
            $("#view_grid").removeClass("active");
             $(this).addClass("active");
             $("#listing_view").addClass("view_list").removeClass("view_grid"); // set class           
             $.cookie("listingView", "view_list"); // set cookie
             $("#listing_view").animate({opacity: "0"}, 0);// set class         
             $("#listing_view").delay(200).animate({opacity: "1"}, 300);// set class    
        });
        if ($.cookie("listingView")) { // read cookie
            var listingView = $.cookie("listingView");
            $('#listing_view').removeClass("view_list view_grid").addClass(listingView); // set product view class
            $(".view_btn").removeClass("act_btn"); // set class
            $("#"+listingView).addClass("act_btn");           
        }
    //}  

    $('#search_block_top').hover(
        function () {
            $(this).addClass("hvr");
            $(".ac_results").removeClass('hidden');
        }, 
        function () {
            $(this).delay(200).removeClass("hvr");
    });
    
    $(document).on("mouseover", ".ac_results", function(e) {
        $('#search_block_top').addClass("hvr");
    });
    $(document).on("mouseleave", ".ac_results", function(e) {
        $('#search_block_top').delay(200).removeClass("hvr");
        $(this).addClass('hidden');
    });
    /* add/remove product to/from favorite */


    /*  CATEGORY PAGE       */
    //$("#product_list .product_like a").click(function(){        
    $(".registered .product_like a").live('click', function() {
        var fav_url = favorite_products_url_add
        if ($(this).hasClass("remfav"))
            var fav_url = favorite_products_url_remove
        var fav_pid = $(this).closest('.ajax_block_product').data("productid"); // get product id

        $.ajax({
            url: fav_url,
            type: "POST",
            data: {
              "id_product": fav_pid
            },
            success: function(result){                
              if (result == '0')
              {
                var num = parseInt($(".favQty").text());
                if (fav_url == favorite_products_url_add) {
                    $(".product-list").find("[data-productid='" + fav_pid + "'] .product_like").addClass("active");
                    $(".favQty").text(num+1);
                    $(".favoritelist").find("li.no-products").remove();
                    $(".favoritelist").css({"height":"auto"});
                    addFavoriteProduct('.p'+fav_pid+' .image-cover');
                } else if (fav_url == favorite_products_url_remove) {
                    $(".product-list").find("[data-productid='" + fav_pid + "'] .product_like").removeClass("active");
                    $(".favQty").text(num-1);
                }                
              }
            }
        });
    });
    /*      PRODUCT PAGE  ADD TO FAVORITES    */
    $(".registered#product .product_like a").click(function(){        
        if ($(this).hasClass("addfav")) { 
            var fn = "add";
            var fav_url = favorite_products_url_add
        } else { 
            var fn = "rem";
            var fav_url = favorite_products_url_remove
        };     
        console.log(fav_url);
        $.ajax({
            url: fav_url,
            type: "POST",
            data: {
              "id_product": id_product
            },
            success: function(result){                
                console.log(result);
              if (result == '0')
              {                
                var num = parseInt($(".favQty").text());
                if (fn == "add") {
                    $("#product .product_like").addClass("active");
                    $(".favQty").text(num+1);
                    $(".favoritelist li").each(function( index ) {
                        if ($(this).hasClass("no-products")) {
                            $(this).remove();                            
                        }
                    });
                    $(".favoritelist").css({"height":"auto"});
                    addFavoriteProduct("#bigpic");
                } else if (fn == "rem") {
                    $("#product .product_like").removeClass("active");
                    $(".favQty").text(num-1);
                }
              }
            }
        });
        return false;
    });
    $(".guest .product_like a").live('click', function() {
        if (!!$.prototype.fancybox)
            $.fancybox.open([
                {
                    type: 'inline',
                    autoScale: true,
                    minHeight: 30,
                    content: '<p class="fancybox-error">' + pleaselogin + '</p>'
                }
            ], {
                padding: 0
            });
        else
            alert("Product has been added");
        return false;
    });

    // #menu
    if (sticky == true) {
        if ( $('.flexmenu')[0] && ($(window).width() > 980)) {
            var stickyNavTop = $('.flexmenu').offset();
            var stickyNav = function(){
                var scrollTop = $(window).scrollTop();             
                if (scrollTop > stickyNavTop.top) { 
                    $('.flexmenu').addClass('sticky');
                } else {
                    $('.flexmenu').removeClass('sticky'); 
                }
            };

            stickyNav();
            $(window).scroll(function() {
                stickyNav();
            });
        }
    }

    // hide content until after title animation
    if (load_effect == true)
        jQuery('.no-touch .load-animate').waypoint({
            triggerOnce: true,
            offset: '70%',
            handler: function() {
                jQuery(this).addClass('animated fadeInUp');
            }
        });

    // #scroll
    if (scrolltotop == true) {
        if ( $('#scrollTop')[0] ) {

            var wind_w = $(window).width();
            var cont_w = $(".page_width").width();
            var button_right = ((wind_w - cont_w)/2)-50;
            $("#scrollTop").css({"right": button_right});

            $(window).scroll(function () {
                var position = $("#scrollTop").offset();


                //$("#scrollTop").text(position.top);
                if (position.top < 800) {
                    $("#scrollTop").fadeOut(600);
                } else {
                    $("#scrollTop").fadeIn(600);
                }
            });
            $("#scrollTop a").click(function(){
                $("html, body").animate({ scrollTop: 0 }, "slow");
                return false;
            });
        }
    }

    // #tooltip
    if (tooltips == true)
        $(function(){$('.no-touch [title]').aToolTip();});

    // #cookie low

    if (use_cookies == true) {    
        if (!$.cookie("accept_cookies")) {            
            $.fn.simpleCookieNotice({
                setImpDate: 'August 02, 2012',
                setURL: /http:\/\/www\.yourdomain\.co\.uk/,
                cookiePageURL: baseUri+'?id_cms=4&controller=cms&id_lang=1',
                fadeInTime: 1500,
                showTime: 10000,
                fadeOutTime:2500,
                cookieText:cookie_text+' <a target="_blank" href="'+cookie_page+'">'+cookie_link+'</a>. ',
                setCSS:{ 'width':'100%','padding':'9px','position': 'absolute','position': 'fixed','bottom': '0px', 'left': '0px', 'background': '#fff', 'color': '#f00','display': 'none', 'opacity': '0.9', 'line-height': '22px'} });
            $(".accept_cookies").click(function(){
                $.cookie("accept_cookies", 1, { expires: 7 });
                $("#cookieInfo").slideUp('fast');
                return false;
            });
        }
    }

    function addFavoriteProduct(e) {
        var $element = $(e);
        if (!$element.length)
            var $element = $('.product_like');
        var $picture = $element.clone();
        var content = '<li class="favoriteproduct clearfix"><a href="#" class="favProdImage"><img src="'+$picture[0].src+'" alt=""/></a><div class="text_desc"><a href="#">'+$picture[0].alt+'</a></div></li>';
        $('.favoritelist ul').append(content);
    }
    // wishlist button    
    $(".registered #wishlist_button").click(function(){
        if (!$(this).hasClass("active")) { 
            $(this).addClass("active");
        }
    });     

    /* product page tabs */   

    /* order page tabs */
    $(".shipping-and-taxes-tab").click(function(){    
        $('.additional-cart-tabs div').removeClass('active');
        $(this).addClass("active");        
        $(".shipping-and-taxed-content").show('fast');
        $(".vouchers-content").hide('fast');
    });
    $(".vouchers-tab").click(function(){    
        $('.additional-cart-tabs div').removeClass('active');
        $(this).addClass("active");        
        $(".shipping-and-taxed-content").hide('fast');
        $(".vouchers-content").show('fast');
    });

    /* product page*/
    $(".tab-titles h3").click(function(){
        $(".tab-titles h3").removeClass("active-tab");
        $(this).addClass("active-tab");
        var num = $(this).data("title");
        $("#pb-left-column section").removeClass("active-section");
        $("#pb-left-column").find("[data-section='" + num + "']").addClass("active-section");
    });
    /* end product page*/
     
    $(function(){
        $('.sections-titles .page-product-heading').click(function(){
            $(".sections-titles h3").removeClass("active");
            $(this).addClass("active");
            var labelId = $(this).data("tab-label");            
            $(".sections").find("[data-tab]").fadeOut(200, function () {                                
                $(this).addClass("d-hidden");                
            });            
            $(".sections").find('[data-tab='+labelId+']').delay(200).fadeIn(400, function () {                
                $(this).removeClass('d-hidden');
            });
        });
    });

    $(".section-switcher").live('click', function() {
        var id = $(this).data("switcher");
        $(".section-"+id).animate({
            "height": "toggle"
        }, 
        500,
        function(){
            if ($('.section-'+id).is(':visible')) {
                $('.switcher-'+id).addClass("act");
            } else {
                $('.switcher-'+id).removeClass("act");
            }
        });
    });

    $(".accordionButton").click(function(){
        var accid = $(this).data("tab-acc");
        var th = $(".tab-slider-wrapper").find('[data-acc='+accid+']');
        if ($(th).hasClass("show")) {
            $(th).removeClass("show");
        } else {
            $(".accordionContent").removeClass("activeCarousel");
            $(".tab-slider-wrapper").find('[data-acc='+accid+']').addClass("activeCarousel");
        }
    });

    // wishlist
    $(".product_wishlist").click(function(){

        var id_product = $(this).closest('li').data("productid");
        var addtowish = WishlistCart('wishlist_block_list', "add", id_product, false, 1, ".image-cover");
        var num = parseInt($(".wlQty").text());
        if ($('.wlQty')[0])
            $(".wlQty").text(num+1);
        return false;

    });

    $(".product_wishlist.remove").click(function(){

        var id_product = $(this).closest('li').data("productid");
        var addtowish = WishlistCart('wishlist_block_list', "delete", id_product, false, 1, ".image-cover");
        var num = parseInt($(".wlQty").text());
        if ($('.wlQty')[0])
            $(".wlQty").text(num-1);
        return false;

    });

    // TO DO: ajax clear tpl cache
    function clearCache(module_name) {
        $.ajax({
            type: "POST",
            headers: { "cache-control": "no-cache" },
            async: false,
            url: tsDir + 'ajax.php',
            data: "module="+module_name,
            success: function(data) {
                
            }
        });
    }

});