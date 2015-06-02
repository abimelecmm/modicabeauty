<!-- MODULE Block new products -->
<div id="new-products_block_right" class="block products_block">
	<h4 class="title_block"><a href="{$link->getPageLink('new-products')|escape:'html'}" title="{l s='New products' mod='blocknewproducts'}">{l s='New products' mod='blocknewproducts'}</a></h4>
	<div class="block_content">
	{if $new_products !== false}
		<ul class="product_images clearfix">
		{foreach from=$new_products item='product' name='newProducts'}
			{if $smarty.foreach.newProducts.index < 2}
			<li{if $smarty.foreach.newProducts.first} class="first"{/if}>
				<a class="product_image" href="{$product.link|escape:'html'}" title="{$product.legend|escape:html:'UTF-8'}">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium_'|cat:$cookie->img_name)|escape:'html'}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$product.legend|escape:html:'UTF-8'}" />
				</a>
				<div class="block_product_info">
					<h5><a href="{$product.link|escape:'html'}" title="{$product.name|escape:html:'UTF-8'}">{$product.name|strip_tags|escape:html:'UTF-8'}</a></h5>
					<span class="price">{displayPrice price=$product.price}</span>
				</div>
			</li>
			{/if}
		{/foreach}
		</ul>
	{else}
		<p>&raquo; {l s='Do not allow new products at this time.' mod='blocknewproducts'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block new products -->
