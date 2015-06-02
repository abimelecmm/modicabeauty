{*
* Copyright (C) 2012  S.C Minic Studio S.R.L.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
* @author     S.C Minic Studio S.R.L.
* @copyright  Copyright S.C Minic Studio S.R.L. 2012. All rights reserved.
* @license    GPLv2 License http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
* @version    v3.0.0
*}

<fieldset id="newSlide" class="hidden-div">
    <legend>{l s='Add New Slide' mod='pk_promominicslider'}</legend> 
    <form method="post" action="{$slider.postAction}" enctype="multipart/form-data">        
        <div id="new">
            <div class="title input">
                <label>{l s='Title' mod='pk_promominicslider'}</label>
                <input type="text" name="title" size="41" class="ghost-text sl-tooltip" value="{l s='The title of the slide' mod='pk_promominicslider'}" title="{l s='This will be the title on the slide.' mod='pk_promominicslider'}" /> 
            </div>
            <div class="url input">
                <label>{l s='Url' mod='pk_promominicslider'}</label>
                <input type="text" name="url" size="41" class="ghost-text sl-tooltip" value="{l s='Link of the slide' mod='pk_promominicslider'}" title="{l s='ex. http://myshop.com/promotions' mod='pk_promominicslider'}" />           
            </div>
            <div class="target">
                <label>{l s='Blank target' mod='pk_promominicslider'}</label>
                <input type="checkbox" name="target" class="sl-tooltip" value="1" title="{l s='Check this if you want to open the link in new window.' mod='pk_promominicslider'}" />
            </div>
            <div class="image input">
                <label>{l s='Image' mod='pk_promominicslider'}</label>
                <input type="file" name="image" size="29" id="image-chooser" class="sl-tooltip" title="{l s='Choose an image, only .jpg, .png, .gif are allowed.' mod='pk_promominicslider'}" />
                <strong style="margin-left:154px; color:#ff715c">{l s='Recommended image dimensions are 568x568px' mod='pk_promominicslider'}</strong>
            </div>
            <div class="imageName input">
                <label>{l s='Image name' mod='pk_promominicslider'}</label>
                <input type="text" name="imageName" size="41" class="ghost-text sl-tooltip" value="{l s='Image name' mod='pk_promominicslider'}" title="{l s='Optional! The name of the uploaded image without extension. The white spaces will be replaces with underscore ( _ )' mod='pk_promominicslider'}" />           
            </div>
            {if $slider.options.single == 1}
                <div class="language">
                    <label>{l s='Language' mod='pk_promominicslider'}</label>
                    <select name="language" class="sl-tooltip" style="display:inline" title="{l s='The language of the slide.' mod='pk_promominicslider'}">
                        {foreach from=$slider.lang.all item=lang}
                            <option value="{$lang.id_lang}" {if $lang.id_lang == $slider.lang.default.id_lang}selected="selected"{/if}>{$lang.name}</option>
                        {/foreach}
                    </select>
                </div>
            {/if}
            <div class="alt input">
                <label>{l s='Image alt' mod='pk_promominicslider'}</label>
                <input type="text" name="alt" size="41" class="ghost-text sl-tooltip" value="{l s='An alternate text for the image' mod='pk_promominicslider'}" title="{l s='The image alt, alternate text for the image' mod='pk_promominicslider'}" />
            </div>
            <div class="caption"> 
                <label>{l s='Caption' mod='pk_promominicslider'}</label>
                <textarea type="text" name="caption" cols=40 rows=6 class="ghost-text sl-tooltip" title="{l s='Be carefull, too long text isnt good and FULL HTML is allowed.' mod='pk_promominicslider'}">{l s='The slide text' mod='pk_promominicslider'}</textarea>                      
            </div>
            <div class="button_cont">
                <input type="submit" name="submitNewSlide" value="{l s='Add Slide' mod='pk_promominicslider'}" class="green large" />
                {if $slider.options.single == 0}
                    <input type="hidden" name="language" value="{$slider.lang.default.id_lang}" />
                {/if}
            </div> 
        </div> 
    </form>
</fieldset>