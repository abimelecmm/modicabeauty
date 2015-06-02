<!-- MODULE IsotopeSort -->
<div id="isotopeSort" class="homemodule load-animate">
	<div class="isotopeSortIndent">
	{if isset($products) AND $products}
        <div class="option-combo">
      	<ul class="filter option-set clearfix" data-filter-group="type"> 
	          <li><a href="#" data-filter-value="" class="selected lmroman">{l s='All' mod='pk_isotopesort'}</a></li>{if $fea}<li><a class="lmroman" href="#" data-filter-value=".featured_product">{l s='Featured' mod='pk_isotopesort'}</a></li>{/if}{if $spe}<li><a class="lmroman" href="#" data-filter-value=".special_product">{l s='Special' mod='pk_isotopesort'}</a></li>{/if}{if $new}<li><a class="lmroman" href="#" data-filter-value=".new_product">{l s='Latest' mod='pk_isotopesort'}</a></li>{/if}{if $bes}<li><a class="lmroman" href="#" data-filter-value=".bestsellers">{l s='Bestsellers' mod='pk_isotopesort'}</a></li>{/if}{if $categories}{foreach from=$categories item=category name=categories}<li><a class="lmroman" href="#" data-filter-value=".{$category.link_rewrite}">{$category.name}</a></li>{/foreach}
	          {/if}
	      </ul>
    	</div>        
		<div class="block_content">
			<ul id="isotope" class="isotope products-module">
            {foreach from=$products item=product key=p name=isotopeSortProduct}
            {if !empty($product.name) && ($smarty.foreach.isotopeSortProduct.index < $isotope_max)}
       			<li class="ajax_block_product {$type[$product["id_product"]]}" data-productid="{$product['id_product']}">
					<div class="product_image_cont">
						<a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"><img src='{$link->getImageLink($product.link_rewrite, $product.id_image, "large_`$cookie->img_name`")}' alt="{$product.name|escape:html:'UTF-8'}" /></a>
						<span class="labels">
							{if isset($product.new) && $product.new == 1}
								<span class="new sec_bg">{l s='New' mod='pk_isotopesort'}</span>
							{/if}
							{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                              {if isset($product.specific_prices.price)}
                                <span class="pk-reduction sec_bg">
                                  {if $product.specific_prices.reduction_type == "amount"}
                                      -{convertPrice price=$product.specific_prices.reduction}
                                  {else}
                                      -{$product.specific_prices.reduction*100|round:2}%        
                                  {/if}
                                </span>
                              {/if}                            
                              {/if}
						</span>
						{if ($countdown == true)}
				        {assign var=to value="-"|explode:$product.specific_prices.to} 
				        {if isset($product.specific_prices.to) && ($to[0] != "0000")}
				        	<div class="countdown countdown-{$product.id_product}" title="{l s='To the end of this offer'}"></div>
				        	<script>
				        	$(document).ready(function(){
				        		$(function() {
								    $('#isotope .countdown-{$product.id_product}').countdown({
								        date: "{$product.specific_prices.to|replace:' ':'T'}",
								          render: function(data) {
								            $(this.el).html("<div>" + this.leadingZeros(data.days, 2) + " <span>{l s='Days'}</span></div><div>" + this.leadingZeros(data.hours, 2) + " <span>{l s='Hours'}</span></div><div>" + this.leadingZeros(data.min, 2) + " <span>{l s='Min'}</span></div><div>" + this.leadingZeros(data.sec, 2) + " <span>{l s='Sec'}</span></div>");
								            $(this.el).attr('title', this.leadingZeros(data.days, 2)+" {l s='Days'} {l s='and'} "+this.leadingZeros(data.hours, 2)+" {l s='Hours'} {l s='to the end of this offer'}");
								          }
								    });
								});
				        	});
				        	</script>			        	
				        {/if}
				        {/if}
						<div class="function_buttons altview smooth02">

							{if ($wishlist.install == "installed") && ($wishlist.enable == "enabled")}
								<div class="function_button dib product_wishlist sec_bshadow_hvr smooth02 sec_bg_hvr{if $isInWishList[$product.id_product] == 1} sec_bg sec_bshadow remove{/if}">
                              		<a href="#" class="wishlist_button addToWishlist" title="{if $isInWishList[$product.id_product] == 1}{l s='This product is already in your wishlist' mod='pk_productsCarousel_bottom'}{else}{l s='Add to Wishlist' mod='pk_productsCarousel_bottom'}{/if}">
                              			<svg class="svgic svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg>
                              		</a>
                              	</div>
                            {/if}
                            
                            {if ($favorite.install == "installed") && ($favorite.enable == "enabled")}
                            	<div class="function_button dib product_like sec_bshadow_hvr smooth02 sec_bg_hvr {if $isFav[$product.id_product] == 1} sec_bg sec_bshadow{/if}">
                            		<a title="{l s='Add this product to my favorites' mod='pk_isotopesort'}" href="#" class="addfav dib{if $isFav[$product.id_product] == 1} hidden{/if}">
                            			<svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg>
                            		</a>
                            		<a title="{l s='Remove product from my favorites' mod='pk_isotopesort'}" href="#" class="remfav  dib{if $isFav[$product.id_product] != 1} hidden{/if}">
                            			<svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg>
                            		</a>
                              	</div>
                            {/if}

                            	<div class="function_button dib quickview sec_bshadow_hvr smooth02 sec_bg_hvr">
                            		<a class="quick-view" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}">
                            			<svg class="svgic svgic-search"><use xlink:href="#si-search"></use></svg>
                            		</a>
                            	</div>

						</div>
					</div>
					<div class="isotope_bottom_block">					
						<h5 class="s_title_block normview"><a class="ellipsis" href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a></h5>
						{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
							<p class="price_container">
								{if ($product.reduction > 0)}
								<span class="price isotope-old-price old-price">{convertPrice price=$product.price_without_reduction}</span>	
								{/if}
								<span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>
							</p>
						{/if}
						<h5 class="s_title_block altview"><a class="ellipsis" href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</a></h5>
						{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
							{if ($product.quantity > 0 OR $product.allow_oosp)}
								<a class="exclusive ajax_add_to_cart_button lmromancaps" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" title="{l s='Add to cart' mod='pk_isotopesort'}" data-id-product="{$product.id_product|intval}">{l s='Add to cart' mod='pk_isotopesort'}</a>
							{else}
								<a href="{$product.link}" class="button exclusive lmromancaps altview">{l s='View' mod='pk_isotopesort'}</a>
							{/if}
						{else}
							<a href="{$product.link}" class="button exclusive lmromancaps altview">{l s='View' mod='pk_isotopesort'}</a>
						{/if}
					</div>
				</li>
			{/if}
			{/foreach}
			</ul>
		</div>
	{else}
		<p class="alert alert-warning">{l s='There are no products right now' mod='pk_isotopesort'}</p>
	{/if}
	</div>
</div>
<script>
function getProducts() {
	$.ajax({
	    type: 'POST',
	    url: baseDir + 'modules/pk_isotopesort/ajax.php',
	    success: function(result){
	      if (result == '0') {
	        console.log("no data")
	      } else {
			  
				$('#isotope').prepend(result);			
	      }
	    }
	});
}
$(window).load(function() {
// cache container
	var $container = $('#isotope');

	// initialize isotope
	$container.isotope({
	  // options...
	});

// filter items when filter link is clicked
$('.filter a').click(function(){
  var selector = $(this).attr('data-filter-value');
  $container.isotope({ filter: selector });
  $('.filter a.selected').removeClass('selected');
  $(this).addClass('selected');
  return false;
});
});
</script>
<!-- /MODULE IsotopeSort -->