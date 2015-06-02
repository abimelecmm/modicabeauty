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
	{if Configuration::get('PH_BLOG_DISPLAY_CATEGORY_IMAGE') && isset($blogCategory->image)}
		<div class="simpleblog-category-image">
			<img src="{$blogCategory->image}" alt="{$blogCategory->name}" class="img-responsive" />
		</div>
		{/if}
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
		<div class="{$gridHtmlCols} post-container" data-postid="{$post.id_simpleblog_post}">
			{if $gridHtmlCols == "ph_col_medium"} <!--MEDIUM-->
				<div class="blog-info">
					{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
					<div class="blog-date main_bg">
						<div>{$post.date_add|date_format:"%e <span>%b</span>"}</div>
					</div>
					{/if}					
					<div class="blog-post-likes likes_{$post.id_simpleblog_post}" onclick="addRating({$post.id_simpleblog_post});"><svg class="svgic svgic-like smooth05"><use xlink:href="#si-like"></use></svg><div class="lmromandemi">{$post.likes}</div></div>
				</div>
			{/if}
			<div class="post-item">
				{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
					<figure>
						<a href="{$post.banner}" class="blog-main-image" title="">
							{if (Configuration::get('PH_BLOG_LIST_LAYOUT') == 'full') OR (Configuration::get('PH_BLOG_LIST_LAYOUT') == 'full_medium')}
								<img src="{$post.banner_wide}" alt="{$post.meta_title}" class="img-responsive" />
							{else}
								<img src="{$post.banner_thumb}" alt="{$post.meta_title}" class="img-responsive" />
							{/if}
						</a>
						{if $gridHtmlCols == "ph_col_small"} <!--MEDIUM-->
							<div class="blog-info">
								{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
								<div class="blog-date main_bg">
									<div>{$post.date_add|date_format:"%e <span>%b</span>"}</div>
								</div>
								{/if}					
								<div class="blog-post-likes likes_{$post.id_simpleblog_post}" onclick="addRating({$post.id_simpleblog_post});"><svg class="svgic svgic-like smooth05"><use xlink:href="#si-like"></use></svg><div class="lmromandemi">{$post.likes}</div></div>
							</div>
						{/if}
					</figure>
				{/if}
				<div class="post-additional-info">
					{if $gridHtmlCols == "ph_col"} <!--NOT MEDIUM-->
						{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
						<div class="blog-date main_bg">
							<div>{$post.date_add|date_format:"%e <span>%b</span>"}</div>
						</div>
						{/if}					
						<div class="blog-post-likes likes_{$post.id_simpleblog_post}" onclick="addRating({$post.id_simpleblog_post});"><svg class="svgic svgic-like smooth05"><use xlink:href="#si-like"></use></svg><div class="lmromandemi">{$post.likes}</div></div>
					{/if}					
					{if ($gridHtmlCols == "ph_col_medium") OR ($gridHtmlCols == "ph_col_small")} <!--MEDIUM SMALL-->
					<h2>
						<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
					</h2>
					{/if}
					<div class="blog-post-data">
						{if isset($post.author) && !empty($post.author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
							<span class="post-author">
								{$post.author}
							</span>
						{/if}
						{if $is_category eq false && Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
							<span class="post-category">
								<a href="{$post.category_url}" title="">{$post.category}</a>
							</span>
						{/if}					

						{if isset($post.tags) && $post.tags && Configuration::get('PH_BLOG_DISPLAY_TAGS')}
							<span class="post-tags clear dib">
								{foreach from=$post.tags item=tag name='tagsLoop'}
									{$tag}{if !$smarty.foreach.tagsLoop.last}, {/if}
								{/foreach}
							</span>
						{/if}
					</div>
				</div><!-- .additional-info -->

				<div class="post-info">
					{if ($gridHtmlCols == "ph_col") OR (Configuration::get('PH_BLOG_LIST_LAYOUT') == 'grid')} <!--BIG-->
					<h2>
						<a href="{$post.url}" title="{$post.meta_title}">{$post.meta_title}</a>
					</h2>
					{/if}
					{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
						{$post.short_content}
					{/if}<br/>
					<a href="{$post.url}" class="button" title="{l s='Permalink to' mod='ph_simpleblog'} {$post.meta_title}">{l s='Read more' mod='ph_simpleblog'}</a>
				</div>	
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
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
});

$(document).ready(function(){
	$.each($(".post-container"), function( index, value ) {
		  var pid = $(value).data("postid");
		  if ($.cookie('guest_{$cookie->id_guest}_'+pid) == "voted") {
			$(".likes_"+pid).addClass("voted");
		}
	});	
});
function addRating(item_id){	
	if ($.cookie('guest_{$cookie->id_guest}_'+item_id) != "voted") {
		$.cookie('guest_{$cookie->id_guest}_'+item_id, 'voted');
		var request = $.ajax({
		  type: "POST",
		  url: baseDir+'modules/ph_simpleblog/ajax.php',
		  data: { 
		  	action:'addRating',
			item_id : item_id 
		  },
		  success: function(result){             
		    	var data = $.parseJSON(result);
				if (data.status == 'success') {		
					$(".likes_"+item_id+" div").text(data.message);
					$(".likes_"+item_id).addClass("voted");
				} else {
					alert(data.message);
				}
		    }
		}); 		
	} else {
		$.cookie('guest_{$cookie->id_guest}_'+item_id, '');
		var request = $.ajax({
		  type: "POST",
		  url: baseDir+'modules/ph_simpleblog/ajax.php',
		  data: { 
		  	action:'removeRating',
			item_id : item_id 
			},
		  success: function(result){             
		    	var data = $.parseJSON(result);
				if (data.status == 'success') {		
					$(".likes_"+item_id).removeClass("voted");
					$(".likes_"+item_id+" div").text(data.message);
				} else {
					alert(data.message);
				}
		    }
		});
	}
	return false;
}

</script>