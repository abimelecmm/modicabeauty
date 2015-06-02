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

<fieldset id="list_slide" class="">
    <legend>{l s='Slides' mod='pk_promominicslider'}</legend>
    <div>
    <div id="navigation">
        {if $slider.options.single == 0}
            <a href="#{$slider.lang.default.iso_code}_slides" class="navigation active">
                <img src="{$slider.lang.lang_dir}{$slider.lang.default.id_lang}.jpg" />
                {$slider.lang.default.name}
            </a>
        {else}
            {foreach from=$slider.lang.all item=lang}
                <a href="#{$lang.iso_code}_slides" class="navigation {if $lang.iso_code == $slider.lang.default.iso_code}active{/if}">
                    <img src="{$slider.lang.lang_dir}{$lang.id_lang}.jpg" />
                    {$lang.name}
                </a>
            {/foreach}    
        {/if}
    </div>
    <div class="titles">
        <span class="order">{l s="Order" mod='pk_promominicslider'}</span>
        <span class="title">{l s="Title" mod='pk_promominicslider'}</span>
        <span class="active">{l s="Active" mod='pk_promominicslider'}</span>
        <span class="arrow"></span>    
    </div>
    <div class="slides_holder">  
        {foreach name=languages from=$slider.slides key=iso item=lang}
            <ul id="{$iso}_slides" class="languages">
                {foreach name=slides from=$lang item=slide}
                    <li id="order_{$slide.id_slide}h{$slide.id_order}" class="slide_elem" >
                        <div class="slide_header {if $slide.active != 1}inactive{/if}">
                            <span class="order">
                                <span></span>
                                {if $slide.id_order le 9}0{/if}{$slide.id_order}
                            </span>
                            <span class="title">{$slide.title}</span>
                            <span class="{if $slide.active == 1}active{else}deactivated{/if}"></span>
                            <span class="arrow"></span>
                        </div>
                        <div class="slide_body">
                              <form id="{$iso}_{$slide.id_order}" method="post" action="{$slider.postAction}" enctype="multipart/form-data">
                                  <div class="image_holder">
                                      <img src="{$module_dir}uploads/thumbs/admin_{$slide.image}" />
                                      <div class="file_input">
                                          <input type="file" name="newImage" class="file"/>
                                          <div>
										      <span></span>                          
										      <input type="submit" value="{l s='Change image' mod='pk_promominicslider'}"/>                          
									      </div>                        
                                      </div>                        <br/>         
                                      <strong style="color:#ff715c; clear:both; display:block">{l s='Recommended image dimensions are 600x600px' mod='pk_promominicslider'}</strong>
                                  </div>                                  
                                  <div class="form_cont">
                                      <div class="title input">
                                          <label>{l s='Title' mod='pk_promominicslider'}: </label>
                                          <input type="text" name="title" class="sl-tooltip" size="41" value="{$slide.title}" title="{l s='This will be the title on the slide.' mod='pk_promominicslider'}" /> 
                                      </div>
                                      <div class="url input">
                                      	<label>{l s='Url' mod='pk_promominicslider'}: </label>
                                      	<input type="text" name="url" class="sl-tooltip" size="41" value="{$slide.url}" title="{l s='ex. http://myshop.com/promotions' mod='pk_promominicslider'}" />           
                                      </div>
                                      <div class="target">
                                          <label>{l s='Blank target' mod='pk_promominicslider'}: </label>
                                          <input type="checkbox" name="target" class="sl-tooltip" value="1" {if $slide.target == 1}checked="true"{/if} title="{l s='Check this if you want to open the link in new window.' mod='pk_promominicslider'}" />
                                      </div>
                                      <div class="alt input">
                                          <label>{l s='Image alt' mod='pk_promominicslider'}: </label>
                                          <input type="text" name="alt" class="sl-tooltip" size="41" value="{$slide.alt}" title="{l s='The image alt, alternate text for the image' mod='pk_promominicslider'}" />
                                      </div>
                                      <div class="caption"> 
                                      	<label>{l s='Caption' mod='pk_promominicslider'}: </label>
                                      	<textarea type="text" name="caption" class="sl-tooltip" cols="40" rows="6" title="{l s='Be carefull, too long text isnt good and HTML is not allowed.' mod='pk_promominicslider'}" >{$slide.caption}</textarea>                      
                                      </div>
                                      <div class="switch">
                                          <label>{l s='Active' mod='pk_promominicslider'}: </label>
                  						<div class="field switch">
                  							<input type="radio" id="active-e" class="" name="isActive"  value="{$slide.active}" checked="true" />
                  							<label for="r-keyboard-e" class="cb-enable {if $slide.active == 1}selected{/if}" ><span>ON</span></label>
                  							<label for="r-keyboard-d" class="cb-disable {if $slide.active == 0}selected{/if}" ><span>OFF</span></label>
                  						</div>
                                      </div>
                                  </div>     
                                  <div class="button_cont">
                                      <input type="hidden" name="slideId" value="{$slide.id_slide}" />
                                      <input type="hidden" name="orderId" value="{$slide.id_order}" />
                                      <input type="hidden" name="slideIso" value="{$slide.lang_iso}" />
                                      <input type="hidden" name="oldImage" value="{$slide.image}" />    
                                      <input type="submit" name="deleteSlide" value="{l s='Delete' mod='pk_promominicslider'}" id="delete-slide" class="red disabled" />   
                                      <input type="submit" name="editSlide" value="{l s='Update' mod='pk_promominicslider'}" id="update-slide" class="green" />       
                                  </div>    
                            </form>
                        </div>  
                    </li>                 
              {/foreach}    
          </ul>               
      {/foreach}
    </div>  
    </div>
</fieldset>