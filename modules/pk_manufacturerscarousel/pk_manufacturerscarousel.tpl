<!-- Manufacturers Carousel module -->
<div id="manufacturersCarousel" class="homemodule load-animate">
	{if $manufacturers}
		<ul id="m-list">
		{foreach from=$manufacturers item=manufacturer name=manufacturer_list}
			{if $smarty.foreach.manufacturer_list.iteration <= $text_list_nb}		
			<li class="{if $smarty.foreach.manufacturer_list.last}last_item{elseif $smarty.foreach.manufacturer_list.first}first_item{else}item{/if}">
				<div class="manuf-indent">
					<a class="smooth02" href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)}" title="{l s='More about' mod='pk_manufacturerscarousel'} {$manufacturer.name}">
					<img class="dib" src="{$img_manu_dir}{$manufacturer.id_manufacturer}-manu_alysum.jpg" alt="" />
					</a>
					{if isset($show_title) AND $show_title == 1}
					<span class="lmroman">{$manufacturer.name|escape:'htmlall':'UTF-8'}</span>{/if}
				</div>
			</li>
			{/if}
		{/foreach}
		</ul>	
	{else}
		<p>{l s='No manufacturer' mod='pk_manufacturerscarousel'}</p>
	{/if}
	{if $manufacturers && ($manufacturers|count >= 6)}
	<script>
	$("#m-list").flexisel({
		pref: "mnf",
        visibleItems: 6,
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
                visibleItems: 5
            },
            tablet_land: { 
                changePoint:1170,
                visibleItems: 6
            }
        }
    });
	</script>
{/if}
</div>	
<!-- /Manufacturers Carousel module -->