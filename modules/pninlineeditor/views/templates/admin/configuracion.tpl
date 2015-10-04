<!-- Block inline editor -->

<link rel="stylesheet" type="text/css" media="screen" href="../modules/pninlineeditor/views/css/configuracion.css"/>





<form method="POST" name="pn_inlineeditor" id="pn_inlineeditor">



    <fieldset id="inline_editor" name="inline_editor">



        <legend><img src="../img/admin/cog.gif">{l s='Prestanitro Inline Editor' mod='pninlineeditor'}</legend>



        <div>

            <div id="left">

                <h3>Price Configuration</h3>



                <input id="PN_INLINE_IMPUESTOS" name="PN_INLINE_IMPUESTOS" class="inputCenter" type="checkbox" size="74"

                       value={$PN_INLINE_IMPUESTOS}  {if $PN_INLINE_IMPUESTOS}checked{/if} />

                {l s="I'm displaying prices tax included" mod='pninlineeditor'}



                <h3>Price Display</h3>

                Select the format your store is using to show big numbers.<br>

                <div>

                    <input type="radio" name="PN_INLINE_FORMATO" value="false" {if !$PN_INLINE_FORMATO}CHECKED{/if} >1,500.25&nbsp;

                    <br><input type="radio" name="PN_INLINE_FORMATO" value="true" {if $PN_INLINE_FORMATO}CHECKED{/if}>1.500,25

                </div>



                <h3>Enable/Disable editable elements</h3>

                <input id="PN_INLINE_EN_NOMBRE" name="PN_INLINE_EN_NOMBRE" class="inputCenter" type="checkbox" size="74"

                       value={$PN_INLINE_EN_NOMBRE|escape:'htmlall'}  {if $PN_INLINE_EN_NOMBRE}checked{/if} />

                {l s="Name" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_REF" name="PN_INLINE_EN_REF" class="inputCenter" type="checkbox" size="74"

                       value={$PN_INLINE_EN_REF|escape:'htmlall'}  {if $PN_INLINE_EN_REF}checked{/if} />

                {l s="Reference" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_PRECIO" name="PN_INLINE_EN_PRECIO" class="inputCenter" type="checkbox" size="74"

                       value={$PN_INLINE_EN_PRECIO|escape:'htmlall'}  {if $PN_INLINE_EN_PRECIO}checked{/if} />

                {l s="Price" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_CANTIDAD" name="PN_INLINE_EN_CANTIDAD" class="inputCenter" type="checkbox"

                       size="74"

                       value={$PN_INLINE_EN_CANTIDAD|escape:'htmlall'}  {if $PN_INLINE_EN_CANTIDAD}checked{/if} />

                {l s="Quantity" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_DESC_CORTA" name="PN_INLINE_EN_DESC_CORTA" class="inputCenter" type="checkbox"

                       size="74"

                       value={$PN_INLINE_EN_DESC_CORTA|escape:'htmlall'}  {if $PN_INLINE_EN_DESC_CORTA}checked{/if} />

                {l s="Short description" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_DESC" name="PN_INLINE_EN_DESC" class="inputCenter" type="checkbox" size="74"

                       value={$PN_INLINE_EN_DESC|escape:'htmlall'}  {if $PN_INLINE_EN_DESC}checked{/if} />

                {l s="Long description" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_RED_PERCENT" name="PN_INLINE_EN_RED_PERCENT" class="inputCenter" type="checkbox"

                       size="74"

                       value={$PN_INLINE_EN_RED_PERCENT|escape:'htmlall'}  {if $PN_INLINE_EN_RED_PERCENT}checked{/if} />

                {l s="I'm using percent discount in my store" mod='pninlineeditor'}<br>

                <input id="PN_INLINE_EN_RED_AMOUNT" name="PN_INLINE_EN_RED_AMOUNT" class="inputCenter" type="checkbox"

                       size="74"

                       value={$PN_INLINE_EN_RED_AMOUNT|escape:'htmlall'}  {if $PN_INLINE_EN_RED_AMOUNT}checked{/if} />

                {l s="I'm using amount discount in my store" mod='pninlineeditor'}<br>









                <h3>Change CSS path</h3>

                See documentation for more info.

                <div>

                    {l s="Name" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_NOMBRE" name="PN_INLINE_NOMBRE" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_NOMBRE|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Reference" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_REF" name="PN_INLINE_REF" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_REF|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Price" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_PRECIO" name="PN_INLINE_PRECIO" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_PRECIO|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Discount amount" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_RED_PERCENT" name="PN_INLINE_RED_PERCENT" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_RED_PERCENT|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Discount percent" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_RED_AMOUNT" name="PN_INLINE_RED_AMOUNT" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_RED_AMOUNT|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Quantity" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_CANTIDAD" name="PN_INLINE_CANTIDAD" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_CANTIDAD|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Short Description" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_DESC_CORTA" name="PN_INLINE_DESC_CORTA"

                           class="inputCenter" type=“number” size="74" value="{$PN_INLINE_DESC_CORTA|escape:'htmlall'}"/>

                </div>

                <div>

                    {l s="Long Description" mod='pninlineeditor'} </br>

                    <input class="pn_inline_input" id="PN_INLINE_DESC" name="PN_INLINE_DESC" class="inputCenter"

                           type=“number” size="74" value="{$PN_INLINE_DESC|escape:'htmlall'}"/>

                </div>









                <p id="submit_button" class="submit_button">

                    <input type="submit" name="submit_inlineeditor" class="button"/>

                </p>

            </div>



            <div id="right">

                <fieldset>

                    <legend><img src="../img/admin/info.png">{l s='About Us' mod='pninlineeditor'}</legend>



                    <div id="hire">

                        <p>Module developed by <a href="http://www.prestanitro.com">PrestaNitro</a>.

                            We can install this module in your store for 20€ and also accept customization working.

                            <mailto>Contact us</mailto>

                            to estimate work hours.

                            Feel free to contact us if you found any issue. Hire us at info@prestanitro.com

                        </p>

                    </div>

                    <div id="div_logo"><img id="pn_logo" src="http://www.prestanitro.com/logo-prestanitro.png"/></div>



                </fieldset>

            </div>





        </div>



    </fieldset>

</form>



<!-- /Block inline editor -->