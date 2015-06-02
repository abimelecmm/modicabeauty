$(document).ready(function() {

  if (typeof(productsNum_s) == 'undefined')
        productsNum_s = 5;
  
      $("#products-single").flexisel({
            pref: "sngl",
            visibleItems: productsNum_s,
            animationSpeed: 500,
            autoPlay: sc_autoplay_s,
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
                    visibleItems: 2
                },
                tablet: { 
                    changePoint:991,
                    visibleItems: 3
                },
                tablet_land: { 
                    changePoint:1199,
                    visibleItems: productsNum_s
                }
            }
      });
    $("#productsCarousel_single").find(".flexisel-nav").appendTo("#productsCarousel_single .carousel-title");
});