{*
* 2007-2015 mitsos1os
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
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize the module for your
* needs please refer to
*
*  @author mitsos1os
*  @copyright  2014-2015 mitsos1os
*}

<div id="product-extraTabs" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" id="submitted_tabs[]" value="ModuleExtraproducttab" />
    <h3 class="tab"> <i class="icon-info"></i> {l s='Extra Product Tabs' mod='extraproducttab'}</h3>
    {$counter = 0}
	{foreach from=$extraTabs item=extraTab}
        {$tabID = $extraTab['id_Tab']}
        {*
        ***    find the tab displaynames,status for the product and content for each language
        *}
        {$active = 1}
        {foreach from = $productTabsActivation item = productTabActivation}
            {if $productTabActivation['id_Tab'] == $tabID}
                {if $productTabActivation['notActive']}
                    {$active = 0}
                {/if}
                {break}
            {/if}
        {/foreach}
        {foreach from = $languages item = language}
            {$tabLangNames[$language['id_lang']] = '' }
            {$tabLangContent[$language['id_lang']] = ''}
            {foreach from = $tabDisplayNames item = tabDisplayName}
                {if $tabDisplayName['id_Tab'] == $tabID && $tabDisplayName['id_lang'] == $language['id_lang']}
                    {$tabLangNames[$language['id_lang']] = $tabDisplayName['displayname']}
                    {break}
                {/if}
            {/foreach}
            {foreach from = $productTabsContent item = productTabContent}
                {if $productTabContent['id_Tab'] == $tabID && $productTabContent['id_lang'] == $language['id_lang']}
                    {$tabLangContent[$language['id_lang']] = $productTabContent['content']}
                    {break}
                {/if}
            {/foreach}
        {/foreach}
        <fieldset id="fieldset_{$counter|intval}">
		<legend>
			{$extraTab['name']|escape:'htmlall':'UTF-8'} {l s='Content' mod='extraproducttab'}
        </legend>
		<label>
			{l s='Tab display name' mod='extraproducttab'}
        </label>
		<div class="margin-form">
			 <div class="translatable">
                {foreach from=$languages item=language}
                <div class="lang_{$language.id_lang}" style="{if !$language.is_default}display:none;{/if}float: left;">
                    <input size="30" type="text" id="extraTab_{$tabID|intval}_displayName_{$language.id_lang}" 
                    name="extraTab_{$tabID|intval}_displayName_{$language.id_lang}"
                        value="{$tabLangNames[$language.id_lang]|htmlentitiesUTF8|default:''}"
                        onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();" onblur="updateLinkRewrite();"/>
                </div>
                {/foreach}
            </div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
        <label>
			{l s='Active on this product' mod='extraproducttab'}
        </label>
		<div class="margin-form">
            <input name="extraTab_{$tabID|intval}_active_on" id="extraTab_{$tabID|intval}_active_on" class="" value="1" {if $active == 1 }checked="checked"{/if} type="checkbox">
			<br />
		</div>
		<div class="clear"></div>
		<label>
			{l s='Content' mod='extraproducttab'}
        </label>
		<div class="margin-form">
			<div class="translatable">
                {foreach from=$languages item=language}
                <div class="lang_{$language.id_lang}" style="{if !$language.is_default}display:none;{/if}float: left;">
                    <textarea cols="100" rows="10" id="extraTab_{$tabID|intval}_content_{$language.id_lang}" 
                        name="extraTab_{$tabID|intval}_content_{$language.id_lang}" 
                        class="extraProductTab_rte" >{if isset($tabLangContent[$language.id_lang])}{$tabLangContent[$language.id_lang]|htmlentitiesUTF8}{/if}</textarea>
                    <span class="counter" max="{if isset($max)}{$max}{else}none{/if}"></span>
                    <span class="hint">{$hint|default:''}<span class="hint-pointer">&nbsp;</span></span>
                </div>
                {/foreach}
            </div>
		    <div class="clear"></div>
		</div>
        <hr />
       </fieldset>
        {$counter = $counter + 1}
    {/foreach}
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#product-tab-content-ModuleExtraproducttab").on('loaded', function(){
            tinySetup({
                editor_selector :"extraProductTab_rte"
            });
            displayFlags(languages, id_language, allowEmployeeFormLang);
        });
    });
</script>
