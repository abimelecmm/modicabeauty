<ul>
{foreach from=$virtualProducts item=product}
	<li>
		<a href="{$product.link}">{$product.name}</a>
		{if isset($product.deadline)}
			galioja iki {$product.deadline}
		{/if}
		{if isset($product.downloadable)}
			galite atsisiøsti {$product.downloadable} kartus(ø)
		{/if}
	</li>
{/foreach}
</ul>