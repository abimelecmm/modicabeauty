{strip}
{addJsDef cookie_page=false}
{addJsDef mobileBlocks=false}
{addJsDef tooltips=false}
{addJsDef scrolltotop=false}
{addJsDef sticky=false}
{addJsDef use_cookies=false}
{addJsDef load_effect=false}
{addJsDef invert_logo=false}
{addJsDef homeslider_bg=$theme_settings.sliderbg}
{if ($page_name == "category") || 
	($page_name == "prices-drop") || 
	($page_name == "new-products") || 
	($page_name == "best-sales") || 
	($page_name == "search") || 
	($page_name == "manufacturer")}
		{addJsDef listing_view_buttons=true}
{/if}
{if $theme_settings.tooltips == 1 && $theme_settings.browser == "" }
	{addJsDef tooltips=true}		
{/if}
{if ($theme_settings.toTop == 1)}
	{addJsDef scrolltotop=true}
{/if}
{if ($theme_settings.sticky_menu == 1)}
	{addJsDef sticky=true}
{/if}
{if ($theme_settings.mobileBlocks == 1)}
	{addJsDef mobileBlocks=true}
{/if}
{if ($theme_settings.use_cookie == 1)}
	{addJsDef use_cookies=true}
{/if}
{if ($theme_settings.cookie_page != "")}
	{addJsDef cookie_page=$theme_settings.cookie_page}
{/if}
{addJsDef load_effect=$theme_settings.load_effect}
{addJsDefL name=cookie_text}{l s='We are using Cookies. By browsing our websites, cookies will be stored on your computer. Please see our' mod='pk_themesettings' js=1}{/addJsDefL}
{addJsDefL name=cookie_link}{l s='Cookie Policy' mod='pk_themesettings' js=1}{/addJsDefL}
{addJsDefL name=cookie_accept}{l s='Accept ' mod='pk_themesettings' js=1}{/addJsDefL}
{/strip}
{if $theme_settings.load_effect == false}
<style>.no-touch .load-animate, #pk_funfacts_block li { opacity: 1 !important }</style>
{/if}
{addJsDefL name=pleaselogin}{l s='You must be logged in to manage your favorites' mod='pk_themesettings' js=1}{/addJsDefL}