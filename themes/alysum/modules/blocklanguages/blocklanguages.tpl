<!-- Block languages module -->
<div id="languages_block_top" class="dib">
	<div id="countries" class="dd_el">
		{foreach from=$languages key=k item=language name="languages"}
			{if $language.iso_code == $lang_iso}
				<span class="selected_language smooth02">
					{assign var=indice_lang value=$language.id_lang}
					<img src="{$img_lang_dir}{$language.id_lang}.jpg" class="dib" width="16" height="11" alt="{$language.iso_code}" />{if isset($lang_rewrite_urls.$indice_lang)}<a class="dib" href="{$lang_rewrite_urls.$indice_lang|escape:htmlall}">{else}<a class="dib" href="{$link->getLanguageLink($language.id_lang)|escape:htmlall}">
					{/if}{$language.name}</a><svg class="svgic svgic-account"><use xlink:href="#si-arrowdown"></use></svg>
				</span>
			{/if}
		{/foreach}
		<ul id="first-languages" class="countries_ul dd_container">
		{foreach from=$languages key=k item=language name="languages"}
			{if $language.iso_code != $lang_iso}
			<li class="smooth02 main_bg_hvr">
			{if $language.iso_code != $lang_iso}
				{assign var=indice_lang value=$language.id_lang}
				<img src="{$img_lang_dir}{$language.id_lang}.jpg" class="dib" width="16" height="11" alt="{$language.iso_code}" />{if isset($lang_rewrite_urls.$indice_lang)}<a href="{$lang_rewrite_urls.$indice_lang|escape:htmlall}" title="{$language.name}">{else}<a href="{$link->getLanguageLink($language.id_lang)|escape:htmlall}" title="{$language.name}">{/if}{/if}{$language.name}{if $language.iso_code != $lang_iso}</a>
			{/if}
			</li>
			{/if}
		{/foreach}
		</ul>
	</div>
</div>
<!-- /Block languages module -->