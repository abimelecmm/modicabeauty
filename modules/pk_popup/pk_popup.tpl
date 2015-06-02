<div class="pk_popup" style="{if $pk_ppp.PK_POPUP_WIDTH}width:{$pk_ppp.PK_POPUP_WIDTH}px;{/if}{if $pk_ppp.PK_POPUP_HEIGHT}height:{$pk_ppp.PK_POPUP_HEIGHT}px;{/if}{if $pk_ppp.PK_POPUP_BG == 1}background-image: url({$link->getMediaLink("`$module_dir`$popup_bg")});{/if}">
	<div id="newsletter_block_popup" class="block">
		<div class="block_content">
		{if isset($msg) && $msg}
			<p class="{if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
		{/if}
			<form action="{$link->getPageLink('index')|escape:'html'}" method="post">
				{if $pk_ppp.PK_POPUP_TEXT != ""}<div class="popup_text">{$pk_ppp.PK_POPUP_TEXT}</div>{/if}
                {if $pk_ppp.PK_POPUP_NEWSLETTER == 1}
				<!--<input class="inputNew popup_name" type="text" value="{l s='first name' mod='pk_popup'}" />-->
				<input class="inputNew dib" id="newsletter-input-popup" type="text" name="email" value="{l s='Enter your email' mod='pk_popup'}" /><div class="send-reqest button_unique main_color_hover smooth02 dib"><svg class="svgic svgic-right-arrow"><use xlink:href="#si-right-arrow"></use></svg></div>
                <div class="send-response"></div>
                {/if}
			</form>
		</div>
	</div>
</div>
{if $pk_ppp.PK_POPUP_NEWSLETTER == 1}
<script type="text/javascript">
    var placeholder2 = "{l s='Enter your email' mod='pk_popup' js=1}";
    {literal}
        $(document).ready(function() {
            $('#newsletter-input-popup').on({
                focus: function() {
                    if ($(this).val() == placeholder2) {
                        $(this).val('');
                    }
                },
                blur: function() {
                    if ($(this).val() == '') {
                        $(this).val(placeholder2);
                    }
                }
            });
        });
    {/literal}
</script>
{/if}
{strip}
{addJsDef pk_popup_width=$pk_ppp.PK_POPUP_WIDTH}
{addJsDef pk_popup_height=$pk_ppp.PK_POPUP_HEIGHT}
{addJsDef pk_popup_newsletter=$pk_ppp.PK_POPUP_NEWSLETTER}
{addJsDef pk_popup_bg=$pk_ppp.PK_POPUP_BG}
{addJsDef pk_popup_path=$pk_ppp.PK_POPUP_PATH}
{/strip}