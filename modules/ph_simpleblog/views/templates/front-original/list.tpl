{if Configuration::get('PH_BLOG_DISPLAY_BREADCRUMBS')}
	{capture name=path}
		<a href="{ph_simpleblog::getLink()}">{l s='Blog' mod='ph_simpleblog'}</a>
		{if $is_category eq true}
			<span class="navigation-pipe">{$navigationPipe}</span>{$blogCategory->name}
		{/if}
	{/capture}
	{if !$is_16}{include file="$tpl_dir./breadcrumb.tpl"}{/if}
{/if}

{if isset($posts) && count($posts) > 0}
<div class="ph_simpleblog simpleblog-{if $is_category}category{else}home{/if}">
	{if $is_category eq true}
		<h1>{$blogCategory->name}</h1>

		{if !empty($blogCategory->description) && Configuration::get('PH_BLOG_DISPLAY_CAT_DESC')}
		<div class="ph_cat_description">
			{$blogCategory->description}
		</div>
		{/if}
	{else}
		<h1>{$blogMainTitle}</h1>
	{/if}

	<div class="ph_row">
		{foreach from=$posts item=post}
		<div class="{$gridHtmlCols}">
			<div class="post-item">
				{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
					<figure>
						<a href="{$post.url}" title="">
							{if Configuration::get('PH_BLOG_LIST_LAYOUT') == 'full'}
								<img src="{$post.banner_wide}" alt="{$post.meta_title}" class="img-responsive" />
							{else}
								<img src="{$post.banner_thumb}" alt="{$post.meta_title}" class="img-responsive" />
							{/if}
						</a>
					</figure>
				{/if}

				<div>
					<h2>
						<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
					</h2>
					{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
						{$post.short_content}
					{/if}
					<a href="{$post.url}" title="{l s='Permalink to' mod='ph_simpleblog'} {$post.meta_title}">{l s='Read more' mod='ph_simpleblog'} &raquo;</a>
				</div>	

				<div class="post-additional-info">
					{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
						<span class="post-date">
							{l s='Posted on:' mod='ph_simpleblog'} {$post.date_add}
						</span>
					{/if}

					{if $is_category eq false && Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
						<span class="post-category">
							{l s='Posted in:' mod='ph_simpleblog'} <a href="{$post.category_url}" title="">{$post.category}</a>
						</span>
					{/if}

					{if isset($post.author) && !empty($post.author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
						<span class="post-author">
							{l s='Author:' mod='ph_simpleblog'} {$post.author}
						</span>
					{/if}

					{if isset($post.tags) && $post.tags && Configuration::get('PH_BLOG_DISPLAY_TAGS')}
						<span class="post-tags clear">
							{l s='Tags:' mod='ph_simpleblog'} 
							{foreach from=$post.tags item=tag name='tagsLoop'}
								{$tag}{if !$smarty.foreach.tagsLoop.last}, {/if}
							{/foreach}
						</span>
					{/if}
				</div><!-- .additional-info -->
			</div>
		</div><!-- .ph_col -->
		{/foreach}
	</div><!-- .ph_row -->
		
	{if $is_category}
		{include file="./pagination.tpl" rewrite=$blogCategory->link_rewrite type='category'}
	{else}
		{include file="./pagination.tpl" rewrite=false type=false}
	{/if}
</div><!-- .ph_simpleblog -->
{else}
	<p class="warning">{l s='There are no posts' mod='ph_simpleblog'}</p>
{/if}
<script>
var currentBlog = '{if $is_category}category{else}home{/if}';
$(window).load(function() {
	$('body').addClass('simpleblog simpleblog-'+currentBlog);

	$('.ph_col').equalHeight();
});
$(window).on('resize', function()
{
	$('.ph_col').equalHeight();
});
</script>