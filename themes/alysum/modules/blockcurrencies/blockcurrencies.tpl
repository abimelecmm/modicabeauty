<!-- Block currencies module -->
<div id="currencies_block_top" class="dib">
	<form id="setCurrency" action="{$request_uri}" method="post" class="dd_el">
		<div id="currencyHolder">
			<span class="dib smooth02">
				<svg class="svgic main_color svgic-money"><use xlink:href="#si-money"></use></svg>
				{foreach from=$currencies key=k item=f_currency}{if $id_currency_cookie == $f_currency.id_currency}<span>{$f_currency.name}</span>{/if}{/foreach}<svg class="svgic svgic-arrowdown"><use xlink:href="#si-arrowdown"></use></svg>
			</span>
			<ul class="dd_container">
				{foreach from=$currencies key=k item=f_currency}
				{if $id_currency_cookie != $f_currency.id_currency}
					<li class="dropdown-option smooth02 main_bg_hvr" data-value="{$f_currency.id_currency}" onclick="setCurrency($(this).data('value'));">
						<span class="currency-sign main_bg"><span class="main_color">{$f_currency.sign}</span></span>{$f_currency.name}
					</li>
				{/if}
				{/foreach}
			</ul>
			<div><b></b></div>
		</div>
		<p>
			<input type="hidden" name="id_currency" id="id_currency" value=""/>
			<input type="hidden" name="SubmitCurrency" value="" />			
		</p>
	</form>
</div>
<!-- /Block currencies module -->