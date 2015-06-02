{if $theme_settings.socials_in_footer == 1}
<div id="socialnetworks">
<ul class="socialnetworks_menu dib">
{foreach from=$soc item=s key=name}
<li class="{$name} dib sec_bg"><a title="{$name}" target="_blank" href="{$s}" class="icon-{$name}"><svg class="dib svgic svgic-{$name}"><use xlink:href="#si-{$name}"></use></svg></a></li>
{/foreach}
</ul>
</div>
{/if}
{if $theme_settings.pay_in_footer == 1}
<div id="payment-icons">
<ul class="dib">
{foreach from=$pay item=s key=name}
<li class="{$name}"><img src="{$module_dir}images/payment_icons/32/{$name}.png" alt="" /></li>
{/foreach}
</ul>
</div>
{/if}