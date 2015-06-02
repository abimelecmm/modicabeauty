$(document).ready(function() {  
    $(".registered #productsCarousel_single .product_like a").live('click', function() {
    var fav_url = favorite_products_url_add
    if ($(this).hasClass("remfav"))
        var fav_url = favorite_products_url_remove
    var fav_pid = $(this).closest('.ajax_block_product').data("productid"); // get product id
    
    $.ajax({
        url: fav_url,
        type: "POST",
        data: {
          "id_product": fav_pid
        },
        success: function(result){                
          if (result == '0')
          {
            var num = parseInt($(".favQty").text());
            if (fav_url == favorite_products_url_add) {
                $("#productsCarousel_single").find("[data-productid='" + fav_pid + "'] .product_like").addClass("active");
                var pName = $(".p"+fav_pid).attr("title");
                $(".favQty").text(num+1);                
                if (!!$.prototype.fancybox)
                    $.fancybox.open([
                        {
                            type: 'inline',
                            autoScale: true,
                            minHeight: 30,
                            content: '<p class="fancybox-error"><strong>' + pName+'</strong> ' + favadd + '</p>'
                        }
                    ], {
                        padding: 0
                    });
                else
                    alert("Product has been added");                
            } else if (fav_url == favorite_products_url_remove) {
                $("#productsCarousel_single").find("[data-productid='" + fav_pid + "'] .product_like").removeClass("active");
                $(".favQty").text(num-1);
                var pName = $(".p"+fav_pid).attr("title");
                if (!!$.prototype.fancybox)
                    $.fancybox.open([
                        {
                            type: 'inline',
                            autoScale: true,
                            minHeight: 30,
                            content: '<p class="fancybox-error"><strong>' + pName+'</strong> '+favrem + '</p>'
                        }
                    ], {
                        padding: 0
                    });
                else
                    alert("Product has been removed");
            }                
          }
        }
    });
  });
});