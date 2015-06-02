{if $page_name == "index"}
<div class="ph_simpleblog simpleblog-recent homemodule load-animate">
	{if isset($posts) && count($posts) > 0}
	<table class="title-table">
      <tr>
        <td class="w50p"><span class="right-wing title-wing"></span></td>
        <td class="carousel-title"><h3 class="lmroman">{l s='Recent posts' mod='ph_recentposts'}</h3></td>
        <td class="w50p"><span class="left-wing title-wing"></span></td>
      </tr>
    </table>
	<div class="ph_row simpleblog-recentposts">
		{foreach from=$posts item=post}
		<div class="post-item {$gridHtmlCols} post-item-{$post.id_simpleblog_post}">
			<div class="post-item" data-postid="{$post.id_simpleblog_post}">
				{if isset($post.banner) && Configuration::get('PH_BLOG_DISPLAY_THUMBNAIL')}
					<figure>
						<a href="{$post.url}" title="" class="blog-main-image" style="background-image: url('{$post.banner_wide}')">
						</a>
						<div class="blog-info">
							{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
							<div class="blog-date main_bg bshadow lmromandemi">
								<div>{$post.date_add|date_format:"%e <span>%b</span>"}</div>
							</div>
							{/if}					
							<div class="blog-post-likes grayshadow likes_{$post.id_simpleblog_post}" onclick="addRating({$post.id_simpleblog_post});"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg><div class="lmromandemi">{$post.likes}</div></div>
						</div>						
					</figure>
				{/if}
				<div class="blog-post-info">
					<h2 class="lmroman">
						<a href="{$post.url}" title="{$post.meta_title}" class="ellipsis">{$post.meta_title}</a>
					</h2>
					<div class="post-additional-info">
						{if isset($post.author) && !empty($post.author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}<span class="post-author dib">{$post.author} </span>{/if}
						{if ($post.tags)}
							<span class="post-tags dib">{foreach from=$post.tags item=tag name=tags}{if $smarty.foreach.tags.index != 0}, {/if}{$tag}{/foreach}</span>
						{/if}
						<span class="post-views dib"><svg class="svgic svgic-eye"><use xlink:href="#si-eye"></use></svg> {$post.views}</span>
					</div><!-- .additional-info -->
					{if Configuration::get('PH_BLOG_DISPLAY_DESCRIPTION')}
						<div class="blog-post-desc">{$post.short_content|substr:0:175}{if strlen($post.short_content)>175}...{/if}</div>
					{/if}
					<a href="{$post.url}" class="button" title="{l s='Permalink to' mod='ph_recentposts'} {$post.meta_title}">{l s='Read more' mod='ph_recentposts'}</a>
				</div>	
				
			</div>
		</div>
		{/foreach}
	</div><!-- .ph_row -->
	{else}
	<p class="warning">{l s='There are no posts' mod='ph_recentposts'}</p>
{/if}
</div><!-- .ph_simpleblog -->
<script>
jQuery(document).ready(function() {
	$.each($(".simpleblog-recent .post-item"), function( index, value ) {
		  var pid = $(value).data("postid");
		  if ($.cookie('guest_{$cookie->id_guest}_'+pid) == "voted") {
			$(".post-item-"+pid+" .blog-post-likes").addClass("voted");
		}
	});	
  	$(".simpleblog-recentposts").flexisel({
        pref: "recposts",
        visibleItems: 2,
        animationSpeed: 500,
        autoPlay: true,
        autoPlaySpeed: 3000,            
        pauseOnHover: true,
        enableResponsiveBreakpoints: true,
        clone : true,
        responsiveBreakpoints: { 
            portrait: { 
                changePoint:320,
                visibleItems: 1
            }, 
            landscape: { 
                changePoint:480,
                visibleItems: 1
            },
            tablet: { 
                changePoint:768,
                visibleItems: 2
            },
            tablet_land: { 
                changePoint:1001,
                visibleItems: 2
            }
        }
  	});	
  	$(".simpleblog-recent").find(".flexisel-nav").appendTo(".simpleblog-recent .carousel-title");
});
function addRating(item_id){	
	if ($.cookie('guest_{$cookie->id_guest}_'+item_id) != "voted") {
		$.cookie('guest_{$cookie->id_guest}_'+item_id, 'voted');
		$.post(baseDir+'modules/ph_simpleblog/ajax.php', {
			action:'addRating',
			item_id : item_id
		}, 
		function (data) {
			if (data.status == 'success') {		
				$(".post-item-"+item_id+" .blog-post-likes").addClass("voted");
				$(".post-item-"+item_id+" .blog-post-likes div").text(data.message);
			} else {
				alert(data.message);
			}
			
		}, 'json');
	} else {
		$.cookie('guest_{$cookie->id_guest}_'+item_id, '');
		$.post(baseDir+'modules/ph_simpleblog/ajax.php', {
			action:'removeRating',
			item_id : item_id
		}, 
		function (data) {
			if (data.status == 'success') {		
				$(".post-item-"+item_id+" .blog-post-likes div").text(data.message);
				$(".post-item-"+item_id+" .blog-post-likes").removeClass("voted");
			} else {
				alert(data.message);
			}
			
		}, 'json');
	}
	return false;
}
</script>
{/if}