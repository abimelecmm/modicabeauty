{if Configuration::get('PH_BLOG_DISPLAY_BREADCRUMBS')}
	{capture name=path}
		<a href="{ph_simpleblog::getLink()}">{l s='Blog' mod='ph_simpleblog'}</a>
		<span class="navigation-pipe">{$navigationPipe}</span> <a href="{$post->category_url}">{$post->category}</a>
		<span class="navigation-pipe">{$navigationPipe}</span> {$post->meta_title}
	{/capture}
	{if !$is_16}{include file="$tpl_dir./breadcrumb.tpl"}{/if}
{/if}

<div class="ph_simpleblog simpleblog-single" data-postid="{$post->id_simpleblog_post}">
	<div class="img_container">
		<img src="{$post->banner}" alt="{$post->meta_title}" title="{$post->meta_title}" />
	</div>	
	<div class="blog-info">
		{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
		<div class="blog-date main_bg">
			<div>{$post->date_add|date_format:"%e <span>%b</span>"}</div>
		</div>
		{/if}					
		<div class="blog-post-likes likes_{$post->id_simpleblog_post}" onclick="addRating({$post->id_simpleblog_post});"><svg class="svgic svgic-like"><use xlink:href="#si-like"></use></svg><div class="lmromandemi">{$post->likes}</div></div>

		<div class="post-additional-info">
			{if isset($post->author) && !empty($post->author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
				<span class="post-author">
					{$post->author} 
				</span>
			{/if}

			{if Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
				<span class="post-category">
					<a href="{$post->category_url}" title="">{$post->category}</a>
				</span>
			{/if}			

			{if $post->tags && Configuration::get('PH_BLOG_DISPLAY_TAGS') && isset($post->tags_list)}
				<span class="post-tags clear">
					{foreach from=$post->tags_list item=tag name='tagsLoop'}
						{$tag}{if !$smarty.foreach.tagsLoop.last}, {/if}
					{/foreach}
				</span>
			{/if}
		</div><!-- .additional-info -->

	</div>	
	<div class="post-content">
		<h1>{$post->meta_title}</h1>
		<div class="post-content">{$post->content}</div>
	</div><!-- .post-content -->

	{if Configuration::get('PH_BLOG_DISPLAY_SHARER')}
		<div class="post-share-buttons">
			<div class="fb-like" data-href="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" data-width="80" data-height="20" data-colorscheme="light" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}">Tweet</a>
			<div class="g-plusone" data-size="medium" data-annotation="none" data-href="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"></div>
		</div><!-- .share-buttons -->
	{/if}

	{if Configuration::get('PH_BLOG_FB_COMMENTS')}
		<div class="fb-comments" data-href="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" data-colorscheme="light" data-numposts="5" data-width="535"></div>
	{/if}
</div><!-- .ph_simpleblog -->

{if Configuration::get('PH_BLOG_FB_INIT')}
<script>
var lang_iso = '{$lang_iso}_{$lang_iso|@strtoupper}';
{literal}(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/"+lang_iso+"/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
{/literal}
</script>
{/if}

<script>
$(function() {
	$('body').addClass('simpleblog simpleblog-single');
});
{literal}
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');

(function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
{/literal}
$(document).ready(function(){
	var pid = $(".ph_simpleblog").data("postid");
	if ($.cookie('guest_{$cookie->id_guest}_'+pid) == "voted") {
		$(".blog-post-likes").addClass("voted");
	}
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
					$(".blog-post-likes").addClass("voted");
					$(".blog-post-likes div").text(data.message);
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
					$(".blog-post-likes").removeClass("voted");
					$(".blog-post-likes div").text(data.message);
				} else {
					alert(data.message);
				}
		    }
		});
	}
	return false;
}
</script>