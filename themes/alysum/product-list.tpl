{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if isset($products)}
	{*define numbers of product per line in other page for desktop*}
	{if $page_name !='index' && $page_name !='product'}
		{assign var='nbItemsPerLine' value=3}
		{assign var='nbItemsPerLineTablet' value=2}
		{assign var='nbItemsPerLineMobile' value=3}
	{else}
		{assign var='nbItemsPerLine' value=4}
		{assign var='nbItemsPerLineTablet' value=3}
		{assign var='nbItemsPerLineMobile' value=2}
	{/if}
	{*define numbers of product per line in other page for tablet*}
	{assign var='nbLi' value=$products|@count}
	{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
	{math equation="nbLi/nbItemsPerLineTablet" nbLi=$nbLi nbItemsPerLineTablet=$nbItemsPerLineTablet assign=nbLinesTablet}
	<!-- Products list -->
	<ul{if isset($id) && $id} id="{$id}"{/if} class="product_list{if isset($class) && $class} {$class}{/if}{if isset($active) && $active == 1} active{/if}">
	{foreach from=$products item=product name=products}
		{math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineTablet assign=totModuloTablet}
		{math equation="(total%perLineT)" total=$smarty.foreach.products.total perLineT=$nbItemsPerLineMobile assign=totModuloMobile}
		{if $totModulo == 0}{assign var='totModulo' value=$nbItemsPerLine}{/if}
		{if $totModuloTablet == 0}{assign var='totModuloTablet' value=$nbItemsPerLineTablet}{/if}
		{if $totModuloMobile == 0}{assign var='totModuloMobile' value=$nbItemsPerLineMobile}{/if}
		<li class="ajax_block_product{if $smarty.foreach.products.iteration%$nbItemsPerLine == 0} last-in-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1} first-in-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModulo)} last-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 0} last-item-of-tablet-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineTablet == 1} first-item-of-tablet-line{/if}{if $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 0} last-item-of-mobile-line{elseif $smarty.foreach.products.iteration%$nbItemsPerLineMobile == 1} first-item-of-mobile-line{/if}{if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - $totModuloMobile)} last-mobile-line{/if}" data-productid="{$product.id_product}" data-rewrite="{$product.link_rewrite}">
			<div class="product-container" itemscope itemtype="http://schema.org/Product">
				<div class="left_block">
					{if isset($comparator_max_item) && $comparator_max_item}
						<div class="compare">
							<a class="add_to_compare" href="#" data-id-product="{$product.id_product}" title="{l s='Add to Compare'}">{l s='Add to Compare'}</a>
						</div>
					{/if}
				</div>
				<div class="center_block">
					<div class="product-image-container">
						<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
							<img class="replace-2x img-responsive pimg-{$product.id_product}" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} itemprop="image" />
							<span class="subimage-container pid_{$product.id_product}"></span>
							<span class="attimage-container aid_{$product.id_product}"></span>
							<span class="product-flags">
								{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
									{if isset($product.online_only) && $product.online_only}
										<span class="online_only">{l s='Online only'}</span><br/>
									{/if}
								{/if}
								{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
									{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
										<span class="discount">{l s='Reduced price!'}</span><br/>
									{/if}
								{if isset($product.new) && $product.new == 1}<span class="new">{l s='New'}</span><br/>{/if}
								{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
									<span class="sale-box">
										<span class="sale-label">{l s='Sale!'}</span>
									</span><br/>
								{/if}
								{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
									{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
										{if $product.specific_prices.reduction_type == 'percentage'}
											<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
										{/if}
									{/if}
								{/if}												
							</span>							
						</a>
						{if ($cookie->ts_countdown == 1)}
				        {assign var=to value="-"|explode:$product.specific_prices.to} 
				        {if isset($product.specific_prices.to) && ($to[0] != "0000")}
				        	<div class="countdown countdown-{$product.id_product|intval}"></div>
				        	<script>
				        	$(document).ready(function(){
				        		$(function() {
								    $('.countdown-{$product.id_product|intval}').countdown({
								        date: "{$product.specific_prices.to|replace:' ':'T'}",
								          render: function(data) {
								            $(this.el).html("<div>" + this.leadingZeros(data.days, 2) + " <span>{l s='Days'}</span></div><div>" + this.leadingZeros(data.hours, 2) + " <span>{l s='Hours'}</span></div><div>" + this.leadingZeros(data.min, 2) + " <span>{l s='Min'}</span></div><div>" + this.leadingZeros(data.sec, 2) + " <span>{l s='Sec'}</span></div>");
								          }
								    });
								});
				        	});
				        	</script>			        	
				        {/if}
				        {/if}
						{if isset($product.color_list)}
							<div class="color-list-container">{if $product.color_list}{$product.color_list}{else}&nbsp;{/if}</div>
						{/if}
						<h3 itemprop="name" class="ellipsis">
							{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
							<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
								{$product.name|truncate:70:'...'|escape:'html':'UTF-8'}
							</a>
						</h3>	
					</div>
					<div class="product-info">
						<h3 itemprop="name">
							{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
							<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
								{$product.name|escape:'html':'UTF-8'}
							</a>
						</h3>														
						{hook h='displayProductListReviews' product=$product}
						<p class="product-desc" itemprop="description">
							{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
						</p>
						{hook h="displayProductDeliveryTime" product=$product}
						{hook h="displayProductPriceBlock" product=$product type="weight"}					
					</div>
					<div class="right_block">
						<div class="button-container">
							{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
								{if ($product.allow_oosp || $product.quantity > 0)}
									{if isset($static_token)}
										<a class="button ajax_add_to_cart_button dib btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
											<i></i>{l s='Add to cart'}
										</a><br class="lst" />
									{else}
										<a class="button ajax_add_to_cart_button dib btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
											<i></i>{l s='Add to cart'}
										</a><br class="lst" />
									{/if}						
								{else}
									<span class="button ajax_add_to_cart_button dib btn btn-default disabled">
										<i></i>{l s='Add to cart'}
									</span><br class="lst" />
								{/if}
							{/if}
							{if $page_name != 'index'}
				 				<div class="functional-buttons dib clearfix smooth02">
									{hook h='displayProductListFunctionalButtons' product=$product}						
								</div>
							{/if}
							{if isset($quick_view) && $quick_view}
								<a class="quick-view dib button" href="#" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}"><svg class="svgic svgic-search"><use xlink:href="#si-search"></use></svg></a>
							{/if}
						</div>	
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
							<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price">
								{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
									{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
										<span class="old-price product-price">
											{displayWtPrice p=$product.price_without_reduction}
										</span>
									{/if}
									<span itemprop="price" class="price product-price">
										{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span><br/><meta itemprop="priceCurrency" content="{$priceDisplay}" />{/if}
							</div>
						{/if}
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
							{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
								<span itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="availability hidden">
									{if ($product.allow_oosp || $product.quantity > 0)}
										<span class="available-now">
											<link itemprop="availability" href="http://schema.org/InStock" />{l s='In Stock'}
										</span>
									{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
										<span class="available-dif">
											<link itemprop="availability" href="http://schema.org/LimitedAvailability" />{l s='Product available with different options'}
										</span>
									{else}
										<span class="out-of-stock">
											<link itemprop="availability" href="http://schema.org/OutOfStock" />{l s='Out of stock'}
										</span>
									{/if}
								</span>
							{/if}
						{/if}
					</div>
				</div>				
			</div><!-- .product-container> -->
			<div class="clearfix"></div>
		</li>
	{/foreach}
	</ul>
{addJsDefL name=min_item}{l s='Please select at least one product' js=1}{/addJsDefL}
{addJsDefL name=max_item}{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}{/addJsDefL}
{addJsDef comparator_max_item=$comparator_max_item}
{addJsDef comparedProductsIds=$compared_products}
{/if}
{if (isset($cookie->id_customer))}
	{assign var=custID value=$cookie->id_customer}
{elseif (isset($cookie->id_guest))}
	{assign var=custID value=$cookie->id_guest}
{else}
	{assign var=custID value=0}
{/if}
<script>
$(document).ready(function() {
	$(".color_pick").hover(
			function () {
				var attrid = $(this).data("attrid");
				var pid = $(this).data("pid");
				var link_rewrite = $(this).closest(".ajax_block_product").data("rewrite");
				$.ajax({
				    type: 'POST',
				    url: baseDir+'modules/pk_themesettings/ajax.php',
				    data: 'getImgByAttr=1&pid='+pid+'&attrid='+attrid+'&link_rewrite='+link_rewrite+'&imgName=home_{$cookie->img_name}',
				    success: function(result){
				      if (result == '0')
				      {
				        console.log('no data')
				      } else {                          
				      	if (JSON.parse(result) != false) {
				      		console.log(JSON.parse(result));
					        $(".aid_"+pid).append('<img class="colorimg im_'+attrid+'" src="'+JSON.parse(result)+'" alt="" />');
					    } else {
					    	console.log("No image for this color");
					    }
				      }
				    }
				});    
			},	 
			function () {
				$('.colorimg').remove();
			}
		);	
	$.ajax({
	    type: 'POST',
	    url: baseDir+'modules/pk_themesettings/ajax.php',
	    data: 'id={foreach from=$products item=p name=prd}{$p['id_product']}{if $smarty.foreach.prd.last}{else},{/if}{/foreach}&customer={$custID}&lang_id={$cookie->id_lang}&imgName=home_{$cookie->img_name}&wishlist=blockwishlist&favorites=favoriteproducts',
	    success: function(result){
	      if (result == '0')
	      {
	        console.log("no data")
	      } 
		  else {                   
			var pData = JSON.parse(result);					

			if (pData.modules.enabled.wishlist == "disabled" || pData.modules.installed.wishlist == "not_installed") $(".product_wishlist").hide();
		  	//if (pData.modules.enabled.favorites == "disabled" || pData.modules.installed.favorites == "not_installed") $(".product_like").hide();
		   	jQuery.each(pData.isInWishlist, function(index, value) {
		   		if (value == true) {
			   		$(".product_list").find(".wishlistProd_"+index).addClass("active");
			   	}
		   	});		
		   	jQuery.each(pData.subimage, function(index, value) {
		   		if (value != "no_image") {
			   		$(".pid_"+index).append("<img src='"+value+"' alt='' />");
			   		$(".pid_"+index).parent().addClass('hasSubImage');
			   	}
		   	});
	      }
	    }
	 });
	$.get(baseDir+'modules/pk_themesettings/ajax.php', { theme_settings: "get" }, function(data) {
		var opt = JSON.parse(data);
		if (opt.reduced_label == 1) {		  	
	  		$(".discount").removeClass('hidden');
	  	}
	  	if (opt.availability_label == 1) {		  	
	  		$(".availability").removeClass('hidden');
	  	}
	});
});
</script>