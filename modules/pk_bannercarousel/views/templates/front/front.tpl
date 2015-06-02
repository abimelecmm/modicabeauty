{if $slides|@count != 0}
    {if $minicSlider.options.front == 1 && $page_name != 'index'}
        <!-- Banner Carousel Slider -->
    {else}
        <div class="banners_carousel-container load-animate homemodule">
        <div id="banners_carousel" class="theme-default{if $minicSlider.options.thumbnail == 1 and $minicSlider.options.control != 0} controlnav-thumbs{/if}">   
              <ul id="sliderCarousel" class="slides bannersCarousel">
                  {foreach from=$slides item=image name=singleimage}
                  <li class="dib">
                      <div class="banners_carousel_wrap">
                      {if $image.url != ''}<a href="{$image.url}" {if $image.target == 1}target="_blank"{/if}>{/if}
                          <img src="{$minicSlider.path.images}{$image.image}" class="slider_image" 
                              alt="{if $image.alt}{$image.alt}{/if}" />
                      {if $image.url != ''}</a>{/if}
                      </div>
                  </li>
                  {/foreach}
              </ul>
        </div>    
        </div>
        {if $slides|count >= $minicSlider.options.startSlide} 
        <script type="text/javascript">
            $(window).load(function() {
              var v_num = {if $minicSlider.options.startSlide != ''}{$minicSlider.options.startSlide}{else}2{/if};
              if ( $('#left_column')[0] )
                  if (v_num > 2)
                    var num = (v_num - 1);
                  else
                    var num = v_num;          
                else
                  var num = v_num;

                 $("#sliderCarousel").flexisel({
                      pref: "ban",
                      visibleItems: num,
                      animationSpeed: {if $minicSlider.options.speed != ''}{$minicSlider.options.speed}{else}500{/if},
                      autoPlay: {if $minicSlider.options.manual != ''}{$minicSlider.options.manual}{else}0{/if},
                      autoPlaySpeed: {if $minicSlider.options.pause != ''}{$minicSlider.options.pause}{else}3000{/if},            
                      pauseOnHover: {if $minicSlider.options.hover != ''}{$minicSlider.options.hover}{else}1{/if},
                      showbuttons : {if $minicSlider.options.buttons != ''}{$minicSlider.options.buttons}{else}0{/if},
                      enableResponsiveBreakpoints: true,
                      clone : true,
                      responsiveBreakpoints: { 
                          portrait: { 
                              changePoint:400,
                              visibleItems: 1
                          }, 
                          landscape: { 
                              changePoint:768,
                              visibleItems: 1
                          },
                          tablet: { 
                              changePoint:991,
                              visibleItems: 2
                          },
                          tablet_land: { 
                              changePoint:1199,
                              visibleItems: num
                          }
                      }
                  });   
            });        
        </script>   
        {/if}
    {/if}
{/if}