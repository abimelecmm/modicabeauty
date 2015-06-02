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
    <legend>{l s='Slides' mod='pk_sequenceminicslider'}</legend>
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
        <span class="order">{l s="Order" mod='pk_sequenceminicslider'}</span>
        <span class="title">{l s="Title" mod='pk_sequenceminicslider'}</span>
        <span class="active">{l s="Active" mod='pk_sequenceminicslider'}</span>
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
                            <span class="title">{$slide.title|strip_tags}</span>
                            <span class="{if $slide.active == 1}active{else}deactivated{/if}"></span>
                            <span class="arrow"></span>
                        </div>
                        <div class="slide_body">
                              <form id="{$iso}_{$slide.id_order}" method="post" action="{$slider.postAction}" enctype="multipart/form-data">
                                  <div class="image_holder">
                                    <div style="overflow:hidden; width:100%">
                                      <h4>{l s='Main Image' mod='pk_sequenceminicslider'}</h4>                 
                                        <img src="{$module_dir}uploads/thumbs/{$slide.image}" />
                                        <div class="file_input">
                                          <input type="file" name="newImage" class="file"/>
                                          <div>
                  										      <span></span>
                  										      <input type="submit" value="{l s='Change image' mod='pk_sequenceminicslider'}"/>
                  									      </div>
                                        </div>
                                      </div>           
                                      {if $slider.subImages[$iso][$slide.id_slide][0] != ""}
                                        <div style="overflow:hidden; width:100%">
                                          {foreach from=$slider.subImages[$iso][$slide.id_slide] item=imageName key=k}
                                          <h4>{l s='Sub Image' mod='pk_sequenceminicslider'} 0{$k+1}</h4>                 
                                              <div class="admin_img">
                                                <img src="{$module_dir}uploads/thumbs/{$imageName}" />
                                              </div>
                                              <div class="clear remove_subimage">
                                                <label>{l s='Remove Image' mod='pk_sequenceminicslider'}: </label>
                                                <input type="checkbox" name="remove_subimage_{$slide.id_slide}_{$k}" value="1" />
                                              </div>
                                              <div class="clear before">
                                                <h4>{l s='Position before Showing' mod='pk_sequenceminicslider'}:</h4>
                                                <div class="clmn col01">
                                                  <label>{l s='Top' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="before-top_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['before']['top']}" title="{l s='This is top position in % of image before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col02">
                                                  <label>{l s='Left' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="before-left_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['before']['left']}" title="{l s='This is left position in % of image before it will be shown' mod='pk_sequenceminicslider'}" />
                                                </div>
                                                <div class="clmn col03">
                                                  <label>{l s='Duration' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="before-duration_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['before']['duration']}" title="{l s='This is a time in seconds what will take animation before this image will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col04">
                                                  <label>{l s='Rotation' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="before-rotation_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['before']['rotation']}" title="{l s='This is a rotation angle in degrees of image before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col05">
                                                  <label>{l s='Opacity' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="before-opacity_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['before']['opacity']}" title="{l s='This is a opacity in % of image before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                              </div>
                                              <div class="clear on">
                                                <h4>{l s='Position on Showing' mod='pk_sequenceminicslider'}:</h4>
                                                <div class="clmn col01">
                                                  <label>{l s='Top' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="on-top_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['in']['top']}" title="{l s='This is top position in % of image when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col02">
                                                  <label>{l s='Left' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="on-left_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['in']['left']}" title="{l s='This is left position in % of image when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col03">
                                                  <label>{l s='Duration' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="on-duration_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['in']['duration']}" title="{l s='This is a time in seconds what will take animation when this image will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col04">
                                                  <label>{l s='Rotation' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="on-rotation_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['in']['rotation']}" title="{l s='This is a rotation angle in degrees of image when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col05">
                                                  <label>{l s='Opacity' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="on-opacity_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['in']['opacity']}" title="{l s='This is a opacity in % of image when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                              </div>
                                              <div class="clear after">
                                                <h4>{l s='Position after Showing' mod='pk_sequenceminicslider'}:</h4>
                                                <div class="clmn col01">
                                                  <label>{l s='Top' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="after-top_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['after']['top']}" title="{l s='This is top position in % of image after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col02">
                                                  <label>{l s='Left' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="after-left_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['after']['left']}" title="{l s='This is left position in % of image after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col03">
                                                  <label>{l s='Duration' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="after-duration_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['after']['duration']}" title="{l s='This is a time in seconds what will take animation after this image will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col04">
                                                  <label>{l s='Rotation' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="after-rotation_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['after']['rotation']}" title="{l s='This is a rotation angle in degrees of image after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                                <div class="clmn col05">
                                                  <label>{l s='Opacity' mod='pk_sequenceminicslider'}: </label>
                                                  <input type="text" name="after-opacity_{$iso}_{$slide.id_slide}_{$k}" class="tooltip2" size="10" value="{$slider.slideEffects[$iso][$slide.id_slide][$k]['after']['opacity']}" title="{l s='This is a opacity in percentage of image after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                                </div>
                                              </div>
                                          {/foreach}
                                            <div class="file_input">
                                              <input type="file" name="subimage" class="file"/>
                                              <div>
                                                <span></span>
                                                <input type="submit" value="{l s='Add image' mod='pk_sequenceminicslider'}"/>
                                              </div>
                                            </div>
                                        </div>
                                      {else}
                                        <div class="file_input">
                                          <input type="file" name="subimage" class="file"/>
                                          <div>
                                            <span></span>
                                            <input type="submit" value="{l s='Add image' mod='pk_sequenceminicslider'}"/>
                                          </div>
                                        </div>
                                      {/if}                                      
                                  </div>
                                  <div class="form_cont">
                                      <div class="title input">
                                          <label>{l s='Title' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="title" class="tooltip2" size="41" value="{$slide.title}" title="{l s='This will be the title on the slide.' mod='pk_sequenceminicslider'}" /> 
                                      </div>
                                      <div class="url input">
                                      	<label>{l s='Url' mod='pk_sequenceminicslider'}: </label>
                                      	<input type="text" name="url" class="tooltip2" size="41" value="{$slide.url}" title="{l s='ex. http://myshop.com/promotions' mod='pk_sequenceminicslider'}" />           
                                      </div>
                                      <div class="target">
                                          <label>{l s='Blank target' mod='pk_sequenceminicslider'}: </label>
                                          <input type="checkbox" name="target" class="tooltip2" value="1" {if $slide.target == 1}checked="true"{/if} title="{l s='Check this if you want to open the link in new window.' mod='pk_sequenceminicslider'}" />
                                      </div>
                                      <div class="alt input">
                                          <label>{l s='Image alt' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="alt" class="tooltip2" size="41" value="{$slide.alt}" title="{l s='The image alt, alternate text for the image' mod='pk_sequenceminicslider'}" />
                                      </div>
                                      <div class="caption"> 
                                      	<label>{l s='Caption' mod='pk_sequenceminicslider'}: </label>
                                      	<textarea type="text" name="caption" class="tooltip2" cols="40" rows="6" title="{l s='Be carefull, too long text isnt good and HTML is not allowed.' mod='pk_sequenceminicslider'}" >{$slide.caption}</textarea>                      
                                      </div>
                                      <div style="overflow:hidden">
                                        <h4>{l s='Text Section Width' mod='pk_sequenceminicslider'}</h4> 
                                        <div class="text-section_width three_column"> 
                                          <input type="text" name="text_section_width_{$iso}_{$slide.id_slide}" class="tooltip2" size="41" value="{$slider.text_width[$iso][$slide.id_slide]}" title="{l s='The width of section where slide test placed' mod='pk_sequenceminicslider'}" />
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clear before">
                                        <h4>{l s='Text Position before Showing' mod='pk_sequenceminicslider'}:</h4>
                                        <div class="clmn col01">
                                          <label>{l s='Top' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-before-top_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['before']['top']}" title="{l s='This is top position in % of text before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col02">
                                          <label>{l s='Left' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-before-left_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['before']['left']}" title="{l s='This is left position in % of text before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col03">
                                          <label>{l s='Duration' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-before-duration_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['before']['duration']}" title="{l s='This is a time in seconds what will take animation before this text will be shown' mod='pk_sequenceminicslider'}" />                                         
                                        </div>
                                        <div class="clmn col04">
                                          <label>{l s='Rotation' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-before-rotation_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['before']['rotation']}" title="{l s='This is a rotation angle in degrees of text before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col05">
                                          <label>{l s='Opacity' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-before-opacity_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['before']['opacity']}" title="{l s='This is a opacity in % of text before it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                      </div>
                                      <div class="clear on">
                                        <h4>{l s='Text Position on Showing' mod='pk_sequenceminicslider'}:</h4>
                                        <div class="clmn col01">
                                          <label>{l s='Top' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-on-top_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['in']['top']}" title="{l s='This is top position of text in % when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col02">
                                          <label>{l s='Left' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-on-left_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['in']['left']}" title="{l s='This is left position of text in % when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col03">
                                          <label>{l s='Duration' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-on-duration_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['in']['duration']}" title="{l s='This is a time in seconds what will take animation when this text will be shown' mod='pk_sequenceminicslider'}" />                                         
                                        </div>
                                        <div class="clmn col04">
                                          <label>{l s='Rotation' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-on-rotation_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['in']['rotation']}" title="{l s='This is a rotation angle in degrees of text when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col05">
                                          <label>{l s='Opacity' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-on-opacity_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['in']['opacity']}" title="{l s='This is a opacity in % of text when it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                      </div>
                                      <div class="clear after">
                                        <h4>{l s='Text Position after Showing' mod='pk_sequenceminicslider'}:</h4>
                                        <div class="clmn col01">
                                          <label>{l s='Top' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-after-top_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['after']['top']}" title="{l s='This is top position of text in % after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col02">
                                          <label>{l s='Left' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-after-left_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['after']['left']}" title="{l s='This is left position of text after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col03">
                                          <label>{l s='Duration' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-after-duration_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['after']['duration']}" title="{l s='This is a time in seconds what will take animation after this text will be shown' mod='pk_sequenceminicslider'}" />                                         
                                        </div>
                                        <div class="clmn col04">
                                          <label>{l s='Rotation' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-after-rotation_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['after']['rotation']}" title="{l s='This is a rotation angle in degrees of text after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                        <div class="clmn col05">
                                          <label>{l s='Opacity' mod='pk_sequenceminicslider'}: </label>
                                          <input type="text" name="text-after-opacity_{$iso}_{$slide.id_slide}" class="tooltip2" size="10" value="{$slider.slidesText[$iso][$slide.id_slide]['after']['opacity']}" title="{l s='This is a opacity in % of text after it will be shown' mod='pk_sequenceminicslider'}" /> 
                                        </div>
                                      </div>
                                    </div>
                                      <div class="switch">
                                          <label>{l s='Active' mod='pk_sequenceminicslider'}: </label>
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
                                      <input type="submit" name="deleteSlide" value="{l s='Delete' mod='pk_sequenceminicslider'}" id="delete-slide" class="red disabled" />   
                                      <input type="submit" name="editSlide" value="{l s='Update' mod='pk_sequenceminicslider'}" id="update-slide" class="green" /><br/><br/>
                                      {l s='You can save all values to file'}<br/> {l s='and restore if you will lose them.' mod='pk_sequenceminicslider'}<br/><br/>
                                      <input type="submit" name="saveNumbers" value="{l s='Save Values' mod='pk_sequenceminicslider'}" id="update-slide" class="green" />
                                      <input type="submit" name="loadNumbers" value="{l s='Restore' mod='pk_sequenceminicslider'}" id="update-slide" class="green" />       
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