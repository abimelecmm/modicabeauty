<!-- MODULE Block best sellers -->
<div id="best-sellers_block_right" class="block products_block">
    <h4 class="title_block"><a href="{$link->getPageLink('best-sales')|escape:'html'}" title="{l s='View a top sellers products' mod='blockbestsellers'}">{l s='Top sellers' mod='blockbestsellers'}</a></h4>
    <div class="block_content">
        {if $best_sellers && $best_sellers|@count > 0}
            <ul class="product_images">
                {foreach from=$best_sellers item=product name=myLoop}
                {if $smarty.foreach.myLoop.index < 3}
                    <li class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if} clearfix">
                        <a href="{$product.link|escape:'html'}" title="{$product.legend|escape:'html':'UTF-8'}" class="product_image content_img clearfix">
                            <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium_'|cat:$cookie->img_name)|escape:'html'}"
                                 height="{$smallSize.height}" width="{$smallSize.width}"
                                 alt="{$product.legend|escape:'html':'UTF-8'}"/>

                        </a>
                         <div class="block_product_info">
                            <h5><a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|strip_tags|escape:html:'UTF-8'}</a></h5>
                            {if !$PS_CATALOG_MODE}<span class="price">{displayPrice price=$product.price}</span>{/if}
                        </div>
                    </li>
                {/if}
                {/foreach}
            </ul>
        {else}
            <p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
        {/if}
    </div>
</div>
<!-- /MODULE Block best sellers -->
