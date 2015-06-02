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



<div id="small_slider" class="theme-default{if $minicSlider.options.thumbnail == 1 and $minicSlider.options.control != 0} controlnav-thumbs{/if}">   
	<div id="minicslider_adv" class="minictopbg">
		<div class="minicbottombg">
			<div id="minicslider_nivo" class="nivoSlider" style="{if $minicSlider.options.width}width:{$minicSlider.options.width}px;{/if}{if $minicSlider.options.height}height:{$minicSlider.options.height}px{/if}{if $minicSlider.options.control != 1}margin-bottom:0;{/if}{if $minicSlider.position == 'top'}display:inline-block;{/if}">
                {foreach from=$slides.$defLang item=image name=singleimage}
                    {if $image.url != ''}<a href="{$image.url}" {if $image.target == 1}target="_blank"{/if}>{/if}
                        <img src="{$minicSlider.path.images}{$image.image}" class="slider_image" {if $image.alt}alt="{$image.alt}"{/if} {if $minicSlider.options.thumbnail == 1}data-thumb="{$minicSlider.path.thumbs}{$image.image}"{/if} />
                    {if $image.url != ''}</a>{/if}
                {/foreach}
			</div>
		</div>
	</div>
</div> 