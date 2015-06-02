<!-- Module pk_aboutus -->
<div id="pk_aboutus_block_center" class="pk_aboutus_block homemodule load-animate">
	{if $pk_aboutus->body_home_logo_link}<a href="{$pk_aboutus->body_home_logo_link|escape:'html':'UTF-8'}" title="{$pk_aboutus->body_title|escape:'html':'UTF-8'|stripslashes}">{/if}
	{if $homepage_logo}<img class="img-responsive" src="{$link->getMediaLink($image_path)|escape:'html'}" alt="{$pk_aboutus->body_title|escape:'html':'UTF-8'|stripslashes}" {if $image_width}width="{$image_width}"{/if} {if $image_height}height="{$image_height}" {/if}/>{/if}
	{if $pk_aboutus->body_home_logo_link}</a>{/if}
	<div class="pk_aboutus_text">
		{if $pk_aboutus->body_title}<h6 class="lmromancaps">{$pk_aboutus->body_title|stripslashes}</h6>{/if}
		{if $pk_aboutus->body_paragraph}<div class="rte">{$pk_aboutus->body_paragraph|stripslashes}</div>{/if}
	</div>
</div>
<!-- /Module pk_aboutus -->
