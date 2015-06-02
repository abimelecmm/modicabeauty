<div id="sequence-theme" class="load-animate homemodule">
    {if $minicSlider.options.buttons == 1}
    <ul class="controls">
        <li class="status"></li>
        <li class="prev"></li>
        <li class="pause"></li>
        <li class="next"></li>
    </ul>
    {/if}
    {if $minicSlider.options.control == 1}
    <ul class="nav">
        {foreach from=$slides.$defLang item=image name=singleimage}
        <li data-id="{$image.id_slide}"></li>
        {/foreach}
    </ul>
    {/if}
    <div id="sequence">
        <ul>
            {foreach from=$slides.$defLang item=image name=singleimage}            
            <li class="animate-in" id="slide{$image.id_slide}" data-id="{$image.id_slide}" data-info="shop{$image.id_shop}langiso{$image.lang_iso}">
                <img src="{$minicSlider.path.images}{$image.image}" class="slide{$image.id_slide} mainImg" {if $image.alt}alt="{$image.alt}"{/if} />
                <div class="slide-text">
                    {if $subImages[$image.lang_iso][$image.id_slide][0] != ""}
                        <div class="page_width">
                        {foreach $subImages[$image.lang_iso][$image.id_slide] key=imgId item=imgName}
                            <img src="{$minicSlider.path.images}{$imgName}" class="slideImg{$imgId}" alt="{$imgId}" />
                        {/foreach}
                        </div>
                    {/if}
                    <div id="htmlcaption_{$image.id_slide}" class="slide-text-{$image.id_slide} slide-content">
                        <h3 class="trajan">{$image.title}</h3>
                        <p>{$image.caption}</p>
                        {if ($image.url != "")}<a href="{$image.url}" {if ($image.target == 1)}target="_blank"{/if} class="button">{l s='Details' mod='pk_sequenceminicslider'}</a>{/if}
                    </div>
                </div>
            </li>
            {/foreach}            
        </ul>
    </div>
</div>