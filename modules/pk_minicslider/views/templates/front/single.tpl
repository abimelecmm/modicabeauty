<div id="minic_slider" class="homemodule load-animate clearfix theme-default{if $minicSlider.options.thumbnail == 1 and $minicSlider.options.control != 0} controlnav-thumbs{/if}">   
    <div id="slider" class="nivoSlider" style="{if $minicSlider.options.width}width:{$minicSlider.options.width}px;{/if}{if $minicSlider.options.height}height:{$minicSlider.options.height}px{/if}{if $minicSlider.options.control != 1}margin-bottom:0;{/if}{if $minicSlider.position == 'top'}display:inline-block;{/if}">
        {foreach from=$slides.$defLang item=image name=singleimage}
                <img src="{$minicSlider.path.images}{$image.image}" class="slider_image" 
                    {if $image.alt}alt="{$image.alt}"{/if}
                    {if $image.title != '' or $image.caption != ''}title="#htmlcaption_{$image.id_slide}"{/if} 
                    {if $minicSlider.options.thumbnail == 1}data-thumb="{$minicSlider.path.thumbs}{$image.image}"{/if}/>
        {/foreach}
    </div>
    {foreach from=$slides.$lang_iso item=caption name=singlecaption}
        {if $caption.title != '' or $caption.caption != ''}
            <div id="htmlcaption_{$caption.id_slide}" class="nivo-html-caption">
                <h3 class="lmromandemi main_title">{$caption.title}</h3>
                <div class="nivo-capt">{$caption.caption}</div>
                {if $caption.url != ''}
                <br/><br/>
                <a href="{$caption.url}" class="button sec_bord_hvr sec_bg_hvr" {if $caption.target == 1}target="_blank"{/if}>{l s='shop now' mod='pk_minicslider'}</a>
                {/if}
            </div>
        {/if}
    {/foreach}
</div> 