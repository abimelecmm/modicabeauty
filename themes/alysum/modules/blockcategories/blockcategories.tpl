{if $blockCategTree && $blockCategTree.children|@count}
<!-- Block categories module -->
<div id="categories_block_left" class="block list-module">
	<h4 class="title_block">
		{if isset($currentCategory)}
			{$currentCategory->name|escape}
		{else}
			{l s='Categories' mod='blockcategories'}
		{/if}
	</h4>
	<div class="block_content">
		<ul class="tree {if $isDhtml}dhtml{/if}">
			{foreach from=$blockCategTree.children item=child name=blockCategTree}
				{if $smarty.foreach.blockCategTree.last}
					{include file="$branche_tpl_path" node=$child last='true'}
				{else}
					{include file="$branche_tpl_path" node=$child}
				{/if}
			{/foreach}
		</ul>
	</div>
</div>
<!-- /Block categories module -->
{/if}
