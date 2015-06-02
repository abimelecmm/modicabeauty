{capture name=path}
	<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
		{l s='My account' mod='favoriteproducts'}</a>
		<span class="navigation-pipe">{$navigationPipe}</span>{l s='My favorite products.' mod='favoriteproducts'}
{/capture}

<div class="wht_bg">
	<div class="wrap_indent">
<div id="favoriteproducts_block_account">
	<h2>{l s='My favorite products.' mod='favoriteproducts'}</h2>
	{if $favoriteProducts}
		<div>
			{foreach from=$favoriteProducts item=favoriteProduct}
			<div class="favoriteproduct clearfix">
				<a href="{$link->getProductLink($favoriteProduct.id_product, null, null, null, null, $favoriteProduct.id_shop)|escape:'html':'UTF-8'}" class="product_img_link">
					<img src="{$link->getImageLink($favoriteProduct.link_rewrite, $favoriteProduct.image, 'medium_default')|escape:'html':'UTF-8'}" alt=""/></a>
				<h3><a href="{$link->getProductLink($favoriteProduct.id_product, null, null, null, null, $favoriteProduct.id_shop)|escape:'html':'UTF-8'}">{$favoriteProduct.name|escape:'html':'UTF-8'}</a></h3>
				<div class="product_desc">{$favoriteProduct.description_short|strip_tags|escape:'html':'UTF-8'}</div>

				<div class="remove">
					<img rel="ajax_id_favoriteproduct_{$favoriteProduct.id_product}" src="{$img_dir}icon/delete.gif" alt="" class="icon" />
				</div>
			</div>
			{/foreach}
		</div>
	{else}
		<p class="warning">{l s='No favorite products have been determined just yet. ' mod='favoriteproducts'}</p>
	{/if}

	<ul class="footer_links">
		<li class="fleft">
			<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a>
			<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">{l s='Back to your account.' mod='favoriteproducts'}</a></li>
	</ul>
</div>
</div>
</div>