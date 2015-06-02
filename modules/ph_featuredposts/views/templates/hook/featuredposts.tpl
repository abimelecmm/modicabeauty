{if isset($recent_posts) && count($recent_posts) > 0}
<div class="ph_simpleblog simpleblog-featured block">
    <h4 class="title_block">{l s='Featured Posts' mod='ph_recentposts'}</h4>
	<div class="ph_row blogpost-list-feat">
		{foreach from=$recent_posts item=post name=foo}
		{if $smarty.foreach.foo.index < 2}
		<section class="blog-item">
			<div class="post-item">
				{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
					<figure>
						<a href="{$post.url}" title="">
							{if Configuration::get('PH_RECENTPOSTS_LAYOUT') == 'full'}
								<img src="{$post.banner_wide}" alt="{$post.meta_title}" class="img-responsive" />
							{else}
								<img src="{$post.banner_thumb}" alt="{$post.meta_title}" class="img-responsive" />
							{/if}
						</a>
						<div class="blog-info">
							{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
							<div class="blog-date main_bg bshadow">
								<div>{$post.date_add|date_format:"%e <span>%b</span>"}</div>
							</div>
							{/if}					
							<div class="blog-post-likes grayshadow likes_{$post.id_simpleblog_post}" onclick="addRating({$post.id_simpleblog_post});"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg><div class="lmromandemi">{$post.likes}</div></div>
						</div>
					</figure>
				{/if}
				<h2>
					<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
				</h2>
				<div class="post-content">					
					{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
						{$post.short_content|truncate:80:'...'}
					{/if}
				</div>	
				<a href="{$post.url}" title="{l s='Permalink to' mod='ph_recentposts'} {$post.meta_title}" class="button">{l s='Read more' mod='ph_recentposts'}</a>
				<div class="clearfix"></div>
			</div>
		</section><!-- .ph_col -->
		{/if}
		{/foreach}
	</div><!-- .ph_row -->
</div><!-- .ph_simpleblog -->
{else}
	<p class="warning">{l s='There are no posts' mod='ph_recentposts'}</p>
{/if}