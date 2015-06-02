<div class="ph_simpleblog">
	<h1>{$blogMainTitle}</h1>
	<ul class="posts_list {if $blogLayout eq 'grid'}posts_list_grid{/if} nolist">
		{foreach from=$posts item=post}
		{if $blogLayout eq 'grid'}
			<li class="col-md-6">
				<div class="col-md-12">
				{if isset($post.banner)}	
					<a class="main_img" href="{$post.url}"><img src="{$post.banner}" alt="{$post.meta_title}" class="img-responsive" /></a>
				{else}
					<a class="main_img" href="{$post.url}"><img src="{$module_dir}covers/default.jpg" alt="{$post.meta_title}" class="img-responsive" /></a>
				{/if}
				<div class="row">
					<div class="info">
						<span class="col-md-3 col-sm-3 col-xs-3">
							<i class="fa fa-calendar"></i>
							16 JUN
						</span>
						<span class="col-md-2 col-sm-2 col-xs-2">
							<i class="fa fa-thumbs-up"></i> 
							<span class="count">10</span>
						</span>
						{if isset($post.author) && !empty($post.author)}
							<span class="col-md-4 col-sm-4 col-xs-4">
								<i class="fa fa-user"></i>&nbsp;&nbsp;{$post.author}
							</span>
						{/if}
						<span class="col-md-3 col-sm-3 col-xs-3">
							<i class="fa fa-folder-o"></i> <a href="{$post.category_url}">{$post.category}</a>
						</span>
					</div>
					<div class="col-md-12 article_content">
						<h3><a href="{$post_url}">{$post.meta_title}</a></h3>
						<p>
						{$post.short_content}
						</p>
						<a href="{$post.url}" title="{l s='Read more' mod='ph_simpleblog'}" class="button">{l s='Read more' mod='ph_simpleblog'}</a>
					</div>
				</div>
				</div>
			</li>
		{elseif $blogLayout eq 'full'}
			<li>
				{if isset($post.banner)}	
					<a class="main_img" href="{$post.url}"><img src="{$post.banner}" alt="{$post.meta_title}" class="img-responsive" /></a>
				{else}
					<a class="main_img" href="{$post.url}"><img src="{$module_dir}covers/default.jpg" alt="{$post.meta_title}" class="img-responsive" /></a>
				{/if}
				<div class="row">
					<div class="col-md-2 info">
						<span class="col-md-6 col-sm-6 col-xs-6 date">16 JUN</span>
						<span class="col-md-6 col-sm-6 col-xs-6 like">
							<i class="fa fa-thumbs-up"></i> 
							<span class="count">10</span>
						</span>
						<p>
							{if isset($post.author) && !empty($post.author)}
							<i class="fa fa-user"></i> {$post.author} <br />
							{/if}
							<i class="fa fa-folder-o"></i> <a href="{$post.category_url}">{$post.category}</a>
						</p>
					</div>
					<div class="col-md-10 article_content">
						<h3><a href="{$post_url}">{$post.meta_title}</a></h3>
						<p>
						{$post.short_content}
						</p>
						<a href="{$post.url}" title="{l s='Read more' mod='ph_simpleblog'}" class="button">{l s='Read more' mod='ph_simpleblog'}</a>
					</div>
				</div>
			</li>
		{/if}
		{/foreach}
	</ul>

	{include file="./pagination.tpl" category=false}
</div><!-- .ph_simpleblog -->
<script>
$(function() {
	$('body').addClass('blog blog-list');
});
</script>