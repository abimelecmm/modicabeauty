{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}{l s='New products'}{/capture}

<h1 class="page-heading product-listing">{l s='New products'}</h1>

{if $products}
	<div class="content_sortPagiBar">				
		<div class="sortPagiBar clearfix">
			<div class="views_float">				
				{include file="./product-sort.tpl"}											
				{include file="./nbr-product-page.tpl"}
			</div>
			{if isset($theme_settings.allcookies.listingView)}
				{if $theme_settings.allcookies.listingView == 'view_grid'}
					{assign var='view_type' value="view_grid"}
				{/if}
				{if $theme_settings.allcookies.listingView == 'view_list'}
					{assign var='view_type' value="view_list"}
				{/if}
			{else}
				{if $theme_settings.view == 'view_grid'}
					{assign var='view_type' value="view_grid"}
				{/if}
				{if $theme_settings.view == 'view_list'}
					{assign var='view_type' value="view_list"}
				{/if}
			{/if}
			{if isset($theme_settings) && ($theme_settings.lc_buttons == 1)}
			<div class="views dib">
				<div class="view_btn dib{if $view_type == 'view_grid'} active{/if} smooth02" id="view_grid" title="grid"></div><span class="grid_title">{l s='Grid'}</span>
				<div class="view_btn dib{if $view_type == 'view_list'} active{/if} smooth02" id="view_list" title="list"></div><span class="list_title">{l s='List'}</span>
			</div>					
			{/if}				
		</div>
	</div>
	<div id="listing_view" class="{$view_type}">
		{include file="./product-list.tpl" products=$products}
	</div>
	<div class="sortPagiBar sortPagiBarFooter clearfix">
		{include file="./pagination.tpl"}
	</div>
	{else}
	<p class="alert alert-warning">{l s='No new products.'}</p>
{/if}
