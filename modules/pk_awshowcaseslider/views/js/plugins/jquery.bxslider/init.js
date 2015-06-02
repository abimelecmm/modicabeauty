$(document).ready(function(){

    var video = $(".videoframe").attr("src");

    var slider = $('#aw_slider').bxSlider_mod({   
        mode: aw_mode, 
        speed: aw_speed, 
        auto: aw_auto,
        pause: aw_pause,
        randomStart: aw_random,
        controls: aw_controls, 
        pager: aw_pager, 
        autoHover: aw_hover, 
        onSlideBefore: function() {
            $(".showcase-tooltips a").hide();                
        },
        onSlideAfter: function() {                   
           $(".showcase-tooltips a").fadeIn('500');
        }
  });
    
  $(".showcase-plus-anchor").mouseenter(function()
    {
        var y = (parseInt($(this).css('top').replace('px', '')) + (parseInt($(this).height()))/2);
        var x = (parseInt($(this).css('left').replace('px', '')) + (parseInt($(this).width()))/2);
        var content = $(this).html();           
        slider.animateTooltip(".bx-viewport", x, y, content);

    });
    $(".showcase-plus-anchor").mouseleave(function()
    {
        var y = (parseInt($(this).css('top').replace('px', '')) + (parseInt($(this).height()))/2);
        var x = (parseInt($(this).css('left').replace('px', '')) + (parseInt($(this).width()))/2);
        var content = $(this).html();
        slider.animateTooltip(".bx-viewport", x, y, content);
    });
    
});