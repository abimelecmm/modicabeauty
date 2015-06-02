<!-- Block user information module HEADER -->
<div id="header_user" class="dib">
	<ul id="header_nav">				
		<li id="header_user_info">
			<svg class="svgic main_color svgic-login"><use xlink:href="#si-login"></use></svg>
			{if $logged}
				<a href="{$link->getPageLink('my-account', true)|escape:'html'}" class="account main_color_hvr main_color" rel="nofollow">{$cookie->customer_firstname} {$cookie->customer_lastname}</a>
				<a href="{$link->getPageLink('index', true, NULL, "mylogout")|escape:'html'}" title="{l s='Log me out' mod='blockuserinfo'}" class="logout main_color_hvr" rel="nofollow">{l s='Sign out' mod='blockuserinfo'}</a>
			{else}
				<a href="{$link->getPageLink('my-account', true)|escape:'html'}" class="login main_color_hvr" rel="nofollow">{l s='Sign in' mod='blockuserinfo'}</a> {l s='or' mod='blockuserinfo'} <a href="{$link->getPageLink('my-account', true)|escape:'html'}" class="login main_color_hvr">{l s='Register' mod='blockuserinfo'}</a>
			{/if}
		</li>
	</ul>
</div>
<!-- /Block user information module HEADER -->