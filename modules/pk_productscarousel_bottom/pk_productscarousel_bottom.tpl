{if $page_name == "index"}
<!-- productsCarousel_bottom -->
<div id="productsCarousel_bottom" class="carouselDesktop load-animate homemodule">
    <table class="title-table">
      <tr>
        <td class="w50p"><span class="right-wing title-wing"></span></td>
        <td class="carousel-title"><h3 class="lmroman">{if $pr_type == "fea"}
        {l s='Featured Products' mod='pk_productscarousel_bottom'}
      {elseif $pr_type == "new"}
        {l s='New Products' mod='pk_productscarousel_bottom'}
      {elseif $pr_type == "spe"}
        {l s='On Sale' mod='pk_productscarousel_bottom'}
      {elseif $pr_type == "bes"}
        {l s='Bestsellers' mod='pk_productscarousel_bottom'}
      {/if}</h3></td>
        <td class="w50p"><span class="left-wing title-wing"></span></td>
      </tr>
    </table>
    <div class="wht-bg">
      <div class="indent">
        {if (!empty($products_kit) && $products_kit)}
          <ul id="products-bottom" class="da-thumbs slides products-module">
              {foreach from=$products_kit item=set name=set key=k}
              {if (!empty($set) && $set)}
              {assign var='productLink' value=$link->getProductLink($set.data.id_product, $set.data.link_rewrite)}
              <li class="block_product ajax_block_product slide" data-productid="{$set.data.id_product}">
                  <div class="carouselContainer">
                    <div class="slide-animate">

                      <a href="{$productLink}" title="{$set.data.legend}" class="product_image p{$set.data.id_product}">

                        <img src="{$link->getImageLink($set.data.link_rewrite, "{$set.prodCover.id_product}-{$set.prodCover.id_image}", 'home_'|cat:$cookie->img_name)}" alt="{$set.data.name|escape:html:'UTF-8'}" class="add_to_cart_image" />

                      </a>

                        {if isset($p_hover) && ($p_hover == 1)}
                          {if isset($set.image)}
                            <a href="{$productLink}" title="{$set.data.legend}" class="additional-image smooth02">
                              <img src="{$link->getImageLink($set.data.link_rewrite, "{$set.data.id_product}-{$set.image}", 'home_'|cat:$cookie->img_name)}" alt="{$set.data.name|escape:html:'UTF-8'}" />                    
                            </a>
                          {/if}
                        {/if}

                        {if ($countdown == true)}
                            {assign var=to value="-"|explode:$set.data.specific_prices.to} 
                            {if isset($set.data.specific_prices.to) && ($to[0] != "0000")}
                              <div class="countdown countdown-{$set.data.id_product}" title="{l s='To the end of this offer'}"></div>
                              <script>
                              $(document).ready(function(){
                                $(function() {
                                  $('#productsCarousel_bottom .countdown-{$set.data.id_product}').countdown({
                                      date: "{$set.data.specific_prices.to|replace:' ':'T'}",
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

                        <span class="labels">
                          {if isset($set.data.new) && $set.data.new == 1}<span class="pk-new sec_bg">{l s='New' mod='pk_productscarousel_bottom'}</span>{/if}
                          {if $set.data.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                              {if isset($set.data.specific_prices.price)}
                                <span class="pk-reduction">
                                  {if $set.data.specific_prices.reduction_type == "amount"}
                                      -{convertPrice price=$set.data.specific_prices.reduction}
                                  {else}
                                      -{$set.data.specific_prices.reduction*100|round:2}%        
                                  {/if}
                                </span>
                              {/if}                            
                          {/if}
                        </span>

                        {if isset($quick_view) && $quick_view}
                          <a class="quick-view normview" href="{$set.data.link|escape:'html':'UTF-8'}" rel="{$set.data.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}"><svg class="svgic svgic-search"><use xlink:href="#si-search"></use></svg></a>
                        {/if}
                      
                        <div class="function_buttons smooth02 altview">

                          {if ($wishlist.install == "installed") && ($wishlist.enable == "enabled")}
                              <div class="function_button dib product_wishlist sec_bshadow_hvr smooth02 sec_bg_hvr{if $isInWishList[$set.data.id_product] == 1} sec_bg sec_bshadow remove{/if}">
                                    <a href="#" class="wishlist_button addToWishlist" title="{if $isInWishList[$set.data.id_product] == 1}{l s='This product is already in your wishlist' mod='pk_productscarousel_bottom'}{else}{l s='Add to Wishlist' mod='pk_productscarousel_bottom'}{/if}">
                                      <svg class="svgic svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg>
                                    </a>
                                  </div>
                              {/if}
                              
                              {if ($favorite.install == "installed") && ($favorite.enable == "enabled")}
                                <div class="function_button dib product_like sec_bshadow_hvr smooth02 sec_bg_hvr {if $isFav[$set.data.id_product] == 1} sec_bg sec_bshadow{/if}">
                                  <a title="{l s='Add this product to my favorites' mod='pk_productscarousel_bottom'}" href="#" class="addfav dib{if $isFav[$set.data.id_product] == 1} hidden{/if}">
                                    <svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg>
                                  </a>
                                  <a title="{l s='Remove product from my favorites' mod='pk_productscarousel_bottom'}" href="#" class="remfav  dib{if $isFav[$set.data.id_product] != 1} hidden{/if}">
                                    <svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg>
                                  </a>
                                  </div>
                              {/if}

                                <div class="function_button dib quickview sec_bshadow_hvr smooth02 sec_bg_hvr">
                                  <a class="quick-view" href="{$set.data.link|escape:'html':'UTF-8'}" rel="{$set.data.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}">
                                    <svg class="svgic svgic-search"><use xlink:href="#si-search"></use></svg>
                                  </a>
                                </div>

                        </div>

                    </div>

                    <div class="bottom_block">  
                      {if ($comments["install"] == "installed") && ($comments["enable"] == "enabled") && ($p_rating == 1)}
                        <span class="rate_block smooth05">
                          {hook h='displayProductListReviews' product=$set.data}
                        </span>      
                        {/if}
                    <!-- BESTSELLERS -->
                      {if $pr_type=="bes"}
                      <a class="f_title ellipsis" href="{$productLink}" title="{$set.data.legend}">{$set.data.name|escape:htmlall:'UTF-8'|truncate:70}</a>
                        {if $set.data.price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                          {if isset($p_price) && ($p_price == 1)}
                            <div class="content_price smooth02 price-bg{if isset($set.data.specific_prices.price)} oldprice-exist{/if}">
                              {if isset($set.data.specific_prices.price)}
                              <span class="old-price product-price lmroman">{displayPrice price=$set.data.price_without_reduction}</span>
                              {/if}
                              <span class="price product-price lmroman">{displayPrice price=$set.data.price}</span>
                            </div>
                          {/if}
                        {/if}
                        
                        {if isset($p_button) && ($p_button == 1)}
                        <div class="carousel-buttons smooth02">
                          {if ($set.data.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $set.data.available_for_order AND !isset($restricted_country_mode) AND $set.data.minimal_quantity == 1 AND $set.data.customizable != 2 AND !$PS_CATALOG_MODE}
                            {if ($set.data.quantity > 0 OR $set.data.allow_oosp)}
                              <a class="exclusive ajax_add_to_cart_button button dib" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$set.data.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" data-id-product="{$set.data.id_product|intval}"><span>{l s='Add to cart' mod='pk_productscarousel_bottom'}</span></a>{else}<a href="{$set.data.link}" class="button dib exclusive">{l s='View' mod='pk_productscarousel_bottom'}</a>{/if}{else}<a href="{$set.data.link}" class="button exclusive dib">{l s='Details' mod='pk_productscarousel_bottom'}</a>{/if}{if ($favorite.install == "installed") && ($favorite.enable == "enabled")}<div class="function_button normview product_like{if $isFav[$set.data.id_product] == 1} active{/if}"><a title="{l s='Add this product to my favorites' mod='pk_productscarousel_bottom'}" href="#" class="addfav sec_bg_hvr dib button{if $isFav[$set.data.id_product] == 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a><a title="{l s='Remove product from my favorites' mod='pk_productscarousel_bottom'}"  href="#" class="remfav sec_bg_hvr dib button{if $isFav[$set.data.id_product] != 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a>
                          </div>{/if}{if ($wishlist.install == "installed") && ($wishlist.enable == "enabled")}<div class="function_button normview product_wishlist{if $isInWishList[$set.data.id_product] == 1} active{/if}">
                          <a href="#" class="wishlist_button sec_bg_hvr dib button addToWishlist" onclick="WishlistCart('wishlist_block_list', 'add', '{$set.data.id_product|intval}', false, 1, '.p{$set.data.id_product|intval} .image-cover'); return false;" data-wishid="{$set.data.id_product|intval}" title="{if $isInWishList[$set.data.id_product] == 1}{l s='This product is already in your wishlist' mod='pk_productscarousel_bottom'}{else}{l s='Add to Wishlist' mod='pk_productscarousel_bottom'}{/if}"><svg class="svgic svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg></a>
                          </div>
                          {/if}
                        </div>
                        {/if}
                        
                        <!-- END BESTSELLERS -->
                      {else}
                        <a class="f_title ellipsis" href="{$productLink}" title="{$set.data.legend}">{$set.data.name|escape:htmlall:'UTF-8'|truncate:70}</a>
                        {if $set.data.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                          {if isset($p_price) && ($p_price == 1)}
                            <div class="content_price smooth02 price-bg{if isset($set.data.specific_prices.price)} oldprice-exist{/if}">
                              {if isset($set.data.specific_prices.price)}
                              <span class="old-price product-price lmroman">{displayPrice price=$set.data.price_without_reduction}</span>
                              {/if}
                              <span class="price product-price lmroman">{displayPrice price=$set.data.price}</span>
                            </div>
                          {/if}
                        {/if}
                        {if isset($p_button) && ($p_button == 1)}
                        <div class="carousel-buttons smooth02">
                          {if ($set.data.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $set.data.available_for_order AND !isset($restricted_country_mode) AND $set.data.minimal_quantity == 1 AND $set.data.customizable != 2 AND !$PS_CATALOG_MODE}
                            {if ($set.data.quantity > 0 OR $set.data.allow_oosp)}
                              <a class="exclusive ajax_add_to_cart_button button dib" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$set.data.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" data-id-product="{$set.data.id_product|intval}"><span>{l s='Add to cart' mod='pk_productscarousel_bottom'}</span></a>{else}<a href="{$set.data.link}" class="button exclusive dib">{l s='View' mod='pk_productscarousel_bottom'}</a>{/if}{else}<a href="{$set.data.link}" class="button exclusive dib">{l s='Details' mod='pk_productscarousel_bottom'}</a>{/if}{if ($favorite.install == "installed") && ($favorite.enable == "enabled")}<div class="function_button normview product_like{if $isFav[$set.data.id_product] == 1} active{/if}"><a title="{l s='Add this product to my favorites' mod='pk_productscarousel_bottom'}" href="#!" class="addfav sec_bg_hvr button{if $isFav[$set.data.id_product] == 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a><a title="{l s='Remove product from my favorites' mod='pk_productscarousel_bottom'}"  href="#!" class="remfav sec_bg_hvr button{if $isFav[$set.data.id_product] != 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a>
                          </div>{/if}{if ($wishlist.install == "installed") && ($wishlist.enable == "enabled")}<div class="function_button normview product_wishlist{if $isInWishList[$set.data.id_product] == 1} active{/if}">
                          <a href="#" class="wishlist_button sec_bg_hvr button addToWishlist dib" onclick="WishlistCart('wishlist_block_list', 'add', '{$set.data.id_product|intval}', false, 1, '.p{$set.data.id_product|intval} .image-cover'); return false;" data-wishid="{$set.data.id_product|intval}" title="{if $isInWishList[$set.data.id_product] == 1}{l s='This product is already in your wishlist' mod='pk_productscarousel_bottom'}{else}{l s='Add to Wishlist' mod='pk_productscarousel_bottom'}{/if}"><svg class="svgic svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg></a>
                          </div>
                          {/if}
                        </div>
                        {/if}
                      {/if}               
                    </div>
                </div>
            </li>
            {/if}
            {/foreach}
        </ul>
        {else}
          <p class="alert alert-warning">{l s='There are no products right now' mod='pk_productscarousel_bottom'}</p>
        {/if}
        <div class="clearfix"></div>                    
      </div>
    </div>
</div>
{strip}
{addJsDefL name=favadd}{l s='has been added to your favorites' mod='pk_productscarousel_bottom' js=1}{/addJsDefL}
{addJsDefL name=favrem}{l s='has been removed from your favorites' mod='pk_productscarousel_bottom' js=1}{/addJsDefL}
{/strip}
<script>
$(document).ready(function() {
      $("#products-bottom").flexisel({
            pref: "btm",
            visibleItems: {if isset($bc_products_visible)}{$bc_products_visible}{else}5{/if},
            animationSpeed: 500,
            autoPlay: {if isset($bc_autoplay) && $bc_autoplay == 1}true{else}false{/if},
            autoPlaySpeed: 3000,            
            pauseOnHover: true,
            enableResponsiveBreakpoints: true,
            clone : true,
            responsiveBreakpoints: { 
                portrait: { 
                    changePoint:400,
                    visibleItems: 1
                }, 
                landscape: { 
                    changePoint:768,
                    visibleItems: 2
                },
                tablet: { 
                    changePoint:991,
                    visibleItems: 3
                },
                tablet_land: { 
                    changePoint:1199,
                    visibleItems: {if isset($bc_products_visible)}{$bc_products_visible}{else}5{/if}
                }
            }
      });
    $("#productsCarousel_bottom").find(".flexisel-nav").appendTo("#productsCarousel_bottom .carousel-title");

});
</script>
{/if}