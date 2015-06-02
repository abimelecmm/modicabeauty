<!-- MODULE Block specials -->
<div id="special_block_right" class="block products_block exclusive blockspecials">
	<h4 class="title_block"><a href="{$link->getPageLink('prices-drop')|escape:'html'}" title="{l s='Specials' mod='blockspecials'}">{l s='Specials' mod='blockspecials'}</a></h4>
	<div class="block_content">
	{if $special}
		<ul class="product_images clearfix">
			<li>
				<a class="product_image" href="{$special.link}">
					<img src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'medium_default')|escape:'html'}" alt="{$special.legend|escape:html:'UTF-8'}" height="{$mediumSize.height}" width="{$mediumSize.width}" title="{$special.name|escape:html:'UTF-8'}" />
				</a>
				<div class="block_product_info">
					<h5><a href="{$special.link}" title="{$special.name|escape:html:'UTF-8'}">{$special.name|escape:html:'UTF-8'}</a></h5>
					{if !$PS_CATALOG_MODE}
						<span class="price-discount price">{if !$priceDisplay}{displayWtPrice p=$special.price_without_reduction}{else}{displayWtPrice p=$priceWithoutReduction_tax_excl}{/if}</span>
						{if $special.specific_prices}
							{assign var='specific_prices' value=$special.specific_prices}
							{if $specific_prices.reduction_type == 'percentage' && ($specific_prices.from == $specific_prices.to OR ($smarty.now|date_format:'%Y-%m-%d %H:%M:%S' <= $specific_prices.to && $smarty.now|date_format:'%Y-%m-%d %H:%M:%S' >= $specific_prices.from))}
								<span class="reduction"><span>-{$specific_prices.reduction*100|floatval}%</span></span>
							{/if}
						{/if}
						<span class="price">{if !$priceDisplay}{displayWtPrice p=$special.price}{else}{displayWtPrice p=$special.price_tax_exc}{/if}</span>
					{/if}
				</div>
			</li>
		</ul>
	{else}
		<p>{l s='No specials at this time.' mod='blockspecials'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block specials -->