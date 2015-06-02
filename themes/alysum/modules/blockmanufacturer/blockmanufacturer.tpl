<!-- Block manufacturers module -->
<div id="manufacturers_block_left" class="block blockmanufacturer list-module">
	<h4 class="title_block">{if $display_link_manufacturer}<a href="{$link->getPageLink('manufacturer')|escape:'html'}" title="{l s='Manufacturers' mod='blockmanufacturer'}">{/if}{l s='Brands' mod='blockmanufacturer'}{if $display_link_manufacturer}</a>{/if}</h4>
	<div class="block_content">
{if $manufacturers}
	{if $text_list}
	<ul class="bullet">
	{foreach from=$manufacturers item=manufacturer name=manufacturer_list}
		{if $smarty.foreach.manufacturer_list.iteration <= $text_list_nb}
		<li class="{if $smarty.foreach.manufacturer_list.last}last_item{elseif $smarty.foreach.manufacturer_list.first}first_item{else}item{/if}"><a href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}" title="{l s='More about %s' sprintf=[$manufacturer.name] mod='blockmanufacturer'}">{$manufacturer.name|escape:'html':'UTF-8'}</a></li>
		{/if}
	{/foreach}
	</ul>
	{/if}
	{if $form_list}
		<form action="{$smarty.server.SCRIPT_NAME|escape:'html':'UTF-8'}" method="get">
			<p>
				<select id="manufacturer_list" onchange="autoUrl('manufacturer_list', '');">
					<option value="0">{l s='All manufacturers' mod='blockmanufacturer'}</option>
				{foreach from=$manufacturers item=manufacturer}
					<option value="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
				{/foreach}
				</select>
			</p>
		</form>
	{/if}
{else}
	<p>{l s='No manufacturer' mod='blockmanufacturer'}</p>
{/if}
	</div>
</div>
<!-- /Block manufacturers module -->
