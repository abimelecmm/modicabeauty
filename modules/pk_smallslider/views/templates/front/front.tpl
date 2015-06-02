{if $page_name != 'index'}
    {if $minicSlider.options.front == 1}
            <!-- MINIC SLIDER -->
        {include file="{$minicSlider.tpl}"}
    {else}
        <!-- MINIC SLIDER -->
        {include file="{$minicSlider.tpl}"}
    {/if} 

<script type="text/javascript">
$(window).load(function() {
    $('#minicslider_nivo').nivoSlider({
        effect: '{if $minicSlider.options.current != ''}{$minicSlider.options.current}{else}random{/if}', 
        slices: {if $minicSlider.options.slices != ''}{$minicSlider.options.slices}{else}6{/if}, 
        boxCols: {if $minicSlider.options.slices != ''}{$minicSlider.options.cols}{else}3{/if}, 
        boxRows: {if $minicSlider.options.rows != ''}{$minicSlider.options.rows}{else}3{/if}, 
        animSpeed: {if $minicSlider.options.speed != ''}{$minicSlider.options.speed}{else}500{/if}, 
        pauseTime: {if $minicSlider.options.pause != ''}{$minicSlider.options.pause}{else}3000{/if}, 
        startSlide: {if $minicSlider.options.startSlide != ''}{$minicSlider.options.startSlide}{else}0{/if},
        directionNav: false, 
        controlNav: {if $minicSlider.options.control == 1}true{else}false{/if}, 
        controlNavThumbs: false,
        pauseOnHover: {if $minicSlider.options.hover == 1}true{else}false{/if}, 
        manualAdvance: {if $minicSlider.options.manual == 1}true{else}false{/if}, 
        prevText: '', 
        nextText: '', 
        randomStart: {if $minicSlider.options.random == 1}true{else}false{/if}
    });
});
</script>   
<div class="clearfix"></div>
{/if}