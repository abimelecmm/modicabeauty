<div id="blocksliderpluspromo" class="homemodule load-animate">
    <div id="promo_show">
        <div class="promoborder">
            <div id="camera_wrap">                
                {foreach from=$slides.$def_iso item=image name=singleimage}              
                    <div data-src="{$minicSlider.path.images}{$image.image}" {if $minicSlider.options.thumbnail == 1}data-thumb="{$minicSlider.path.thumbs}{$image.image}"{/if} data-video="hide">                                    
                        {if $image.caption != ''}
                            <div class="camera_caption">{$image.caption}</div>                                    
                        {/if}
                        <div class="button_container smooth02 item_{$smarty.foreach.singleimage.index}">
                            {if $image.url != ''}
                            <a href="{$image.url}" class="button_small smooth02 lmromancaps dib" {if $image.target == 1}target="_blank"{/if}>{l s='View' mod='pk_promominicslider'}</a>
                            {/if}
                        </div>                
                    </div>                                    
                {/foreach}
            </div>
        </div>
    </div>
    <div class="promo_section">
        {if $minicSlider.promo_products}
            <ul class="products clearfix">
                {foreach from=$minicSlider.promo_products item='product' key='k' name='specProducts'}
                {if $smarty.foreach.specProducts.index < 4}  
                {if $minicSlider.options.price_reduction == 1}
                    {assign var="price" value=$minicSlider.promo_products.$k.price}
                {else}
                    {assign var="price" value=$minicSlider.promo_products.$k.price_without_reduction}
                {/if}
                <li class="smooth02 {if $smarty.foreach.specProducts.index%2}odd {/if}{if $smarty.foreach.specProducts.index<2}top_item{/if}{if $minicSlider.options.img_view == 0} full_height{/if}">
                <div class="promoborder">
                    <div class="indent">
                        <a href="{$minicSlider.promo_products.$k.link}" class="imgLink">
                            <img src="{$link->getImageLink($minicSlider.promo_products.$k.link_rewrite, $minicSlider.promo_products.$k.id_image, 'large_'|cat:$cookie->img_name)}" alt="{$minicSlider.promo_products.$k.legend|escape:html:'UTF-8'}" title="{$minicSlider.promo_products.$k.name|escape:html:'UTF-8'}" />
                        </a>
                        <div class="productInfo smooth02 {if !isset($minicSlider.promo_products.$k.specific_prices.price)}not_special{/if}">
                            <div class="wrap"></div>
                            <div class="info smooth02">
                                <div class="clearfix">
                                    <span class="manufacturer_name trajan ellipsis">{if isset($minicSlider.promo_products.$k.manufacturer_name)}{$minicSlider.promo_products.$k.manufacturer_name|truncate:30}{/if}</span>
                                    {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                    <span class="reduction dib trajan">                                
                                        {if isset($minicSlider.promo_products.$k.specific_prices.price)}
                                            {if $minicSlider.promo_products.$k.specific_prices.reduction_type == "amount"}
                                                -{convertPrice price=$product.specific_prices.reduction}
                                            {else}
                                                -{$minicSlider.promo_products.$k.specific_prices.reduction*100|round:2}%        
                                            {/if}
                                        {else}
                                            {displayPrice price=$price}
                                        {/if}
                                    </span>
                                    {/if}
                                    <span class="name ellipsis">{$minicSlider.promo_products.$k.name|escape:html:'UTF-8'|truncate:30:'...':true}</span>
                                    <a class="button lmromandemi" href="{$minicSlider.promo_products.$k.link}">{l s='Buy now!' mod='pk_promominicslider'}</a>
                                </div>
                            </div>
                        </div>
                        {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                        <div class="price_with_reduction smooth02 trajan">
                            <span class="now trajan">{l s='Now' mod='pk_promominicslider'}</span>
                            <span class="price lmromandunh">
                                {displayPrice price=$price}                                
                            </span>
                            <a class="button lmromandemi" href="{$minicSlider.promo_products.$k.link}">{l s='Buy now!' mod='pk_promominicslider'}</a>
                        </div>
                        {/if}
                    </div>   
                    </div>                       
                </li>
                {/if}{/foreach}
            </ul>
        {else}
            <p>{l s='No products at this time' mod='pk_promominicslider'}</p>
        {/if}
    </div>
</div>
<script type="text/javascript">
jQuery(function(){         
    jQuery('#camera_wrap').camera({
        height: '95.82%',
        loader: 'pie',
        loaderColor: '#e17365',
        loaderBgColor: '#ffffff',
        loaderStroke: 7,
        playPause: false,
        imagePath: '{$minicSlider.path.mod_url}',
        fx: '{if $minicSlider.options.current != ''}{$minicSlider.options.current}{else}random{/if}',
        slicedCols: {if $minicSlider.options.slices != ''}{$minicSlider.options.slices}{else}8{/if},
        slicedRows: {if $minicSlider.options.rows != ''}{$minicSlider.options.rows}{else}8{/if}, 
        transPeriod: {if $minicSlider.options.speed != ''}{$minicSlider.options.speed}{else}500{/if}, 
        time: {if $minicSlider.options.pause != ''}{$minicSlider.options.pause}{else}3000{/if}, 
        navigation: {if $minicSlider.options.buttons == 1}true{else}false{/if}, 
        pagination: {if $minicSlider.options.control == 1}true{else}false{/if},
        thumbnails: {if $minicSlider.options.thumbnail == 1}true{else}false{/if},
        hover: {if $minicSlider.options.hover == 1}true{else}false{/if}, 
        autoAdvance: {if $minicSlider.options.manual == 0}true{else}false{/if}
    });
});
</script>