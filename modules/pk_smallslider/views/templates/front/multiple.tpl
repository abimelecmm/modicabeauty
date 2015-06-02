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
{if $slides.$lang_iso|@count != 0}
<div id="small_slider" class="theme-default block">
    {foreach from=$slides key=iso item=slide}
        {if $iso == $lang_iso and $iso|@count != 0}
            <div id="minicslider_nivo" class="nivoSlider" style="{if $minicSlider.options.width}width:{$minicSlider.options.width}px;{/if}{if $minicSlider.options.height}height:{$minicSlider.options.height}px{/if}{if $minicSlider.options.control != 1}margin-bottom:0;{/if}{if $minicSlider.position == 'top'}display:inline-block;{/if}">
                {foreach from=$slide item=image name=images}
                    {if $image.url != ''}<a href="{$image.url}">{/if}
                        <img src="{$minicSlider.path.images}{$image.image}" class="slider_image" {if $image.alt}alt="{$image.alt}"{/if} />
                    {if $image.url != ''}</a>{/if}
                {/foreach}
            </div>
        {/if}
    {/foreach}
</div>
{/if}
