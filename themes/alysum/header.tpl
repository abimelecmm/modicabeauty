<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"><![endif]-->
<html lang="{$lang_iso}">
	<head>
		<meta charset="utf-8" />
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
{/if}
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<!-- meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" / -->
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		{if isset($theme_settings)}<meta name="application-name" content="{$theme_settings.version}" />{/if}
	{if isset($css_files)}{foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}{/if}
	{if isset($theme_settings) && ($theme_settings.used_fonts != false)}
		<link href='http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family={$theme_settings.used_fonts}' rel='stylesheet' type='text/css' />		
	{/if}
		{$HOOK_HEADER}	
	<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]--> 
	</head>
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if $logged}registered {else}guest {/if}{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{/if}{if $hide_right_column} hide-right-column{/if}{if $content_only} content_only{else} not_content_only{/if} lang_{$lang_iso} preset{$theme_settings.preset}{if $theme_settings.widescreen == 0} fixed-width{/if}">
	{include file="./svg.tpl"}
	{if !$content_only}
		{hook h="displayBanner"}
		{if isset($restricted_country_mode) && $restricted_country_mode}
		<div id="restricted-country">
			<p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country}</span></p>
		</div>
		{/if}
		<!--
		{if ($page_name == "index")}<div class="loader-bg"></div>{/if}
		<div id="white_bg" class="smooth05{if ($page_name == "index")} blured{/if}">
		-->
		<div id="white_bg" class="smooth05">
		{if isset($theme_settings) && $theme_settings.toTop == 1}
		<div id="scrollTop" class="bshadow main_bg"><a href="#"><svg class="svgic svgic-arrowdown"><use xlink:href="#si-arrowdown"></use></svg></a></div>
		{/if}
		<div class="{if isset($theme_settings) && isset($theme_settings.pattern)}back_{$theme_settings.pattern}{/if}" id="pattern">
			<div class="page_width header">			
				<!-- Header -->
				<div id="header">
					{hook h="displayNav"}
					<div id="header_logo" {if isset($theme_settings) && ($theme_settings.logo_center == 1)}class="align_center"{/if}>
						<a id="header_logo_img" href="{$base_dir}" title="{$shop_name|escape:'html':'UTF-8'}">
							<img class="logo{if isset($theme_settings.logo_type) && $theme_settings.logo_type == 1} hidden{/if}" src="{$logo_url}" alt="{$shop_name|escape:'htmlall':'UTF-8'}"{if $logo_image_width} width="{$logo_image_width}" {/if}{if $logo_image_height} height="{$logo_image_height}" {/if}/>
							<span id="logo-text" class="{if isset($theme_settings.logo_type) && $theme_settings.logo_type == 0}hidden{/if}">
								{if isset($theme_settings.logo_text)}<span class="logo">{$theme_settings.logo_text}</span>{/if}
								{if isset($theme_settings.slogan)}<span class="slogan">{$theme_settings.slogan}</span>{/if}	
							</span>	
						</a>
					</div>
					{$HOOK_TOP}
					{if isset($theme_settings) && ($theme_settings.c_block == 1)}
					<div class="c_block">
					{if ($theme_settings.email_ph_acc != "") || ({$theme_settings.email_ph_acc2 != ""})}
					<div class="header-box contact-phones pull-right clearfix">
						<span class="header-box-icon dib main_color"><svg class="svgic svgic-headphones"><use xlink:href="#si-headphones"></use></svg></span><ul class="pull-left dib">
							{if ($theme_settings.email_ph_acc != "")}<li>{$theme_settings.email_ph_acc}</li>{/if}
							{if ($theme_settings.email_ph_acc2 != "")}<li>{$theme_settings.email_ph_acc2}</li>{/if}
						</ul>
					</div><!-- End .contact-phones -->	
					{/if}	
					{if ($theme_settings.email_sk_acc != "") || ({$theme_settings.email_em_acc != ""})}
					<div class="header-box contact-infos pull-right">
						<ul>
							{if ($theme_settings.email_sk_acc != "")}<li><span class="header-box-icon main_color"><svg class="svgic svgic-skype"><use xlink:href="#si-skype"></use></svg></span>{$theme_settings.email_sk_acc}</li>{/if}
							{if ($theme_settings.email_em_acc != "")}<li><span class="header-box-icon main_color"><svg class="svgic svgic-email"><use xlink:href="#si-email"></use></svg></span><a class="mailto-link" href="mailto:{$theme_settings.email_em_acc}">{$theme_settings.email_em_acc}</a></li>{/if}
						</ul>
					</div><!-- End .contact-infos -->	
					{/if}	    							
					</div>    			
					{/if}		
					{if ($theme_settings.preset == 6) && ($page_name == "index")}
					{hook h='menu'}
					{/if}	
				</div>
			</div>
			{if ($theme_settings.preset != 6)}
			{hook h='menu'}
			{/if}
			{if ($theme_settings.preset == 6) && ($page_name != "index")}
			{hook h='menu'}
			{/if}	
			<div class="top_slider">
				{hook h='top_slider'}
			</div>
			<div class="page_width">
				<div id="columns" class="clearfix{if isset($theme_settings) && ($theme_settings.homepage_column == '1')}{if ($theme_settings.column == 'right')} right_col{else} left_col{/if}{/if}">
					{if $page_name !='index' && $page_name !='pagenotfound'}
						{include file="$tpl_dir./breadcrumb.tpl"}
					{/if}		
					{if $page_name == "index"}
						{hook h='hook_home_01'}
						{hook h='hook_home_02'}											
						{hook h='hook_home_03'}				
						{hook h='hook_home_04'}				
						{hook h='hook_home_05'}				
						{hook h='hook_home_06'}								
						{hook h='hook_home_07'}
						{hook h='hook_home_08'}
					{/if}		
					<!-- Center -->
					{if $page_name == "index"}
					<div id="center_column" {if isset($HOOK_LEFT_COLUMN) && $HOOK_LEFT_COLUMN|trim && !$hide_left_column && ($theme_settings.homepage_column == 1) && ($page_name != "products-comparison") && ($page_name != "product")}class="column_exist"{/if}>
					{else}
					<div id="center_column" {if isset($HOOK_LEFT_COLUMN) && $HOOK_LEFT_COLUMN|trim && !$hide_left_column && ($page_name != "products-comparison") && ($page_name != "product")}class="column_exist"{/if}>
					{/if}
						{hook h="displayTopColumn"}
	{/if}