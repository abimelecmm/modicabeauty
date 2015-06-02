{assign var=state value="."|explode:$theme_settings.modules.displayBanner.blockbanner} 
{if $state.1 == 1}
<div class="topbanner">
<a href="{if $banner_link}{$banner_link|escape:'htmlall':'UTF-8'}{else}{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}{/if}" title="{$banner_desc|escape:'htmlall':'UTF-8'}">
{if isset($banner_img)}
	<img class="img-responsive dib" src="{$banner_img|escape:'htmlall':'UTF-8'}" alt="{$banner_desc|escape:'htmlall':'UTF-8'}" title="{$banner_desc|escape:'htmlall':'UTF-8'}" width="1170" height="65" />
{else}
	{$banner_desc|escape:'htmlall':'UTF-8'}
{/if}
</a>
</div>
{/if}