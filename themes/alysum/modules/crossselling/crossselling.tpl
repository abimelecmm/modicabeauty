{if isset($orderProducts) && count($orderProducts)}
<div id="crossselling">
	<div class="productscategory_h2 lmromancaps">{l s='Also purchased products' mod='crossselling'}</div>
		<div id="productscategory_slider">
		<div id="crossselling_list">
			<ul>
				{foreach from=$orderProducts item='orderProduct' name=orderProduct}
				<li>
					<div class="li-indent">
					<a href="{$orderProduct.link}" title="{$orderProduct.name|htmlspecialchars}" class="lnk_img">
						{assign var="alysum_image" value=$orderProduct.image|replace:'medium_default':'home_default'}
						<img src="{$alysum_image|lower}" alt="{$orderProduct.name|htmlspecialchars}" />						
					</a>
					<div class="product_name hidden">
						<a href="{$orderProduct.link}" title="{$orderProduct.name|htmlspecialchars}">{$orderProduct.name|truncate:15:'...'|escape:'htmlall':'UTF-8'}</a>
					</div>
					{if $crossDisplayPrice AND $orderProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
						<span class="price_display hidden">
							<span class="price">{convertPrice price=$orderProduct.displayed_price}</span>
						</span>
					{/if}
					</div>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
	{if count($orderProducts) >= 4}
	<script type="text/javascript">		
		$("#crossselling_list ul").flexisel({
			pref: "cross",
	        visibleItems: 4,
	        animationSpeed: 1000,
	        autoPlay: false,
	        autoPlaySpeed: 3000,            
	        pauseOnHover: true,
	        enableResponsiveBreakpoints: true,
	        responsiveBreakpoints: { 
	            portrait: { 
	                changePoint:480,
	                visibleItems: 2
	            }, 
	            landscape: { 
	                changePoint:728,
	                visibleItems: 3
	            },
	            tablet: { 
	                changePoint:980,
	                visibleItems: 4
	            }
	        }
	    });
	</script>
	{/if}
</div>
{/if}
