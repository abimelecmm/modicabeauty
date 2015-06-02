<!-- Block links module -->
<div id="links_block_footer" class="block">
	<div class="block_content">
		<h4 class="dropdown-cntrl dd_el_mobile">
		{if $url == "!"}
			<a href="{$url}">{$title}</a>
		{else}
			{$title}
		{/if}
		</h4>
		<ul class="block_content dropdown-content dd_container_mobile">
		{foreach from=$blocklinkfooter_links item=blocklink_link}
			{if isset($blocklink_link.$lang)} 
			<li><a href="{$blocklink_link.url|htmlentities}"{if $blocklink_link.newWindow} onclick="window.open(this.href);return false;"{/if}>{$blocklink_link.$lang}</a></li>
			{/if}
		{/foreach}
		</ul>
	</div>
</div>
<!-- Block links module -->
