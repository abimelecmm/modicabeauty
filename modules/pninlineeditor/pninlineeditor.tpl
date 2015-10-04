<button id="pninlineeditor-submit" class="pninlineeditor-submit"><i
            class="icon-save icon-2x icon-light"></i> {l s="Save product"}</button>
<script type="text/javascript">
    var pninlineeditor_saving = '<i class="icon-save icon-2x icon-light"></i> {l s="Saving product..."}';
    var pninlineeditor_saved = '<i class="icon-save icon-2x icon-light"></i> {l s="Product Saved!"}';
    var pninlineeditor_save = '<i class="icon-save icon-2x icon-light"></i> {l s="Save product"}';
    var pninlineeditor_error = '<i class="icon-save icon-2x icon-light"></i> {l s="Error Saving"}';
    var pninlineeditor_desc_error = '{l s="Short description is too long. Max size is "}{$PN_INLINE_DESC_CORTA_LIMIT}{l s=" characters. In order to save, please reduce the length deleting text or images or go to Preferences-Product in your backoffice and set a higer limit."}';
    var pninlineeditor_precio = "{$PN_INLINE_PRECIO}";
    var pninlineeditor_nombre = "{$PN_INLINE_NOMBRE}";
    var pninlineeditor_referencia = "{$PN_INLINE_REF}";
    var pninlineeditor_cantidad = "{$PN_INLINE_CANTIDAD}";
    var pninlineeditor_desc = "{$PN_INLINE_DESC}";
    var pninlineeditor_desc_corta = "{$PN_INLINE_DESC_CORTA}";
    var pninlineeditor_red_amount = "{$PN_INLINE_RED_AMOUNT}";
    var pninlineeditor_red_percent = "{$PN_INLINE_RED_PERCENT}";
    var pninlineeditor_en_precio = {if $PN_INLINE_EN_PRECIO}true{else}false{/if};
    var pninlineeditor_en_nombre = {if $PN_INLINE_EN_NOMBRE}true{else}false{/if};
    var pninlineeditor_en_referencia = {if $PN_INLINE_EN_REF}true{else}false{/if};
    var pninlineeditor_en_cantidad = {if $PN_INLINE_EN_CANTIDAD}true{else}false{/if};
    var pninlineeditor_en_desc = {if $PN_INLINE_EN_DESC}true{else}false{/if};
    var pninlineeditor_en_desc_corta = {if $PN_INLINE_EN_DESC_CORTA}true{else}false{/if};
    var pninlineeditor_en_red_amount = {if $PN_INLINE_EN_RED_AMOUNT}true{else}false{/if};
    var pninlineeditor_en_red_percent = {if $PN_INLINE_EN_RED_PERCENT}true{else}false{/if};
    var pninlineeditor_impuestos = {if $PN_INLINE_IMPUESTOS}true{else}false{/if};
    var pninlineeditor_desc_corta_limit = {$PN_INLINE_DESC_CORTA_LIMIT};
    var pninlineditor_formato= {if $PN_INLINE_FORMATO}true{else}false{/if};
    var pninlineeditor_fileman = '{$base_uri}modules/pninlineeditor/fileman/index.html';
    var pninlineeditor_kcfinder = '{$base_uri}modules/pninlineeditor/kcfinder/';

</script>
<script type="text/javascript" src="{$base_uri}modules/pninlineeditor/ckeditor/ckeditor.js"></script>



