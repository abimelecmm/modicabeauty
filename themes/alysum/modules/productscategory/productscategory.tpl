{if count($categoryProducts) > 0 && $categoryProducts !== false}
<div class="clearfix blockproductscategory">
	<div class="productscategory_h2">{l s='Related products' mod='productscategory'}</div>
	<div id="{if count($categoryProducts) > 2}productscategory{else}productscategory_noscroll{/if}">
	<div id="productscategory_slider">
	<div id="productscategory_list">
		<ul>
			{foreach from=$categoryProducts item='categoryProduct' name=categoryProduct}<!--{if count($categoryProducts) < 6}style="width: {math equation="width / nbImages" width=94 nbImages=$categoryProducts|@count}%"{/if}--> 
			<li>
				<div class="li-indent">
				<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="lnk_img" title="{$categoryProduct.name|htmlspecialchars}"><img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'home_'|cat:$cookie->img_name)}" alt="{$categoryProduct.name|htmlspecialchars}" /></a>
			</div>
			</li>
			{/foreach}
		</ul>
	</div>
	</div>
	</div>
	{if count($categoryProducts) > 2}
	<script type="text/javascript">		
		$("#productscategory_list ul").flexisel({
			pref: "pc",
	        visibleItems: 3,
	        animationSpeed: 1000,
	        autoPlay: true,
	        autoPlaySpeed: 3000,            
	        pauseOnHover: true,
	        enableResponsiveBreakpoints: true,
	        responsiveBreakpoints: { 
	            portrait: { 
	                changePoint:480,
	                visibleItems: 1
	            }, 
	            landscape: { 
	                changePoint:728,
	                visibleItems: 2
	            },
	            tablet: { 
	                changePoint:980,
	                visibleItems: 3
	            }
	        }
	    });
	</script>
	{/if}
</div>
{/if}