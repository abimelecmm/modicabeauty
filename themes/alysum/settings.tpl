{assign var="href_pref" value="//fonts.googleapis.com/css?family="}
{assign var="link_params" value="rel=\"stylesheet\" type=\"text/css\" href="}
{if isset($theme_settings)}
{if !(in_array($theme_settings.logo_font, $theme_settings.systemFonts))}{literal}
<link id="gFontName" {/literal}{$link_params}"{$href_pref}{$theme_settings.logo_font}{literal}">{/literal}
{/if}
{if !(in_array($theme_settings.heading_font, $theme_settings.systemFonts))}{literal}
<link id="gFontNameHeading" {/literal}{$link_params}"{$href_pref}{$theme_settings.heading_font}{literal}">{/literal}
{/if}
{if !(in_array($theme_settings.text_font, $theme_settings.systemFonts))}{literal}
<link id="gFontNameText" {/literal}{$link_params}"{$href_pref}{$theme_settings.text_font}{literal}">{/literal}
{/if}
{if !(in_array($theme_settings.buttons_font, $theme_settings.systemFonts))}{literal}
<link id="gFontNameButton" {/literal}{$link_params}"{$href_pref}{$theme_settings.buttons_font}{literal}">{/literal}
{/if}
{if !(in_array($theme_settings.slogan_font, $theme_settings.systemFonts))}{literal}
<link id="gFontNameSlogan" {/literal}{$link_params}"{$href_pref}{$theme_settings.slogan_font}{literal}">{/literal}
{/if}
{/if}