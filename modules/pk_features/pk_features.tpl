{if $infos|@count > 0}
<!-- MODULE Block pk_features -->
<div id="pk_features" class="clearfix load-animate homemodule">
	<div class="page_width">
	<h4 class="lmromandemi">{l s='alysum features' mod='pk_features'}</h4>
	<ul>	
		{foreach from=$infos item=info}
			<li class="col-xs-12 col-sm-3">
				<div class="pkf-indent">
				{if $info.file_name != ""}
				<div class="img-wrapper main_bg"><img src="{$link->getMediaLink("`$module_dir`img/`$info.file_name|escape:'htmlall':'UTF-8'`")}" alt="{$info.text|escape:html:'UTF-8'}" /></div>{/if}<h5 class="lmromandemi">{$info.title|escape:html:'UTF-8'}</h5><span>{$info.text|escape:html:'UTF-8'}</span>{if ($info.url != "")}<a class="button" href="{$info.url}">{l s='Read more' mod='pk_features'}</a>{/if}
				</div>
			</li>
		{/foreach}
	</ul>
	</div>
</div>

<!-- /MODULE Block pk_features -->
{/if}