{if Configuration::get('PH_BLOG_DISPLAY_BREADCRUMBS')}
	{capture name=path}
		<a href="{ph_simpleblog::getLink()}">{l s='Blog' mod='ph_simpleblog'}</a>
		<span class="navigation-pipe">{$navigationPipe}</span> <a href="{$post->category_url}">{$post->category}</a>
		<span class="navigation-pipe">{$navigationPipe}</span> {$post->meta_title}
	{/capture}
	{if !$is_16}{include file="$tpl_dir./breadcrumb.tpl"}{/if}
{/if}

<div class="ph_simpleblog simpleblog-single">
	<h1>{$post->meta_title}</h1>
	
	<div class="post-content">
		{$post->content}
	</div><!-- .post-content -->

	<div class="post-additional-info">
		{if Configuration::get('PH_BLOG_DISPLAY_DATE')}
			<span class="post-date">
				{l s='Posted on:' mod='ph_simpleblog'} {$post->date_add}
			</span>
		{/if}
		
		{if Configuration::get('PH_BLOG_DISPLAY_CATEGORY')}
			<span class="post-category">
				{l s='Posted in:' mod='ph_simpleblog'} <a href="{$post->category_url}" title="">{$post->category}</a>
			</span>
		{/if}

		{if isset($post->author) && !empty($post->author) && Configuration::get('PH_BLOG_DISPLAY_AUTHOR')}
			<span class="post-author">
				{l s='Author:' mod='ph_simpleblog'} {$post->author} 
			</span>
		{/if}

		{if $post->tags && Configuration::get('PH_BLOG_DISPLAY_TAGS') && isset($post->tags_list)}
			<span class="post-tags clear">
				{l s='Tags:' mod='ph_simpleblog'} 
				{foreach from=$post->tags_list item=tag name='tagsLoop'}
					{$tag}{if !$smarty.foreach.tagsLoop.last}, {/if}
				{/foreach}
			</span>
		{/if}
	</div><!-- .additional-info -->

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
</script>