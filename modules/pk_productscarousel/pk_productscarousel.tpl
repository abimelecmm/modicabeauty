{if $page_name == "index"}
<!-- pk_productscarousel -->
<div class="productsCarousel carouselDesktop productsCarousel-middle load-animate homemodule">
    <div class="wht-bg tabs-wrapper">
      <ul class="tab-nav{if $products_kit|count > 1} several_tabs{/if}">
        {assign var="counter" value=1}
        {foreach from=$products_kit item=set name=set key=k}<li class="dib tab{if $active == $k} active{/if}"><a class="tab-link" onclick="return false" data-rel="{$counter++}"><h3><span class="dib">{if $k == "fea"}
            {l s='Featured' mod='pk_productscarousel'}
          {elseif $k == "new"}
            {l s='New' mod='pk_productscarousel'}
          {elseif $k == "spe"}
            {l s='Special' mod='pk_productscarousel'}
          {elseif $k == "bes"}
            {l s='Bestsellers' mod='pk_productscarousel'}
          {/if}
          </span></h3></a></li>{/foreach}
      </ul>
    </div>
    <div class="wht-bg forStart">
      <div class="indent">
        <div class="tab-slider">
        <div class="tab-slider-wrapper">      
          {foreach from=$products_kit item=set name=set key=k}
          <div class="accordionButton" data-tab-acc="{$smarty.foreach.set.index+1}"><span>{if $k == "fea"}{l s='Featured Products' mod='pk_productscarousel'}{elseif $k == "new"}{l s='New Products' mod='pk_productscarousel'}{elseif $k == "spe"}{l s='Special Products' mod='pk_productscarousel'}{elseif $k == "bes"}{l s='Bestsellers' mod='pk_productscarousel'}{/if}</span></div>
          <div class="accordionContent tab-content{if $active == $k} activeCarousel{/if}" id="{$k}-mid" data-acc="{$smarty.foreach.set.index+1}">
                <ul id="{$k}-products-mid" class="da-thumbs slides">
                  {if $set}                  
                  {foreach from=$set item=product name=products key=id}                      
                    {assign var='productLink' value=$link->getProductLink($product.data.id_product, $product.data.link_rewrite)}
                    <li class="block_product ajax_block_product slide smooth02" data-productid="{$product.data.id_product}">
                      <div class="carouselContainer">
                        <div class="slide-animate">

                          <a href="{$productLink}" title="{$product.data.legend}" class="product_image p{$product.data.id_product}">

                            <img src="{$link->getImageLink($product.data.link_rewrite, "{$product.prodCover.id_product}-{$product.prodCover.id_image}", 'home_'|cat:$cookie->img_name)}" alt="{$product.data.name|escape:html:'UTF-8'}" class="add_to_cart_image" />

                            {if isset($p_hover) && ($p_hover == 1)}
                              {if isset($product.image)}
                                <div class="additional-image smooth02">                    
                                  <img src="{$link->getImageLink($product.data.link_rewrite, "{$product.data.id_product}-{$product.image}", 'home_'|cat:$cookie->img_name)}" alt="{$product.data.name|escape:html:'UTF-8'}" />                    
                                </div>
                              {/if}
                            {/if}

                            <span class="labels">
                              {if isset($product.data.new) && $product.data.new == 1}<span class="pk-new sec_bg">{l s='New' mod='pk_productscarousel'}</span>{/if}
                              {if $product.data.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                                  {if isset($product.data.specific_prices.price)}
                                    <span class="pk-reduction">
                                      {if $product.data.specific_prices.reduction_type == "amount"}
                                          -{convertPrice price=$product.data.specific_prices.reduction}
                                      {else}
                                          -{$product.data.specific_prices.reduction*100|round:2}%        
                                      {/if}
                                    </span>
                                  {/if}                            
                              {/if}
                            </span>

                          </a>

                          {if isset($quick_view) && $quick_view}
                            <a class="quick-view" href="{$product.data.link|escape:'html':'UTF-8'}" rel="{$product.data.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}"><svg class="svgic svgic-search"><use xlink:href="#si-search"></use></svg></a>
                          {/if}
                          {if ($pc_countdown == true)}
                                {assign var=to value="-"|explode:$product.data.specific_prices.to} 
                                {if isset($product.data.specific_prices.to) && ($to[0] != "0000")}
                                  <div class="countdown countdown-{$product.data.id_product}" title="{l s='To the end of this offer'}"></div>
                                  <script>
                                  $(document).ready(function(){
                                    $(function() {
                                      $('.productsCarousel .countdown-{$product.data.id_product}').countdown({
                                          date: "{$product.data.specific_prices.to|replace:' ':'T'}",
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

                        </div>

                        <div class="bottom_block">  
                        <!-- BESTSELLERS -->
                         {if ($comments["install"] == "installed") && ($comments["enable"] == "enabled") && ($p_rating == 1)}
                            <span class="rate_block smooth05">
                              {hook h='displayProductListReviews' product=$product.data}
                            </span>      
                            {/if}
                          {if $products_types=="bes"}
                          <a class="f_title ellipsis" href="{$productLink}" title="{$product.data.legend}">{$product.data.name|escape:htmlall:'UTF-8'|truncate:35}</a>
                            {if $product.data.price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                              {if isset($p_price) && ($p_price == 1)}
                                <div class="content_price smooth02 price-bg{if isset($product.data.specific_prices.price)} oldprice-exist{/if}">
                                  {if isset($product.data.specific_prices.price)}
                                  <span class="old-price dib product-price lmroman">{displayPrice price=$product.data.price_without_reduction}</span>
                                  {/if}
                                  <span class="price div product-price lmroman">{displayPrice price=$product.data.price}</span>
                                </div>
                              {/if}
                            {/if}
                            
                            {if isset($p_button) && ($p_button == 1)}
                            <div class="carousel-buttons smooth02">
                              {if ($product.data.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.data.available_for_order AND !isset($restricted_country_mode) AND $product.data.minimal_quantity == 1 AND $product.data.customizable != 2 AND !$PS_CATALOG_MODE}
                                {if ($product.data.quantity > 0 OR $product.data.allow_oosp)}
                                  <a class="exclusive ajax_add_to_cart_button button dib" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.data.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" data-id-product="{$product.data.id_product|intval}"><span>{l s='Add to cart' mod='pk_productscarousel'}</span></a>{else}<a href="{$product.data.link}" class="button dib exclusive altview">{l s='View' mod='pk_productscarousel'}</a>{/if}{else}<a href="{$product.data.link}" class="button exclusive dib">{l s='Details' mod='pk_productscarousel'}</a>{/if}{if ($favorite.install == "installed") && ($favorite.enable == "enabled")}<div class="function_button product_like{if $isFav[$product.data.id_product] == 1} active{/if}"><a title="{l s='Add this product to my favorites' mod='pk_productscarousel'}" href="#" class="addfav sec_bg_hvr dib button{if $isFav[$product.data.id_product] == 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a><a title="{l s='Remove product from my favorites' mod='pk_productscarousel'}"  href="#" class="remfav sec_bg_hvr dib button{if $isFav[$product.data.id_product] != 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a>
                              </div>{/if}{if ($wishlist.install == "installed") && ($wishlist.enable == "enabled")}<div class="function_button product_wishlist{if $isInWishList[$product.data.id_product] == 1} active{/if}">
                              <a href="#" class="wishlist_button sec_bg_hvr dib button addToWishlist" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.data.id_product|intval}', false, 1, '.p{$product.data.id_product|intval} .image-cover'); return false;" data-wishid="{$product.data.id_product|intval}" title="{if $isInWishList[$product.data.id_product] == 1}{l s='This product is already in your wishlist' mod='pk_productscarousel'}{else}{l s='Add to Wishlist' mod='pk_productscarousel'}{/if}"><svg class="svgic svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg></a>
                              </div>
                              {/if}
                            </div>
                            {/if}
                          
                            <!-- END BESTSELLERS -->
                          {else}
                            <a class="f_title ellipsis" href="{$productLink}" title="{$product.data.legend}">{$product.data.name|escape:htmlall:'UTF-8'|truncate:35}</a>
                            {if $product.data.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                              {if isset($p_price) && ($p_price == 1)}
                                <div class="content_price smooth02 price-bg{if isset($product.data.specific_prices.price)} oldprice-exist{/if}">
                                  {if isset($product.data.specific_prices.price)}
                                  <span class="old-price dib product-price lmroman">{displayPrice price=$product.data.price_without_reduction}</span>
                                  {/if}
                                  <span class="price dib product-price lmroman">{displayPrice price=$product.data.price}</span>
                                </div>
                              {/if}
                            {/if}
                            {if isset($p_button) && ($p_button == 1)}
                            <div class="carousel-buttons smooth02">
                              {if ($product.data.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.data.available_for_order AND !isset($restricted_country_mode) AND $product.data.minimal_quantity == 1 AND $product.data.customizable != 2 AND !$PS_CATALOG_MODE}
                                  {if ($product.data.quantity > 0 OR $product.data.allow_oosp)}
                                    <a class="exclusive ajax_add_to_cart_button button dib" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.data.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" data-id-product="{$product.data.id_product|intval}"><span>{l s='Add to cart' mod='pk_productscarousel'}</span></a>
                                  {else}
                                    <a href="{$product.data.link}" class="button exclusive dib">{l s='View' mod='pk_productscarousel'}</a>
                                  {/if}
                                {else}
                                  <a href="{$product.data.link}" class="button exclusive dib">{l s='Details' mod='pk_productscarousel'}</a>
                                {/if}
                                {if ($favorite.install == "installed") && ($favorite.enable == "enabled")}<div class="function_button product_like{if $isFav[$product.data.id_product] == 1} active{/if}"><a title="{l s='Add this product to my favorites' mod='pk_productscarousel'}" href="#!" class="addfav sec_bg_hvr button{if $isFav[$product.data.id_product] == 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a><a title="{l s='Remove product from my favorites' mod='pk_productscarousel'}"  href="#!" class="remfav sec_bg_hvr button{if $isFav[$product.data.id_product] != 1} hidden{/if}"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg></a>
                              </div>{/if}{if ($wishlist.install == "installed") && ($wishlist.enable == "enabled")}<div class="function_button product_wishlist{if $isInWishList[$product.data.id_product] == 1} active{/if}">
                              <a href="#" class="wishlist_button sec_bg_hvr button addToWishlist dib" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.data.id_product|intval}', false, 1, '.p{$product.data.id_product|intval} .image-cover'); return false;" data-wishid="{$product.data.id_product|intval}" title="{if $isInWishList[$product.data.id_product] == 1}{l s='This product is already in your wishlist' mod='pk_productscarousel'}{else}{l s='Add to Wishlist' mod='pk_productscarousel'}{/if}"><svg class="svgic svgic-wishlist"><use xlink:href="#si-wishlist"></use></svg></a>
                              </div>
                              {/if}
                            </div>
                            {/if}
                          {/if}               
                        </div>
                    </div>
                  {/foreach}
                  {/if}
              </ul>
              <div class="clearfix"></div>
            </div>
                <script type="text/javascript">
                  var visible = {$visible_products};
                  if ( $('#left_column')[0] )
                    visible = (visible - 1);

                  jQuery(document).ready(function() {
                    tabslider();
                    $("#{$k}-products-mid").flexisel({
                          pref: "{$k}",
                          visibleItems: visible,
                          animationSpeed: 500,
                          autoPlay: false,
                          autoPlaySpeed: 4500,            
                          pauseOnHover: true,
                          enableResponsiveBreakpoints: true,
                          clone : true,
                          responsiveBreakpoints: { 
                               portrait: { 
                                    changePoint:400,
                                    visibleItems: 1
                                }, 
                                landscape: { 
                                    changePoint:728,
                                    visibleItems: 2
                                },
                                tablet: { 
                                    changePoint:980,
                                    visibleItems: 3
                                },
                                tablet_land: { 
                                    changePoint:1170,
                                    visibleItems: 3
                                }
                          }
                      });

                      var currentBreakpoint; // default's to blank so it's always analysed on first load
                      var didResize  = true; // default's to true so it's always analysed on first load

                      $(window).resize(function() {
                        didResize = true;
                      });
                      setInterval(function() {
                        if(didResize) {
                          didResize = false;

                          var newBreakpoint = $(window).width();

                            if (newBreakpoint > 1170) 
                                newBreakpoint = "breakpoint_1";
                            else if ((newBreakpoint <= 1170) && (newBreakpoint >= 980)) 
                                newBreakpoint = "breakpoint_2";
                            else if ((newBreakpoint <= 979) && (newBreakpoint >= 728)) 
                                newBreakpoint = "breakpoint_3";
                            else if ((newBreakpoint <= 727) && (newBreakpoint >= 400)) 
                                newBreakpoint = "breakpoint_4";
                            else if (newBreakpoint <= 399) 
                                newBreakpoint = "breakpoint_5";

                          // if the new breakpoint is different to the old one, do some stuff
                          if (currentBreakpoint != newBreakpoint) {                         

                            if (newBreakpoint === 'breakpoint_1') {
                              currentBreakpoint = 'breakpoint_1';
                              $(".productsCarousel").removeClass("carouselMobile").addClass("carouselDesktop");
                              tabslider();
                            }
                            if (newBreakpoint === 'breakpoint_2') {
                                currentBreakpoint = 'breakpoint_2';                                
                                $(".productsCarousel").removeClass("carouselMobile").addClass("carouselDesktop");
                                tabslider();
                            }   
                            if (newBreakpoint === 'breakpoint_3') {                                
                                currentBreakpoint = 'breakpoint_3';                                
                                $(".productsCarousel").removeClass("carouselMobile").addClass("carouselDesktop");
                                tabslider();
                            } 
                            if (newBreakpoint === 'breakpoint_4') {                                
                              $(".productsCarousel").removeClass("carouselDesktop").addClass("carouselMobile");
                                currentBreakpoint = 'breakpoint_4';                                
                                tabslider();
                            } 
                            if (newBreakpoint === 'breakpoint_5') {                                
                                currentBreakpoint = 'breakpoint_5';                                
                                $(".productsCarousel").removeClass("carouselDesktop").addClass("carouselMobile");
                                tabslider();
                            } 
                          }
                        }
                      }, 250);                                   
                  }); 
                </script>                              
            {/foreach}
        </div>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
$(window).load(function() {
  $('.productsCarousel-middle a.tab-link').click(function() {
      var width = $(".productsCarousel-middle .tab-slider").width(); //The width in pixels of your #tab-slider 
      var delay = 200; // Pause time between animation in Milliseconds   
      var rel = $(this).data('rel');
      $(".productsCarousel-middle .tab-content").removeClass("activeCarousel");
      $(".productsCarousel-middle").find("[data-acc='" + rel + "']").addClass("activeCarousel");      
      $('.productsCarousel-middle .tab').removeClass('active');
      $(this).parent().addClass('active');
      var $contentNum = parseInt($(this).data('rel'), 10);
      $('.productsCarousel-middle .tab-slider-wrapper').animate({ marginLeft: '-' + (width * $contentNum - width) }, delay, 'easeOutQuint');
      return false;
  });
});
function tabslider() {
  var width = $(".productsCarousel-middle .tab-slider").width(), //The width in pixels of your #tab-slider 
  $tabs = $('.productsCarousel-middle .tab'); //Your Navigation Class Name      
  var rel = $('.productsCarousel-middle .tab-nav').find('.active').find('a').data('rel');
  var shift = Math.abs(width*rel-width) * -1;
  $(".productsCarousel-middle .tab-content").width(width);  
  $('.productsCarousel-middle .tab-slider-wrapper').css({ "margin-left": shift, width: $tabs.length * width }); 

  var width2 = $(".carouselMobile .tab-slider").width(), //The width in pixels of your #tab-slider 
  $tabs = $('.carouselMobile .tab'); //Your Navigation Class Name      
  var rel = $('.carouselMobile .tab-nav').find('.active').find('a').data('rel');
  var shift = Math.abs(width2*rel-width2) * -1;
  $(".carouselMobile .tab-content").width(width2);  
  $('.carouselMobile .tab-slider-wrapper').css({ "margin-left": shift, width: $tabs.length * width2 }); 
}
</script>
{/if}