{*
 * 2007-2013 PrestaShop 
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
 * @category   Promokit modules
 * @package    Promokit Module
 * @author    Marek Mnishek <marek@promokit.eu>
 * @site    
 * @copyright  Copyright 2014 Promokit Co. (http://promokit.eu)
 *}
<input type="hidden" name="modulealysumthemesettings_loaded" value="1">
<div id="product-suppliers" class="panel product-tab">
	<input type="hidden" name="submitted_tabs[]" value="ModulePk_themesettings" />
	<div class="separation"></div>
	<fieldset style="border:none;">
		{if $show_video == 1}
		<div class="form-group">
			<label class="control-label col-lg-3" for="video_id_{$id_lang}">
				{l s='Youtube Video ID' mod='pk_themesettings'}:
			</label>
			<div class="col-lg-9">
				{include file="controllers/products/input_text_lang.tpl"
					languages=$languages
					input_value=$pk_video_id
					input_name="video_id"
				}
			</div>
		</div><br/><br/>
		{/if}
		{if $show_custom_tab == 1}
		<div class="form-group">
			<label class="control-label col-lg-3" for="custom_tab_name_{$id_lang}">
				{l s='Custom Tab Name' mod='pk_themesettings'}:
			</label>
			<div class="col-lg-9">
				{include file="controllers/products/input_text_lang.tpl"
					languages=$languages
					input_value=$pk_custom_tab_name
					input_name="custom_tab_name"
				}
			</div>
		</div><br/><br/>

		<div class="form-group">
			<label class="control-label col-lg-3" for="custom_tab_{$id_lang}">
				{include file="controllers/products/multishop/checkbox.tpl" field="custom_tab" type="tinymce" multilang="true"}
				{l s='Custom Tab Content' mod='pk_themesettings'}:
			</label>
			<div class="col-lg-9">
				{include
					file="controllers/products/textarea_lang.tpl"
					languages=$languages
					input_name='custom_tab'
					class="autoload_rte"
					input_value=$pk_custom_tab}
			</div>
		</div><br/><br/>
		{/if}
    </fieldset>
    <div class="panel-footer">
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay'}</button>
	</div>
	<div class="clear">&nbsp;</div>
</div>
<script type="text/javascript">
	hideOtherLanguage(default_language);
</script>