<div class="instagram-feed homemodule block ig_{$pk_ig_suffix} {if $pk_ig.PK_INSTA_CAROUSEL == false} instalist{/if}{if $pk_ig.PK_INSTA_BACKGROUND == 1} instabg{else} noinstabg{/if}{if ($pk_ig.PK_INSTA_COLOR == true)} light-color{else} dark-color{/if}" {if $pk_ig.PK_INSTA_BACKGROUND == 1}style='background-image:url({$link->getMediaLink("`$module_dir`$insta_bg")});'{/if}>
	<div class="container">
		<div class="row instafeed-container">
			<div class="col-xs-12">
				<table class="title-table">
			      <tr>
			        <td class="w50p"><span class="right-wing title-wing"></span></td>
			        <td class="carousel-title"><h3 class="lmroman">{l s='Instagram Feed' mod='pk_instafeed'}</h3></td>
			        <td class="w50p"><span class="left-wing title-wing"></span></td>
			      </tr>
			    </table>
				<ul id="instafeed_{$pk_ig_suffix}"></ul>
			</div>
		</div>
	</div>
</div>
{assign var='ilikes' value=''}
{assign var='icomments' value=''}
{assign var='icaption' value=''}
{assign var='ilink' value='{{link}}'}
{assign var='iimage' value='{{image}}'}
{if $pk_ig.PK_INSTA_LINKS}{assign var='ilikes' value='{{likes}}'}{/if}
{if $pk_ig.PK_INSTA_COMMENTS}{assign var='icomments' value='{{comments}}'}{/if}
{if $pk_ig.PK_INSTA_CAPTION}{assign var='icaption' value='{{caption}}'}{/if}
{assign var="template" value="<li><div class=\'ig-indent\'><div class=\'ig-wrapper tr05\'><a target=\'_blank\' class=\'ig-link\' href=\'`$ilink`\'><img alt=\'instagram-image\' title=\'`$icaption`\' width=\'306\' height=\'306\' src=\'`$iimage`\' /><span class=\'ig-caption\'>`$icaption`</span><span class=\'ig-likes ig-icon\'><svg class=\'svgic main_color dib svgic-eye\'><use xlink:href=\'#si-eye\'></use></svg>`$ilikes`</span><span class=\'ig-comments ig-icon\'><svg class=\'svgic main_color dib svgic-comment\'><use xlink:href=\'#si-comment\'></use></svg>`$icomments`</span></a></div></div></li>"}
{strip}
{addJsDef pk_insta_api_code=$pk_ig.PK_INSTA_API_CODE}
{addJsDef pk_insta_api_secret=$pk_ig.PK_INSTA_API_SECRET}
{addJsDef pk_insta_at=$pk_ig.PK_INSTA_AT}
{addJsDef pk_insta_api_callback=$pk_ig.PK_INSTA_API_CALLBACK}
{addJsDef pk_insta_carousel=$pk_ig.PK_INSTA_CAROUSEL}
{addJsDef pk_insta_content_type=$pk_ig.PK_INSTA_CONTENT_TYPE}
{addJsDef pk_insta_username=$pk_ig.PK_INSTA_USERNAME}
{if $pk_ig.PK_INSTA_USERID != false}
	{addJsDef pk_insta_userid=$pk_ig.PK_INSTA_USERID->data.0->id}
{else}
	{addJsDef pk_insta_userid=0}
{/if}
{addJsDef pk_insta_sortby=$pk_ig.PK_INSTA_SORTBY}
{addJsDef pk_insta_number=$pk_ig.PK_INSTA_NUMBER}
{addJsDef pk_insta_number_vis=$pk_ig.PK_INSTA_NUMBER_VIS}
{addJsDef pk_insta_hashtag=$pk_ig.PK_INSTA_HASHTAG}
{addJsDef pk_insta_links=$pk_ig.PK_INSTA_LINKS}
{addJsDef pk_insta_back=$pk_ig.PK_INSTA_BACKGROUND}
{addJsDef pk_insta_auto=$pk_ig.PK_INSTA_AUTOSCROLL}
{addJsDef pk_insta_template=$template}
{addJsDef pk_insta_suffix=$pk_ig_suffix}
{/strip}