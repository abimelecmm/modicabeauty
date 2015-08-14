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
    <input type="hidden" name="submitted_tabs[]" value="ModuleExtraproducttab" />
    <h3 class="tab"> <i class="icon-info"></i> {l s='Extra Product Tabs' mod='extraproducttab'}</h3>
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
        <div class="panel-heading">
            {$extraTab['name']|escape:'htmlall':'UTF-8'} {l s='Content' mod='extraproducttab'}
        </div>

        <div class="form-group">
            <label for="extraTab_{$tabID|intval}_displayName" class="control-label col-lg-3 ">
                {l s='Tab display name' mod='extraproducttab'}
            </label>
            {*
            ***    find the tab displayName for each languages
            *}
            <div class="col-lg-5">
                {include file="controllers/products/input_text_lang.tpl"
                languages=$languages
                input_value=$tabLangNames
                input_name="extraTab_{$tabID|intval}_displayName"
                }
            </div>
        </div>

        <div class ="form-group">
            <label for="extraTab_{$tabID|intval}_active" class="control-label col-lg-3 ">
                {l s='Active on this product' mod='extraproducttab'}
            </label>
            <div class="col-lg-5 ">
                <div class="checkbox">
                    <label for="extraTab_{$tabID|intval}_active_on">
                        <input name="extraTab_{$tabID|intval}_active_on" id="extraTab_{$tabID|intval}_active_on" class="" value="1" {if $active == 1 }checked="checked"{/if} type="checkbox">
                    </label>
                </div>
            </div>
        </div>
        <div class ="form-group">
            <label for="extraTab_{$tabID|intval}_content" class="control-label col-lg-3 ">
                {l s='Content' mod='extraproducttab'}
            </label>
            {*
            ***    find the tab content for each language
            *}

            <div class="col-lg-9 ">
                {include
                file="controllers/products/textarea_lang.tpl"
                languages=$languages
                input_name="extraTab_{$tabID|intval}_content"
                class="extraProductTab_rte"
                input_value=$tabLangContent
                }
            </div>
        </div>
        <hr />

    {/foreach}
    <div class="panel-footer">
        <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='extraproducttab'}</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='extraproducttab'}</button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='extraproducttab'}</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        // Execute when tab Informations has finished loading
        tabs_manager.onLoad('ModuleExtraproducttab', function(){
            tinySetup({
                editor_selector :"extraProductTab_rte",
                setup : function(edexTab) {
                    edexTab.on('init', function(edexTab)
                    {
                        if (typeof ProductMultishop.load_tinymce[edexTab.id] != 'undefined')
                        {
                            if (typeof ProductMultishop.load_tinymce[edexTab.id])
                                edexTab.hide();
                            else
                                edexTab.show();
                        }
                    });

                    edexTab.on('keydown', function(edexTab, e) {
                        tinyMCE.triggerSave();
                        textarea = $('#'+tinymce.activeEditor.id);
                        max = textarea.parent('div').find('span.counter').attr('max');
                        if (max != 'none')
                        {
                            count = tinyMCE.activeEditor.getBody().textContent.length;
                            rest = max - count;
                            if (rest < 0)
                                textarea.parent('div').find('span.counter').html('<span style="color:red;">{l s='Maximum' mod='extraproducttab'} '+max+' {l s='characters' mod='extraproducttab'} : '+rest+'</span>');
                            else
                                textarea.parent('div').find('span.counter').html(' ');
                        }
                    });
                }
            });
			 hideOtherLanguage(id_language);
			$("#product-tab-content-ModuleExtraproducttab").on('loaded', function(){
				displayFlags(languages, id_language, allowEmployeeFormLang);
			});
        });
    });
</script>
