<div class="block facebook-box">
	<h4 class="dropdown-cntrl dd_el_mobile">{l s='Facebook' mod='pk_blockfacebooklike'}</h4>
	<div class="dropdown-content dd_container_mobile">
		<a href="{$FB_page_URL}" target="_blank" class="likeButton sec_bg main_bg_hvr">{l s='Like' mod='pk_blockfacebooklike'}</a>
		<div class="block_content">
			<div class="fb_info_top">
				{if $company_logo}
				<img src="https://graph.facebook.com/{$FB_data.id}/picture" alt="" class="fb_avatar" />
				{/if}
				<div class="fb_info">
					{if $company_name}
					<div>{$FB_data.name}</div>
					{/if}				
				</div>
			</div>
			<div class="fb_fans">{$ppls}</div>
			{if $show_faces}
			{$err}
			<ul class="fb_followers">
				{if file_exists($modulePath)} 
					{include file="$modulePath"}
				{/if}
			</ul>
			{/if}
		</div>
	</div>
</div>
