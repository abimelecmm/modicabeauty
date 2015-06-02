<!-- Block Newsletter module-->
<div id="newsletter_block_left" class="block">
	<div class="newsletter-bg hidden sec_bg"></div>
	<h4 class="dib">{l s='sign up to receive the latest news' mod='blocknewsletter'}</h4>
	<div class="block_content dib">
	{if isset($msg) && $msg}
		<p class="dib {if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
	{/if}
		<form action="{$link->getPageLink('index')}" method="post" class="dib">
			<input type="text" name="email" size="18" 
				value="{if isset($value) && $value}{$value}{else}{l s='your e-mail' mod='blocknewsletter'}{/if}" 
				onfocus="javascript:if(this.value=='{l s='your e-mail' mod='blocknewsletter'}')this.value='';" 
				onblur="javascript:if(this.value=='')this.value='{l s='your e-mail' mod='blocknewsletter'}';" 
				class="inputNew" />
				<input type="submit" value="Sign Up" class="button_mini lmromancaps" name="submitNewsletter" />
			<input type="hidden" name="action" value="0" />
		</form>
	</div>
</div>
<script>
$(document).ready(function() {
	if ( $('#footer #newsletter_block_left')[0] ) {
        $(".newsletter-bg").removeClass('hidden').appendTo("#footer");
    }
});
</script>
<!-- /Block Newsletter module-->
