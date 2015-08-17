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
{if $errors|@count == 0}

	{if !isset($priceDisplayPrecision)}
		{assign var='priceDisplayPrecision' value=2}
	{/if}
	{if !$priceDisplay || $priceDisplay == 2}
		{assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, $priceDisplayPrecision)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
	{elseif $priceDisplay == 1}
		{assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, $priceDisplayPrecision)}
		{assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
	{/if}

	<div id="primary_block" class="row" itemscope itemtype="http://schema.org/Product">
		{if $content_only}<div id="cart_block"></div>{/if}
		{if !$content_only}
			<div class="container">
				<div class="top-hr"></div>
			</div>
		{/if}

		{if isset($adminActionDisplay) && $adminActionDisplay}
			<div id="admin-action">
				<p>{l s='This product is not visible to your customers.'}
					<input type="hidden" id="admin-action-product-id" value="{$product->id}" />
					<input type="submit" value="{l s='Publish'}" class="exclusive" onclick="submitPublishProduct('{$base_dir}{$smarty.get.ad|escape:'html':'UTF-8'}', 0, '{$smarty.get.adtoken|escape:'html':'UTF-8'}')"/>
					<input type="submit" value="{l s='Back'}" class="exclusive" onclick="submitPublishProduct('{$base_dir}{$smarty.get.ad|escape:'html':'UTF-8'}', 1, '{$smarty.get.adtoken|escape:'html':'UTF-8'}')"/>
				</p>
				<p id="admin-action-result"></p>
			</div>
		{/if}

		{if isset($confirmation) && $confirmation}
			<p class="confirmation">
				{$confirmation}
			</p>
		{/if}
		<!-- right infos-->  
		<div id="pb-right-column" class="col-xs-12 col-sm-4 col-md-5">
			<!-- product img-->       
			<div class="image_container"> 
			<div id="image-block" class="clearfix">
				{if $product->on_sale}
					<span class="sale-box">
						<span class="sale-label">{l s='Sale!'}</span>
					</span>
				{elseif $product->specificPrice && $product->specificPrice.reduction && $productPriceWithoutReduction > $productPrice}
					<span class="discount">{l s='Reduced price!'}</span>
				{/if} 
				
				{if $have_image}
					<span id="view_full_size">
						{if $jqZoomEnabled && $have_image && !$content_only}
							<a class="jqzoom" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" rel="gal1" href="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}" itemprop="url">
								<img itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}"/>
							</a>
						{else}
							<img id="bigpic" itemprop="image" src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}" title="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" alt="{if !empty($cover.legend)}{$cover.legend|escape:'html':'UTF-8'}{else}{$product->name|escape:'html':'UTF-8'}{/if}" width="{$largeSize.width}" height="{$largeSize.height}"/>
							{if !$content_only}
								<span class="span_link no-print">{l s='View larger'}</span>
							{/if}
						{/if}
					</span>
				{else}
					<span id="view_full_size">
						<img itemprop="image" src="{$img_prod_dir}{$lang_iso}-default-large_{$cookie->img_name}.jpg" id="bigpic" alt="" title="{$product->name|escape:'html':'UTF-8'}" width="{$largeSize.width}" height="{$largeSize.height}"/>
						{if !$content_only}
							<span class="span_link">
								{l s='View larger'}
							</span>
						{/if}
					</span>
				{/if}
			</div> <!-- end image-block -->

			{if isset($images) && count($images) > 0}
				<!-- thumbnails -->
				<div id="views_block" class="clearfix {if isset($images) && count($images) < 2}hidden{/if}">
					{if isset($images) && count($images) > 3}
						<span class="view_scroll_spacer">
							<a id="view_scroll_left" class="btn" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">
								{l s='Previous'}
							</a>
						</span>
					{/if}
					<div id="thumbs_list">
						<ul id="thumbs_list_frame">
						{if isset($images)}
							{foreach from=$images item=image name=thumbnails}
								{assign var=imageIds value="`$product->id`-`$image.id_image`"}
								{if !empty($image.legend)}
									{assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
								{else}
									{assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
								{/if}
								<li id="thumbnail_{$image.id_image}"{if $smarty.foreach.thumbnails.last} class="last"{/if}>
									<a 
										{if $jqZoomEnabled && $have_image && !$content_only}
											href="javascript:void(0);"
											rel="{literal}{{/literal}gallery: 'gal1', smallimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'large_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}',largeimage: '{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}'{literal}}{/literal}"
										{else}
											href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}"
											data-fancybox-group="other-views"
											class="thickbox{if $image.id_image == $cover.id_image} shown{/if}"
										{/if}
										title="{$imageTitle}">
										<img
											class="img-responsive"
											id="thumb_{$image.id_image}"
											src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}"
											alt="{$imageTitle}"
											title="{$imageTitle}"
											height="{$mediumSize.height}"
											width="{$mediumSize.width}"
											itemprop="image" />
									</a>
								</li>
							{/foreach}
						{/if}
						</ul>
					</div> <!-- end thumbs_list -->
					{if isset($images) && count($images) > 3}
						<a id="view_scroll_right" class="btn" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">
							{l s='Next'}
						</a>
					{/if}
				</div> <!-- end views-block -->
				<!-- end thumbnails -->
			{/if}

			{if isset($images) && count($images) > 1}
				<p class="resetimg clear">
					<span id="wrapResetImages" style="display: none;">
						<a id="resetImages" href="{$link->getProductLink($product)|escape:'html':'UTF-8'}">
							<i class="icon-repeat"></i>
							{l s='Display all pictures'}
						</a>
					</span>
				</p>
			{/if}			
		</div><!-- end image_column -->
		</div> <!-- end pb-right-column -->
		<!-- end right infos--> 

		<!-- left infos -->
		<div id="pb-left-column" class="col-xs-12 col-sm-4">
			{if $product->online_only}
				<p class="online_only">{l s='Online only'}</p>
			{/if}
	
			<h1 itemprop="name">{$product->name|escape:'html':'UTF-8'}</h1>

			<div class="product-desc" itemprop="description">
				{$product->description_short}
			</div>

			{if isset($comments) && ($theme_settings.product_rating == 1)}
				{assign var='totalGrade' value=0}
				{assign var='totalVotes' value=0}
				{assign var='productGrade' value=0}
				{foreach from=$comments item=comment}
					{if $comment.content}
					{$totalGrade = $totalGrade+$comment.grade}
					{$totalVotes = $totalVotes+1}					
					{/if}
				{/foreach}
				{if $totalVotes>0}
					{$productGrade = $totalGrade/$totalVotes}
				{else}
					{$productGrade = 0}
				{/if}
				<div class="comment" id="product_comment">
					<div class="star_content clearfix"itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">	
					{section name="i" start=0 loop=5 step=1}
						{if $productGrade <= $smarty.section.i.index}
							<div class="star dib"><svg class="svgic svgic-star"><use xlink:href="#si-star"></use></svg></div>
							{assign var=rt value=$smarty.section.i.index}
						{else}
							<div class="star dib star_on"><svg class="svgic svgic-star"><use xlink:href="#si-star"></use></svg></div>
						{/if}
					{/section}
						<span itemprop="ratingValue" class="hidden">{$productGrade}</span>
						<span itemprop="ratingcount" class="hidden">{$totalVotes}</span>				
					</div>
				</div>		
			{/if}
			{if isset($product->manufacturer_name) && $product->manufacturer_name != ""}
			<p class="product-manufacturer">
				 <span class="value_name">{l s='Manufacturer:'}</span> <a href="{$link->getmanufacturerLink($product->id_manufacturer)|escape:'htmlall':'UTF-8'}" itemprop="brand" itemscope itemtype="http://schema.org/Organization"><span itemprop="name">{$product->manufacturer_name}</span></a>
			</p>
			{/if}
			{capture name=condition}
				{if $product->condition == 'new'}{l s='New'}
				{elseif $product->condition == 'used'}{l s='Used'}
				{elseif $product->condition == 'refurbished'}{l s='Refurbished'}
				{/if}
			{/capture}
			<p id="product_condition"{if !$product->condition} style="display: none;"{/if}>
				<span class="value_name">{l s='Condition'}: </spam>
				<span class="editable" itemprop="condition">{$smarty.capture.condition|escape:'html':'UTF-8'}</span>
			</p>			
			<p id="product_reference"{if isset($groups) || !$product->reference} style="display: none;"{/if}>
				<label>{l s='Model'}: </label>
				<span class="editable" itemprop="sku">{$product->reference|escape:'html':'UTF-8'}</span>
			</p>

			<p id="availability_statut">
				<span id="availability_label" class="value_name">{l s='Availability:'}</span>
				<span id="availability_value"{if $product->quantity <= 0} class="warning_inline"{/if}>
				{if $product->quantity <= 0}{if $allow_oosp}{$product->available_later}{else}{l s='This product is no longer in stock'}{/if}{else}{$product->available_now}{/if}
				</span>
				<!-- number of item in stock -->
				{if ($display_qties == 1 && !$PS_CATALOG_MODE && $product->available_for_order)}
				<span id="pQuantityAvailable"{if $product->quantity <= 0} style="display: none;"{/if}>
					(<span id="quantityAvailable">{$product->quantity|intval}</span>
					<span {if $product->quantity > 1} style="display: none;"{/if} id="quantityAvailableTxt">{l s='item in stock'}</span>
					<span {if $product->quantity == 1} style="display: none;"{/if} id="quantityAvailableTxtMultiple">{l s='items in stock'}</span>)
				</span>
				{/if}


				<span id="availability_value">{l s='items in stock'}</span>
				


				{if $PS_STOCK_MANAGEMENT}
					{hook h="displayProductDeliveryTime" product=$product}
					<span class="warning_inline" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity <= 0) || $allow_oosp || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none"{/if} >{l s='Warning: Last items in stock!'}</span>
				{/if}
			</p>
						
			
			<p id="availability_date"{if ($product->quantity > 0) || !$product->available_for_order || $PS_CATALOG_MODE || !isset($product->available_date) || $product->available_date < $smarty.now|date_format:'%Y-%m-%d'} style="display: none;"{/if}>
				<span id="availability_date_label" class="value_name">{l s='Availability date:'}</span>
				<span id="availability_date_value">{dateFormat date=$product->available_date full=false}</span>
			</p>

			{if $product->description_short || $packItems|@count > 0}
				<div id="short_description_block">
					{if ($product->description_short) && ($theme_settings.short_desc == 1)}
						<div id="short_description_content" class="rte align_justify" itemprop="description">{$product->description_short}</div>
					{/if}

					{if $product->description}
						<p class="buttons_bottom_block">
							<a href="javascript:{ldelim}{rdelim}" class="button">
								{l s='More details'}
							</a>
						</p>
					{/if}
					<!--{if $packItems|@count > 0}
						<div class="short_description_pack">
						<h3>{l s='Pack content'}</h3>
							{foreach from=$packItems item=packItem}
							
							<div class="pack_content">
								{$packItem.pack_quantity} x <a href="{$link->getProductLink($packItem.id_product, $packItem.link_rewrite, $packItem.category)|escape:'html':'UTF-8'}">{$packItem.name|escape:'html':'UTF-8'}</a>
								<p>{$packItem.description_short}</p>
							</div>
							{/foreach}
						</div>
					{/if}-->
				</div> <!-- end short_description_block -->
			{/if}
						<!-- pb-right-column1-->
			<div id="pb-right-column1" class="col-xs-12 col-sm-4 col-md-3">
				<div class="box-info-product">					
					<div class="product_attributes clearfix">						
						{if isset($groups)}
							<!-- attributes -->
							<div id="attributes">
								<div class="clear"></div>
								{foreach from=$groups key=id_attribute_group item=group}
									{if $group.attributes|@count}
										<fieldset class="attribute_fieldset">
											<label class="attribute_label" {if $group.group_type != 'color' && $group.group_type != 'radio'}for="group_{$id_attribute_group|intval}"{/if}>{$group.name|escape:'html':'UTF-8'} :&nbsp;</label>
											{assign var="groupName" value="group_$id_attribute_group"}
											<div class="attribute_list">
												{if ($group.group_type == 'select')}
													<select name="{$groupName}" id="group_{$id_attribute_group|intval}" class="form-control attribute_select" onchange="findCombination();getProductAttribute();">
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if} title="{$group_attribute|escape:'html':'UTF-8'}">{$group_attribute|escape:'html':'UTF-8'}</option>
														{/foreach}
													</select>
												{elseif ($group.group_type == 'color')}
													{if (!$theme_settings) || ($theme_settings.product_colorpicker == 1)}
													<div id="dd_{$id_attribute_group|intval}" class="wrapper-dropdown">
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															{if $group.default == $id_attribute}
															<i id="color_marker" style="background-color: {$colors.$id_attribute.value};">
																{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
																	<img id="attr_{$id_attribute}" class="{if $group.default == $id_attribute}display{else}hidden{/if}" src="{$img_col_dir}{$id_attribute}.jpg" alt="{$colors.$id_attribute.name}" width="19" />
																{/if}
															</i>
																<span>
																{$colors.$id_attribute.name}												
																</span>
															{/if}
														{/foreach}
														<ul id="color_to_pick_list2" class="dropdown">
															{assign var="default_colorpicker" value=""}
															{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<li{if $group.default == $id_attribute} class="selected"{/if}>
																<a id="color_{$id_attribute|intval}" class="color_pick{if ($group.default == $id_attribute)} selected{/if}" onclick="colorPickerClick(this);getProductAttribute();{if $colors|@count > 0}$('#wrapResetImages').show('slow');$('#dd_{$id_attribute_group|intval}').find('#color_marker').css('background-color', $(this).find('i').css('background-color'));
																	$('#dd_{$id_attribute_group|intval}').find('#color_marker img').remove();$('#dd_{$id_attribute_group|intval}').find('#color_to_pick_list2 #attr_{$id_attribute}').clone().prependTo('#dd_{$id_attribute_group|intval} i#color_marker');{/if}">
																	<i style="background: {$colors.$id_attribute.value};">
																		{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
																			<img id="attr_{$id_attribute}" class="{if $group.default == $id_attribute}display{else}hide{/if}" src="{$img_col_dir}{$id_attribute}.jpg" alt="{$colors.$id_attribute.name}" width="19" />
																		{/if}
																	</i>
																	{$colors.$id_attribute.name}
																</a>										
															</li>
															{if ($group.default == $id_attribute)}
																{$default_colorpicker = $id_attribute}
															{/if}
															{/foreach}
														</ul>
														<div><b></b></div>
														<input type="hidden" class="color_pick_hidden" name="{$groupName}" value="{$default_colorpicker}" />
													</div>
													<script>
													$(function() {	 
													    var dd = new DropDown( $('#dd_{$id_attribute_group|intval}') );
													    $(dd).click(function() { 
													    	$('.wrapper-dropdown').removeClass('active');
													    });	 
													});
													</script>													
													{else}
													<ul id="color_to_pick_list" class="clearfix">
														{assign var="default_colorpicker" value=""}
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<li{if $group.default == $id_attribute} class="selected"{/if}>
																<a id="color_{$id_attribute|intval}" class="color_pick{if ($group.default == $id_attribute)} selected{/if}" style="background: {$colors.$id_attribute.value};" title="{$colors.$id_attribute.name}">
																	{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
																		<img src="{$img_col_dir}{$id_attribute}.jpg" alt="{$colors.$id_attribute.name}" width="20" height="20" />
																	{/if}
																</a>
															</li>
															{if ($group.default == $id_attribute)}
																{$default_colorpicker = $id_attribute}
															{/if}
														{/foreach}
													</ul>
													<input type="hidden" class="color_pick_hidden" name="{$groupName}" value="{$default_colorpicker}" />
													{/if}
												{elseif ($group.group_type == 'radio')}
													<ul>
														{foreach from=$group.attributes key=id_attribute item=group_attribute}
															<li>
																<input type="radio" class="attribute_radio" name="{$groupName}" value="{$id_attribute}" {if ($group.default == $id_attribute)} checked="checked"{/if} onclick="findCombination();getProductAttribute();" />
																<span>{$group_attribute|escape:'html':'UTF-8'}</span>
															</li>
														{/foreach}
													</ul>
												{/if}
											</div> <!-- end attribute_list -->
										</fieldset>
									{/if}
								{/foreach}
							</div> <!-- end attributes -->
							{/if}
							{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
							<!-- quantity wanted -->
							<p id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
								<label>{l s='Quantity:'}</label>
								<input
									type="text"
									name="qty"
									id="quantity_wanted"
									class="text"
									value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}"
									maxlength="3"
									{if $product->minimal_quantity > 1}onkeyup="checkMinimalQuantity({$product->minimal_quantity});"{/if} />
								<a href="#" data-field-qty="qty" class="btn btn-default button-minus product_quantity_down">
									<span><i class="icon-minus"></i></span>
								</a>
								<a href="#"  data-field-qty="qty" class="btn btn-default button-plus product_quantity_up ">
									<span><i class="icon-plus"></i></span>
								</a>
								<span class="clearfix"></span>
							</p>
							<!-- minimal quantity wanted -->
							<p id="minimal_quantity_wanted_p"{if $product->minimal_quantity <= 1 || !$product->available_for_order || $PS_CATALOG_MODE} style="display: none;"{/if}>
								{l s='This product is not sold individually. You must select at least'} <b id="minimal_quantity_label">{$product->minimal_quantity}</b> {l s='quantity for this product.'}
							</p>
						{/if}
					</div> <!-- end product_attributes -->
					<div class="content_prices clearfix{if (($cookie->ts_countdown == 1) && isset($product->specificPrice.to))} countd sec_bord_hvr{/if}">
						{if $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
							<!-- prices -->
							{if ($cookie->ts_countdown == 1)}
					        {assign var=to value="-"|explode:$product->specificPrice.to} 
					        {if isset($product->specificPrice.to) && ($to[0] != "0000")}
					        	<div class="countdown" title="{l s='To the end of this offer'}"></div>
					        	<script>
					        	$(document).ready(function(){
					        		$(function() {
									    $('.countdown').countdown({
									        date: "{$product->specificPrice.to|replace:' ':'T'}",
									          render: function(data) {
									            $(this.el).html("<div class='main_bg'>" + this.leadingZeros(data.days, 2) + " <span>{l s='Days'}</span></div><div class='main_bg'>" + this.leadingZeros(data.hours, 2) + " <span>{l s='Hours'}</span></div><div class='main_bg'>" + this.leadingZeros(data.min, 2) + " <span>{l s='Min'}</span></div><div class='main_bg'>" + this.leadingZeros(data.sec, 2) + " <span>{l s='Sec'}</span></div>");
									            $(this.el).attr('title', this.leadingZeros(data.days, 2)+" {l s='Days'} {l s='and'} "+this.leadingZeros(data.hours, 2)+" {l s='Hours'} {l s='to the end of this offer'}");
									          }
									    });
									});
					        	});
					        	</script>			        	
					        {/if}
					        {/if}
							<div class="price">
								<p id="old_price"{if !$product->specificPrice || !$product->specificPrice.reduction} class="hidden"{/if}>
									{if $priceDisplay >= 0 && $priceDisplay <= 2}
										<span id="old_price_display">{if $productPriceWithoutReduction > $productPrice}{convertPrice price=$productPriceWithoutReduction}{/if}</span>
										<!-- {if $tax_enabled && $display_tax_label == 1}{if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}{/if} -->
									{/if}
								</p>
								<p id="reduction_percent" {if !$product->specificPrice || $product->specificPrice.reduction_type != 'percentage'} style="display:none;"{/if}>
									<span id="reduction_percent_display">
										{if $product->specificPrice && $product->specificPrice.reduction_type == 'percentage'}-{$product->specificPrice.reduction*100}%{/if}
									</span>
								</p>
								<p id="reduction_amount" {if !$product->specificPrice || $product->specificPrice.reduction_type != 'amount' || $product->specificPrice.reduction|intval ==0} style="display:none"{/if}>
									<span id="reduction_amount_display">
									{if $product->specificPrice && $product->specificPrice.reduction_type == 'amount' && $product->specificPrice.reduction|intval !=0}
										-{convertPrice price=$productPriceWithoutReduction-$productPrice|floatval}
									{/if}
									</span>
								</p>
								<p class="our_price_display" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
									{if $product->quantity > 0}<link itemprop="availability" href="http://schema.org/InStock"/>{/if}
									{if $priceDisplay >= 0 && $priceDisplay <= 2}
										<span id="our_price_display" itemprop="price">{convertPrice price=$productPrice}</span>
										<!--{if $tax_enabled  && ((isset($display_tax_label) && $display_tax_label == 1) || !isset($display_tax_label))}
											{if $priceDisplay == 1}{l s='tax excl.'}{else}{l s='tax incl.'}{/if}
										{/if}-->
										<meta itemprop="priceCurrency" content="{$currency->iso_code}" />
										{hook h="displayProductPriceBlock" product=$product type="price"}
									{/if}
								</p>

								{if $priceDisplay == 2}
									<br />
									<span id="pretaxe_price">
										<span id="pretaxe_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL)}</span>
										{l s='tax excl.'}
									</span>
								{/if}
								<div id="add_to_cart" {if (!$allow_oosp && $product->quantity <= 0) || !$product->available_for_order || (isset($restricted_country_mode) && $restricted_country_mode) || $PS_CATALOG_MODE}style="display:none"{/if} class="buttons_bottom_block">
								{if ($product->show_price && !isset($restricted_country_mode)) || isset($groups) || $product->reference || (isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS)}
									<!-- add to cart form-->
									<form id="buy_block" style="margin-top:0" {if $PS_CATALOG_MODE && !isset($groups) && $product->quantity > 0}class="hidden"{/if} action="{$link->getPageLink('cart')|escape:'html':'UTF-8'}" method="post">
										<!-- hidden datas -->
										<button type="submit" name="Submit" class="exclusive lmromancaps">{l s='Add to cart'}</button>
										<p class="hidden">
											<input type="hidden" name="token" value="{$static_token}" />
											<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
											<input type="hidden" name="add" value="1" />
											<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
										</p>
									</form>
								{/if}
								</div>
							</div> <!-- end prices -->
					
							{if $packItems|@count && $productPrice < $product->getNoPackPrice()}
								<p class="pack_price">{l s='Instead of'} <span style="text-decoration: line-through;">{convertPrice price=$product->getNoPackPrice()}</span></p>
							{/if}

							{if $product->ecotax != 0}
								<p class="price-ecotax">{l s='Include'} <span id="ecotax_price_display">{if $priceDisplay == 2}{$ecotax_tax_exc|convertAndFormatPrice}{else}{$ecotax_tax_inc|convertAndFormatPrice}{/if}</span> {l s='For green tax'}
									{if $product->specificPrice && $product->specificPrice.reduction}
									<br />{l s='(not impacted by the discount)'}
									{/if}
								</p>
							{/if}

							{if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
								{math equation="pprice / punit_price"  pprice=$productPrice  punit_price=$product->unit_price_ratio assign=unit_price}
								<p class="unit-price"><span id="unit_price_display">{convertPrice price=$unit_price}</span> {l s='per'} {$product->unity|escape:'html':'UTF-8'}</p>
							{/if}
						{/if} {*close if for show price*}
						<div class="clear"></div>
					</div> <!-- end content_prices -->
					{if !$content_only}
					<div class="box-cart-bottom">						
						{if $theme_settings.share_buttons == 1}
						<!-- AddThis Button BEGIN -->
						<div class="addthis_toolbox addthis_default_style">
							<div class="addthis-container">
							<a class="addthis_button_preferred_1"></a>
							<a class="addthis_button_preferred_2"></a>
							<a class="addthis_button_preferred_3"></a>
							<a class="addthis_button_preferred_4"></a>
							<a class="addthis_button_compact"></a>
							<a class="addthis_counter addthis_bubble_style"></a>
							</div>
						</div>
						<script type="text/javascript">
							var addthis_config = { "data_track_addressbar":true }; 
							addthis_config.data_track_addressbar = false;
						</script>
						<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f8c6f62449043f7"></script>
						<!-- AddThis Button END -->
						{/if}
						{if isset($HOOK_PRODUCT_ACTIONS) && $HOOK_PRODUCT_ACTIONS}{$HOOK_PRODUCT_ACTIONS}{/if}<strong></strong>
					</div> <!-- end box-cart-bottom -->
					<ul id="usefull_link_block" class="clearfix">
						{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
						<!--<li class="print">
							<a href="javascript:print();">
								{l s='Print'}
							</a>
						</li>-->
					</ul>
				</div> <!-- end box-info-product -->
				{/if}
			</div> <!-- end pb-right-column1-->

			{if !$content_only}
				<div class="tab-titles">
					{if $product->description}<h3 class="active-tab" data-title="1">{l s='More info'}</h3>{/if}{if isset($features) && $features}<h3 data-title="2">{l s='Data sheet'}</h3>{/if}{if isset($accessories) && $accessories}<h3 data-title="3">{l s='Accessories'}</h3>{/if}{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}<h3 data-title="4">{l s='Volume discounts'}</h3>{/if}{if isset($attachments) && $attachments}<h3 data-title="5">{l s='Download'}</h3>{/if}{if isset($product) && $product->customizable}<h3 data-title="6">{l s='Product customization'}</h3>{/if}{if isset($packItems) && $packItems|@count > 0}<h3 data-title="7">{l s='Pack content'}</h3>{/if}{$HOOK_PRODUCT_TAB}
				</div>
			
		
				{if $product->description}
					<!-- More info -->
					<section class="page-product-box active-section" data-section="1">						
						{if isset($product) && $product->description}
							<!-- full description -->
							<div  class="rte">{$product->description}</div>
						{/if}
					</section>
					<!--end  More info -->
				{/if}
				
				
				<!--HOOK_PRODUCT_TAB -->				
				{if isset($HOOK_PRODUCT_TAB_CONTENT) && $HOOK_PRODUCT_TAB_CONTENT}
						{$HOOK_PRODUCT_TAB_CONTENT}
				{/if}
				<!--end HOOK_PRODUCT_TAB -->

				{if isset($features) && $features}
					<!-- Data sheet -->
					<section class="page-product-box" data-section="2">										
						<table class="table-data-sheet">			
							{foreach from=$features item=feature}
							<tr class="{cycle values="odd,even"}">
								{if isset($feature.value)}			    
								<td>{$feature.name|escape:'html':'UTF-8'}</td>
								<td>{$feature.value|escape:'html':'UTF-8'}</td>
								{/if}
							</tr>
							{/foreach}
						</table>
					</section>
					<!--end Data sheet -->
				{/if}
								
				{if isset($accessories) && $accessories}
					<!--Accessories -->
					<section class="page-product-box" data-section="3">						
						<div class="block products_block accessories-block clearfix">
							<div class="block_content">
								<ul>
									{foreach from=$accessories item=accessory name=accessories_list}
										{if ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) && $accessory.available_for_order && !isset($restricted_country_mode)}
											{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
											<li class="item product-box ajax_block_product{if $smarty.foreach.accessories_list.first} first_item{elseif $smarty.foreach.accessories_list.last} last_item{else} item{/if} product_accessories_description">
												<div class="product_desc">
													<a 
														href="{$accessoryLink|escape:'html':'UTF-8'}"
														title="{$accessory.legend|escape:'html':'UTF-8'}"
														class="product-image product_image">
														<img
															class="lazyOwl"
															src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'home_'|cat:$cookie->img_name)|escape:'html':'UTF-8'}"
															alt="{$accessory.legend|escape:'html':'UTF-8'}"
															width="{$homeSize.width}"
															height="{$homeSize.height}"
															/>
													</a>
												</div>
												<div class="s_title_block product_accessories_price">
													<h5>
														<a href="{$accessoryLink|escape:'html':'UTF-8'}">
															{$accessory.name|truncate:18:'...':true|escape:'htmlall':'UTF-8'}
														</a>
													</h5>
													{if $accessory.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
													<span class="price">
														{if $priceDisplay != 1}
														{displayWtPrice p=$accessory.price}{else}{displayWtPrice p=$accessory.price_tax_exc}
														{/if}
													</span>
													{/if}
												</div>
												<div class="clearfix" style="margin-top:5px">
													{if !$PS_CATALOG_MODE && ($accessory.allow_oosp || $accessory.quantity > 0)}
														<div>
															<div>
																<div>
																	<a
																		class="exclusive button ajax_add_to_cart_button"
																		href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add")|escape:'html':'UTF-8'}"
																		data-id-product="{$accessory.id_product|intval}"
																		title="{l s='Add to cart'}">
																		<span>{l s='Add to cart'}</span>
																	</a>
																</div>
															</div>
														</div>
													{/if}
												</div>
											</li>
										{/if}
									{/foreach}
								</ul>
							</div>
						</div>
					</section>
					<!--end Accessories -->
				{/if}

				{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
					<!-- quantity discount -->
					<section class="page-product-box" data-section="4">
						<div id="quantityDiscount">
							<table class="std table-produst-discounts">
								<thead>
									<tr>
										<th>{l s='Quantity'}</th>
										<th>{if $display_discount_price}{l s='Price'}{else}{l s='Discount'}{/if}</th>
										<th>{l s='You Save'}</th>
									</tr>
								</thead>
								<tbody>
									{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
									<tr id="quantityDiscount_{$quantity_discount.id_product_attribute}" class="quantityDiscount_{$quantity_discount.id_product_attribute}">
									<td>{$quantity_discount.quantity|intval}</td>             
										
										<td>
											{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
												{if $display_discount_price}
													{convertPrice price=$productPrice-$quantity_discount.real_value|floatval}
												{else}
													{convertPrice price=$quantity_discount.real_value|floatval}
												{/if}
											{else}
												{if $display_discount_price}
													{convertPrice price = $productPrice-($productPrice*$quantity_discount.reduction)|floatval}
												{else}
													{$quantity_discount.real_value|floatval}%
												{/if}
											{/if}
										</td>
										<td>
											<span>{l s='Up to'}</span>
											{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
												{$discountPrice=$productPrice-$quantity_discount.real_value|floatval}
											{else}
												{$discountPrice=$productPrice-($productPrice*$quantity_discount.reduction)|floatval}
											{/if}
											{$discountPrice=$discountPrice*$quantity_discount.quantity}
											{$qtyProductPrice = $productPrice*$quantity_discount.quantity}
											{convertPrice price=$qtyProductPrice-$discountPrice}
										</td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
					</section>
				{/if}
				

				<!-- description & features -->
				{if (isset($product) && $product->description) || (isset($features) && $features) || (isset($accessories) && $accessories) || (isset($HOOK_PRODUCT_TAB) && $HOOK_PRODUCT_TAB) || (isset($attachments) && $attachments) || isset($product) && $product->customizable}

					{if isset($attachments) && $attachments}
					<!--Download -->
					<section class="page-product-box" data-section="5">
						{foreach from=$attachments item=attachment name=attachements}
							{if $smarty.foreach.attachements.iteration %3 == 1}<div class="row">{/if}
								<div class="col-lg-4">
									<h4><a href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">{$attachment.name|escape:'html':'UTF-8'}</a></h4>
									<p class="text-muted">{$attachment.description|escape:'html':'UTF-8'}</p>
									<a class="btn btn-default btn-block" href="{$link->getPageLink('attachment', true, NULL, "id_attachment={$attachment.id_attachment}")|escape:'html':'UTF-8'}">
										<i class="icon-download"></i>
										{l s="Download"} ({Tools::formatBytes($attachment.file_size, 2)})
									</a>
									<hr>
								</div>
							{if $smarty.foreach.attachements.iteration %3 == 0 || $smarty.foreach.attachements.last}</div>{/if}
						{/foreach}
					</section>
					<!--end Download -->
					{/if}

					{if isset($product) && $product->customizable}
					<!--Customization -->
					<section class="page-product-box" data-section="6">
						<!-- Customizable products -->
						<form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm" class="clearfix">
							<p class="infoCustomizable">
								{l s='After saving your customized product, remember to add it to your cart.'}
								{if $product->uploadable_files}
								<br />
								{l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
							</p>
							{if $product->uploadable_files|intval}
								<div class="customizableProductsFile">
									<h5 class="product-heading-h5">{l s='Pictures'}</h5>
									<ul id="uploadable_files" class="clearfix">
										{counter start=0 assign='customizationField'}
										{foreach from=$customizationFields item='field' name='customizationFields'}
											{if $field.type == 0}
												<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
													{if isset($pictures.$key)}
														<div class="customizationUploadBrowse">
															<img src="{$pic_dir}{$pictures.$key}_small" alt="" />
																<a href="{$link->getProductDeletePictureLink($product, $field.id_customization_field)|escape:'html':'UTF-8'}" title="{l s='Delete'}" >
																	<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="customization_delete_icon" width="11" height="13" />
																</a>
														</div>
													{/if}
													<div class="customizationUploadBrowse form-group">
														<label class="customizationUploadBrowseDescription">
															{if !empty($field.name)}
																{$field.name}
															{else}
																{l s='Please select an image file from your computer'}
															{/if}
															{if $field.required}<sup>*</sup>{/if}
														</label>
														<input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="form-control customization_block_input {if isset($pictures.$key)}filled{/if}" />
													</div>
												</li>
												{counter}
											{/if}
										{/foreach}
									</ul>
								</div>
							{/if}

							{if $product->text_fields|intval}
								<div class="customizableProductsText">
									<h5 class="product-heading-h5">{l s='Text'}</h5>
									<ul id="text_fields">
									{counter start=0 assign='customizationField'}
									{foreach from=$customizationFields item='field' name='customizationFields'}
										{if $field.type == 1}
											<li class="customizationUploadLine{if $field.required} required{/if}">
												<label for ="textField{$customizationField}">
													{assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
													{if !empty($field.name)}
														{$field.name}
													{/if}
													{if $field.required}<sup>*</sup>{/if}
												</label>
												<textarea name="textField{$field.id_customization_field}" class="form-control customization_block_input" id="textField{$customizationField}" rows="3" cols="20">{strip}
													{if isset($textFields.$key)}
														{$textFields.$key|stripslashes}
													{/if}
												{/strip}</textarea>
											</li>
											{counter}
										{/if}
									{/foreach}
									</ul>
								</div>
							{/if}

							<p id="customizedDatas">
								<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
								<input type="hidden" name="submitCustomizedDatas" value="1" />
								<!--<input type="button" class="button btn btn-default button button-small" value="{l s='Save'}" onclick="javascript:saveCustomization()" />-->
								<button  class="button btn btn-default button button-small" onclick="javascript:saveCustomization()">
									<span>{l s='Save'}</span>
								</button>
								<span id="ajax-loader" style="display:none">
									<img src="{$img_ps_dir}loader.gif" alt="loader" />
								</span>
							</p>
						</form>
						<p class="clear required"><sup>*</sup> {l s='required fields'}</p>	
					</section>
					<!--end Customization -->
					{/if}
				{/if}

				{if isset($packItems) && $packItems|@count > 0}
				<section id="blockpack" data-section="7">
						{include file="$tpl_dir./product-list.tpl" products=$packItems}
				</section>
				{/if}

			{/if}
			{if $content_only}
				{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}
				{/if}
			<!-- Out of stock hook -->
			<div id="oosHook"{if $product->quantity > 0} style="display: none;"{/if}>
				{$HOOK_PRODUCT_OOS}
			</div>

			{if isset($HOOK_EXTRA_RIGHT) && $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}

		</div>
		<!-- end left infos-->
		
		{if !$content_only}
		{if isset($HOOK_PRODUCT_FOOTER) && $HOOK_PRODUCT_FOOTER}{$HOOK_PRODUCT_FOOTER}{/if}
		{/if}

	</div> <!-- end primary_block -->
<script>
$(window).load(function(){
	var blockHeight = $("#image-block").height();
    $("#thumbs_list").height(blockHeight);
	$(".thickbox").fancybox();
});
</script>
{strip}
{if isset($smarty.get.ad) && $smarty.get.ad}
	{addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{if isset($smarty.get.adtoken) && $smarty.get.adtoken}
	{addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
{/if}
{addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
{addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
{addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
{addJsDef attribute_anchor_separator=$attribute_anchor_separator|escape:'quotes':'UTF-8'}
{addJsDef attributesCombinations=$attributesCombinations}
{addJsDef currencySign=$currencySign|html_entity_decode:2:"UTF-8"}
{addJsDef currencyRate=$currencyRate|floatval}
{addJsDef currencyFormat=$currencyFormat|intval}
{addJsDef currencyBlank=$currencyBlank|intval}
{addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
{if isset($combinations) && $combinations}
	{addJsDef combinations=$combinations}
	{addJsDef combinationsFromController=$combinations}
	{addJsDef displayDiscountPrice=$display_discount_price}
	{addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
{/if}
{if isset($combinationImages) && $combinationImages}
	{addJsDef combinationImages=$combinationImages}
{/if}
{addJsDef customizationFields=$customizationFields}
{addJsDef default_eco_tax=$product->ecotax|floatval}
{addJsDef displayPrice=$priceDisplay|intval}
{addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
{addJsDef group_reduction=$group_reduction}
{if isset($cover.id_image_only)}
	{addJsDef idDefaultImage=$cover.id_image_only|intval}
{else}
	{addJsDef idDefaultImage=0}
{/if}
{addJsDef img_ps_dir=$img_ps_dir}
{addJsDef img_prod_dir=$img_prod_dir}
{addJsDef id_product=$product->id|intval}
{addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
{addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
{addJsDef minimalQuantity=$product->minimal_quantity|intval}
{addJsDef noTaxForThisProduct=$no_tax|boolval}
{addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
{addJsDef oosHookJsCodeFunctions=Array()}
{addJsDef productHasAttributes=isset($groups)|boolval}
{addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcluded=($product->base_price - $product->ecotax)|floatval}
{addJsDef productBasePriceTaxExcl=($product->base_price|floatval)}
{addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
{addJsDef productAvailableForOrder=$product->available_for_order|boolval}
{addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
{addJsDef productPrice=$productPrice|floatval}
{addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
{addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
{addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
{if $product->specificPrice && $product->specificPrice|@count}
	{addJsDef product_specific_price=$product->specificPrice}
{else}
	{addJsDef product_specific_price=array()}
{/if}
{if $display_qties == 1 && $product->quantity}
	{addJsDef quantityAvailable=$product->quantity}
{else}
	{addJsDef quantityAvailable=0}
{/if}
{addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
	{addJsDef reduction_percent=$product->specificPrice.reduction*100|floatval}
{else}
	{addJsDef reduction_percent=0}
{/if}
{if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
	{addJsDef reduction_price=$product->specificPrice.reduction|floatval}
{else}
	{addJsDef reduction_price=0}
{/if}
{if $product->specificPrice && $product->specificPrice.price}
	{addJsDef specific_price=$product->specificPrice.price|floatval}
{else}
	{addJsDef specific_price=0}
{/if}
{addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval} {* TODO: remove if always false *}
{addJsDef stock_management=$PS_STOCK_MANAGEMENT|intval}
{addJsDef taxRate=$tax_rate|floatval}
{addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
{addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
{addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
{addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
{addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
{addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
{/strip}
{/if}