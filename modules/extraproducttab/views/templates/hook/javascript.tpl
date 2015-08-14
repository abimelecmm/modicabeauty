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
                },
                cleanup : false
            });
            hideOtherLanguage(id_language);
            $("#product-tab-content-ModuleExtraproducttab").on('loaded', function(){
                displayFlags(languages, id_language, allowEmployeeFormLang);
            });
        });
    });
</script>