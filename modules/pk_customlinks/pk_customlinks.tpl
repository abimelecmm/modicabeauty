<!-- Block customlinks module -->
<div id="pk_customlinks" class="dib">
	<ul>
		{if $main_links.myacc == true}
		<li class="pk_account dd_el dib smooth02 main_bg_hvr">
			<a href="#" title="{l s='My Account' mod='pk_customlinks'}"><svg class="svgic main_color svgic-account"><use xlink:href="#si-account"></use></svg><span>{l s='My Account' mod='pk_customlinks'}</span></a>
			<div class="dd_container main_bg">
				<div class="indent">
				<ul>
					<li><a href="{$link->getPageLink('history', true)|escape:'html'}" title="{l s='My orders' mod='pk_customlinks'}" rel="nofollow">{l s='My orders' mod='pk_customlinks'}</a></li>
					{if $pk_returnAllowed}<li><a href="{$link->getPageLink('order-follow', true)|escape:'html'}" title="{l s='My returns' mod='pk_customlinks'}" rel="nofollow">{l s='My merchandise returns' mod='pk_customlinks'}</a></li>{/if}
					<li><a href="{$link->getPageLink('order-slip', true)|escape:'html'}" title="{l s='My credit slips' mod='pk_customlinks'}" rel="nofollow">{l s='My credit slips' mod='pk_customlinks'}</a></li>
					<li><a href="{$link->getPageLink('addresses', true)|escape:'html'}" title="{l s='My addresses' mod='pk_customlinks'}" rel="nofollow">{l s='My addresses' mod='pk_customlinks'}</a></li>
					<li><a href="{$link->getPageLink('identity', true)|escape:'html'}" title="{l s='Manage my personal information' mod='pk_customlinks'}" rel="nofollow">{l s='My personal info' mod='pk_customlinks'}</a></li>
					{if $pk_voucherAllowed}<li><a href="{$link->getPageLink('discount', true)|escape:'html'}" title="{l s='My vouchers' mod='pk_customlinks'}" rel="nofollow">{l s='My vouchers' mod='pk_customlinks'}</a></li>{/if}
				</ul>
				</div>
			</div>
		</li>{/if}{if ($wishlist_link != "") && ($main_links.mywsh == true)}<li class="pk_wishlist dd_el dib smooth02 main_bg_hvr">
			<a href="{$wishlist_link}" title="{l s='My Wishlist' mod='pk_customlinks'}"><svg class="svgic main_color svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg><span>{l s='My Wishlist' mod='pk_customlinks'} (<span class="wlQty">{if !empty($pk_wishlist["wishlist_products"])}{count($pk_wishlist["wishlist_products"])}{else}0{/if}</span>)</span></a>
			<div id="pk_wishlist" class="dd_container main_bg">
				<div class="indent">
				<ul class="wishlist-list">
				{if $pk_wishlist["wishlist_products"]}			
					{foreach from=$pk_wishlist["wishlist_products"] item=p name=i}		
						<li class="clearfix {if $smarty.foreach.i.first}first_item{elseif $smarty.foreach.i.last}last_item{else}item{/if}">
							<a href="{$link->getProductLink($p.id_product, $p.link_rewrite, $p.category_rewrite)|escape:'html'}" class="WishListProdImage content_img">
								<img src="{$link->getImageLink($p.link_rewrite, $p.image, 'medium_'|cat:$cookie->img_name)|escape:'htmlall':'UTF-8'}" width="63" height="63" alt=""/>
							</a>
							<div class="text_desc">
								<span class="pName">
									<a class="cart_block_product_name"
									href="{$link->getProductLink($p.id_product, $p.link_rewrite, $p.category_rewrite)|escape:'html'}">{$p.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}</a>
								</span>
								{if !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<div class="price">{convertPrice price=$p.price}</div>{/if}
								<a class="ajax_cart_block_remove_link" href="javascript:;" onclick="javascript:WishlistCart('wishlist_block_list', 'delete', '{$p.id_product}', {$p.id_product_attribute}, '0', '{$token}'); $(this).closest('li').hide('fast'); $('.wlQty').text((parseInt($('.wlQty').text()))-1);$('#pk_wishlist').css({ 'height':'auto' });" title="{l s='remove this product from my wishlist' mod='pk_customlinks'}"></a>
							</div>
						</li>						
					{/foreach}
				{else}
					<li class="no-products">{l s='No products' mod='pk_customlinks'}</li>
				{/if}
				</ul>
				</div>
			</div>
		</li>{/if}{if ($favorite_module == 1) && ($main_links.myfav == true)}<li class="pk_favorites dd_el dib smooth02 main_bg_hvr">
			<a href="{$link->getModuleLink('favoriteproducts', 'account')|escape:'htmlall':'UTF-8'}" title="{l s='My Favorites' mod='pk_customlinks'}"><svg class="svgic main_color svgic-like"><use xlink:href="#si-like"></use></svg><span>{l s='My Favorites' mod='pk_customlinks'} (<span class="favQty">{if !empty($pk_favoriteProducts)}{count($pk_favoriteProducts)}{else}0{/if}</span>)</span></a>
			<div class="favoritelist dd_container main_bg">
				<div class="indent">
				<ul>
				{if $pk_favoriteProducts}								
					{foreach from=$pk_favoriteProducts item=p}
					<li class="favoriteproduct clearfix">
						<a href="{$link->getProductLink($p.id_product, null, null, null, null, $p.id_shop)|escape:'htmlall':'UTF-8'}" class="favProdImage content_img">
							<img src="{$link->getImageLink($p.link_rewrite, $p.image, 'medium_'|cat:$cookie->img_name)|escape:'htmlall':'UTF-8'}" width="63" height="63" alt=""/>
						</a>
						<div class="text_desc">
							<span class="pName">
								<a href="{$link->getProductLink($p.id_product, null, null, null, null, $p.id_shop)|escape:'htmlall':'UTF-8'}">{$p.name|escape:'htmlall':'UTF-8'}</a>
							</span>
							{if !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<div class="price">{convertPrice price=$p.price}</div>{/if}
							<a href="#" onclick="return false" class="remove" rel="ajax_id_favoriteproduct_{$p.id_product}" data-buttonID="{$p.id_product}">&nbsp</a>
						</div>
					</li>
					{/foreach}				
				{else}
					<li class="no-products">{l s='No favorite products have been determined just yet. ' mod='pk_customlinks'}</li>
				{/if}					
				</ul>
				</div>
			</div>
		</li>{/if}{if $main_links.mywtl == true}<li class="pk_watchlist dd_el dib smooth02 main_bg_hvr"><a href="#" title="{l s='Recently viewed products' mod='pk_customlinks'}"><svg class="svgic main_color svgic-eye"><use xlink:href="#si-eye"></use></svg><span>{l s='Watch List' mod='pk_customlinks'} <span>({count($watchlist)})</span></span></a>
			<div class="watchlist dd_container main_bg">
				<div class="indent">
				<ul>
					{if $watchlist}
					{foreach from=$watchlist item=p name=loop}
						<li class="clearfix{if $smarty.foreach.loop.last} last_item{elseif $smarty.foreach.loop.first} first_item{else} item{/if}">
							<a href="{$p->product_link|escape:'html'}" title="{l s='About' mod='pk_customlinks'} {$p->name|escape:html:'UTF-8'}" class="content_img">
							<img width="63" height="63" src="{if isset($p->id_image) && $p->id_image}{$link->getImageLink($p->link_rewrite, $p->cover, 'medium_'|cat:$cookie->img_name)}{else}{$img_prod_dir}{$lang_iso}-default-medium_default.jpg{/if}" alt="{$p->legend|escape:html:'UTF-8'}" />
							</a>
							<div class="text_desc">
								<span class="pName">
									<a href="{$p->product_link|escape:'html'}" title="{l s='About' mod='pk_customlinks'} {$p->name|escape:html:'UTF-8'}">{$p->name}</a>
								</span>
								{if !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<div class="price">{convertPrice price=$p->price}</div>{/if}
							</div>
						</li>
					{/foreach}
					{else}
						<li class="no-products">{l s='No viewed products yet.' mod='pk_customlinks'}</li>
					{/if}
				</ul>
			</div>
			</div>
		</li>{/if}{if $main_links.mycmp == true}<li class="pk_compare dd_el dib smooth02 main_bg_hvr"><a href="{$link->getPageLink('products-comparison')|escape:'html':'UTF-8'}" title="{l s='Compare List' mod='pk_customlinks'}"><svg class="svgic main_color svgic-compare"><use xlink:href="#si-compare"></use></svg><span>{l s='Compare List' mod='pk_customlinks'} (<span class="total-compare-val">{count($compared_products)}</span>)</span></a>
			<div class="compare dd_container main_bg">
				<div class="indent">
				<ul>
					{if $pk_compare}
						{foreach from=$pk_compare item=p name=loop}
							<li data-id-product="$p.id" class="clearfix{if $smarty.foreach.loop.last} last_item{elseif $smarty.foreach.loop.first} first_item{else} item{/if}">						
								<a href="{$link->getProductLink($p.id, $p.link_rewrite)|escape:'html'}" title="{l s='About' mod='pk_customlinks'} {$p.name|escape:html:'UTF-8'}" class="content_img">
									<img width="63" height="63" src="{$link->getImageLink($p.link_rewrite, $p.cover, 'medium_'|cat:$cookie->img_name)}" alt="{$p.name|escape:html:'UTF-8'}" />
								</a>
								<div class="text_desc">
									<span class="pName">
										<a href="{$link->getProductLink($p.id, $p.link_rewrite)|escape:'html'}" title="{l s='About' mod='pk_customlinks'} {$p.name|escape:html:'UTF-8'}">{$p.name}</a>
									</span>
									{if !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
										<div class="price">{convertPrice price=$p.specprice}</div>
									{/if}
								</div>
							</li>
						{/foreach}
					{else}
						<li class="no-products">{l s='No products to compare.' mod='pk_customlinks'}</li>
					{/if}
				</ul>
			</div>
			</div>
		</li>{/if}{foreach from=$customlinks_links item=blocklink_link}{if isset($blocklink_link.$lang)}<li class="dib">
				<a href="{$blocklink_link.url|escape}"{if $blocklink_link.newWindow} onclick="window.open(this.href);return false;"{/if}>{$blocklink_link.$lang|escape}</a>
			</li>{/if}{/foreach}
	</ul>
</div>
<!-- /Block customlinks module -->
{strip}
{addJsDef page_name=$page_name}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef ajaxPath=$link->getModuleLink('favoriteproducts', 'actions', ['process' => 'remove'], true)|addslashes}
{/strip}