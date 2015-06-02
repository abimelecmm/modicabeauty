{if $slides|@count != 0}
    {if $minicSlider.options.front == 1 && $page_name != 'index'}
        <!-- Banner Carousel Slider -->
    {else}
    <div class="banners_carousel container homemodule load-animate">
        <div class="banners_carousel-container">
        <div id="banners_carousel" class="banners_carousel_top theme-default{if $minicSlider.options.thumbnail == 1 and $minicSlider.options.control != 0} controlnav-thumbs{/if}">   
              <ul id="sliderCarousel" class="slides bannersCarousel sliderCarousel_top">
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
    </div>
        {if $slides|count >= $minicSlider.options.startSlide}        
        <script type="text/javascript">
            $(window).load(function() {
              var v_num = {$minicSlider.options.startSlide};
                 $(".sliderCarousel_top").flexisel({
                      pref: "ban-top",
                      visibleItems: v_num,
                      animationSpeed: 500,
                      autoPlay: false,
                      autoPlaySpeed: 3000,            
                      pauseOnHover: true,
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
                              visibleItems: v_num
                          }
                      }
                  });   
            });        
        </script>   
        {/if}
    {/if}
{/if}