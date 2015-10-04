<button id="pninlineeditor-submit" class="pninlineeditor-submit"><i
            class="icon-save icon-2x icon-light"></i> {l s="Save product" mod="pninlineeditor"}</button>
<script type="text/javascript">
    var pninlineeditor_saving = '<i class="icon-save icon-2x icon-light"></i> {l s="Saving product..." mod="pninlineeditor" }';
    var pninlineeditor_saved = '<i class="icon-save icon-2x icon-light"></i> {l s="Product Saved!" mod="pninlineeditor"}';
    var pninlineeditor_save = '<i class="icon-save icon-2x icon-light"></i> {l s="Save product" mod="pninlineeditor"}';
    var pninlineeditor_error = '<i class="icon-save icon-2x icon-light"></i> {l s="Error Saving" mod="pninlineeditor"}';
    var pninlineeditor_desc_error = '{l s="Short description is too long. Max size is "  mod="pninlineeditor"}{$PN_INLINE_DESC_CORTA_LIMIT|escape:'htmlall'|unescape:'htmlall'}{l s=" characters. In order to save, please reduce the length deleting text or images or go to Preferences-Product in your backoffice and set a higer limit." mod="pninlineeditor"}';
    var pninlineeditor_precio = "{$PN_INLINE_PRECIO|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_nombre = "{$PN_INLINE_NOMBRE|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_referencia = "{$PN_INLINE_REF|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_cantidad = "{$PN_INLINE_CANTIDAD|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_desc = "{$PN_INLINE_DESC|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_desc_corta = "{$PN_INLINE_DESC_CORTA|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_red_amount = "{$PN_INLINE_RED_AMOUNT|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_red_percent = "{$PN_INLINE_RED_PERCENT|escape:'htmlall'|unescape:'htmlall'}";
    var pninlineeditor_en_precio = {if $PN_INLINE_EN_PRECIO}true{else}false{/if};
    var pninlineeditor_en_nombre = {if $PN_INLINE_EN_NOMBRE}true{else}false{/if};
    var pninlineeditor_en_referencia = {if $PN_INLINE_EN_REF}true{else}false{/if};
    var pninlineeditor_en_cantidad = {if $PN_INLINE_EN_CANTIDAD}true{else}false{/if};
    var pninlineeditor_en_desc = {if $PN_INLINE_EN_DESC}true{else}false{/if};
    var pninlineeditor_en_desc_corta = {if $PN_INLINE_EN_DESC_CORTA}true{else}false{/if};
    var pninlineeditor_en_red_amount = {if $PN_INLINE_EN_RED_AMOUNT}true{else}false{/if};
    var pninlineeditor_en_red_percent = {if $PN_INLINE_EN_RED_PERCENT}true{else}false{/if};
    var pninlineeditor_impuestos = {if $PN_INLINE_IMPUESTOS}true{else}false{/if};
    var pninlineeditor_desc_corta_limit = {$PN_INLINE_DESC_CORTA_LIMIT|escape:'htmlall'|unescape:'htmlall'};
    var pninlineditor_formato= {if $PN_INLINE_FORMATO}true{else}false{/if};
    var pninlineeditor_fileman = '{$base_uri|escape:'htmlall'|unescape:'htmlall'}modules/pninlineeditor/fileman/index.html';
    var pninlineeditor_kcfinder = '{$base_uri|escape:'htmlall'|unescape:'htmlall'}modules/pninlineeditor/kcfinder/';

</script>
<script type="text/javascript" src="{$base_uri|escape:'htmlall'|unescape:'htmlall'}modules/pninlineeditor/ckeditor/ckeditor.js"></script>



