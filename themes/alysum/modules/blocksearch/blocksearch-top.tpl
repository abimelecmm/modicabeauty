<!-- Block search module TOP -->
<div id="search_block_top" class="smooth05">
	<form id="searchbox" method="get" action="{$link->getPageLink('search', null, null, null, false, null, true)|escape:'html':'UTF-8'}" >
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query" type="text" id="search_query_top" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if}" placeholder="{l s='Search...' mod='blocksearch'}" />
		<button type="submit" name="submit_search" class="searchbutton smooth02 main_bg_hvr">
			<svg class="svgic svgic-search smooth02 main_color"><use xlink:href="#si-search"></use></svg>
		</button>
	</form>
</div>
<!-- /Block search module TOP -->