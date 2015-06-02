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
{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
	{if $category->id AND $category->active}
		<h1 class="page-heading{if (isset($subcategories) && !$products) || (isset($subcategories) && $products) || !isset($subcategories) && $products} product-listing{/if}">
			{$category->name|escape:'html':'UTF-8'}{if isset($categoryNameComplement)}&nbsp;{$categoryNameComplement|escape:'html':'UTF-8'}{/if}
		</h1>

		{if $scenes || $category->description || $category->id_image}
			<div class="content_scene_cat">
            	 {if $scenes}
                 	<div class="content_scene">
                        <!-- Scenes -->
                        {include file="$tpl_dir./scenes.tpl" scenes=$scenes}
                        {if $category->description}
                            <div class="cat_desc">
                            {if strlen($category->description) > 350}
                                <div id="category_description_short">{$description_short}</div>
                                <div id="category_description_full" style="display:none">{$category->description}</div>
                                <a href="#" onclick="$('#category_description_short').hide(); $('#category_description_full').show(); $(this).hide(); return false;" class="lnk_more">{l s='More'}</a>
                            {else}
                                <div>{$category->description}</div>
                            {/if}
                            </div>
                        {/if}
                        </div>
                  {else}
                    <!-- Category image -->
                    <div class="content_scene_cat_bg" {if $category->id_image}{/if}>
                    	<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}" alt="" title="" />
                        {if $category->description}
                        {if isset($theme_settings) && ($theme_settings.cat_title == 1)}
                            <div class="cat_desc">
	                            <h2 class="category-name trajan">
	                                {strip}
	                                    {$category->name|escape:'html':'UTF-8'}
	                                    {if isset($categoryNameComplement)}
	                                        {$categoryNameComplement|escape:'html':'UTF-8'}
	                                    {/if}
	                                {/strip}
	                            </h2>
	                            {if strlen($category->description) > 350}
	                                <div id="category_description_short">{$description_short}</div>
	                                <div id="category_description_full" style="display:none">{$category->description}</div>
	                                <a href="#" onclick="$('#category_description_short').hide(); $('#category_description_full').show(); $(this).hide(); return false;" class="lnk_more">{l s='More'}</a>
	                            {else}
	                                <div>{$category->description}</div>
	                            {/if}
                            </div>
                        {/if}
                     {/if}
                     </div>
                  {/if}
            </div>
		{/if}
		{if isset($subcategories)}
		<!-- Subcategories -->
			{if isset($theme_settings) && ($theme_settings.subcategories == 1)}
			<div id="subcategories">
				<h3 class="subcategory-heading">{l s='Subcategories'}</h3>
				<ul class="inline_list">
				{foreach from=$subcategories item=subcategory}
					<li>
	                	<div class="subcategory-image">
							<a class="img" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
							{if $subcategory.id_image}
								<img class="replace-2x" src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'category_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}" alt="" width="{$categorySize.width}" height="{$categorySize.height}" />
							{else}
								<img class="replace-2x" src="{$img_cat_dir}default-category_{$cookie->img_name}.jpg" alt="" width="{$mediumSize.width}" height="{$mediumSize.height}" />
							{/if}
						</a>
	                   	</div>
						<h5><a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'|truncate:350}</a></h5>
						{if $subcategory.description}
							<div class="cat_desc">{$subcategory.description}</div>
						{/if}
					</li>
				{/foreach}
				</ul>
			</div>
			{/if}
		{/if}

		{if $products}
			<div class="content_sortPagiBar">				
				<div class="sortPagiBar clearfix">
					<div class="views_float">
						{include file="./product-compare.tpl"}
						{include file="./product-sort.tpl"}											
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
			<div class="content_sortPagiBar content_sortPagiBarFooter">
				<div class="sortPagiBar sortPagiBarFooter clearfix">
					{include file="./nbr-product-page.tpl" paginationId='bottom'}
					{include file="./pagination.tpl" paginationId='bottom'}
				</div>
			</div>
		{else}
			<p class="warning alert alert-warning">{l s='There are no products in this category.'}</p>
		{/if}
	{elseif $category->id}
		<p class="warning alert alert-warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}