{if ($minicSlider.options.front == 0) || ($page_name == "index") }
<!-- PROMOMINIC SLIDER -->
    {foreach from=$currencies key=k item=f_currency}
        {if $cookie->id_currency == $f_currency.id_currency}
            {assign var="current_currency" value=$f_currency.sign}
        {/if}
    {/foreach}
    {if $minicSlider.options.single == 0}
        {if $slides.$def_iso|@count != 0}
            {if ($minicSlider.options.front == 1)}                
                {if $page_name == 'index'}
                    {include file="{$minicSlider.tpl}"}
                {/if}
            {else}
                {include file="{$minicSlider.tpl}"}
            {/if}            
        {/if}
    {else}
    {if $slides.$lang_iso|@count != 0}
            {if ($minicSlider.options.front == 1)}                
                {if $page_name == 'index'}
                    {include file="{$minicSlider.tpl}"}
                {/if}
            {else}
                {include file="{$minicSlider.tpl}"}
            {/if}        
        {/if}
    {/if}
<!-- PROMOMINIC SLIDER -->
{/if}