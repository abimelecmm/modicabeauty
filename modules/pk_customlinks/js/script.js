$(".add_to_compare").click(function(){

	current_num = $(".compare-form .total-compare-val").text();
	state = $(this).parent().hasClass('chkd');
	pid = $(this).data("id-product");
	container = $("#pk_customlinks .pk_compare");

	if ((current_num < comparator_max_item) || (state == false)) {

		empty_li = $(container).find(".no-products");

		if ($(empty_li)[0])
			$(empty_li).remove();

		prod_link = $(this).closest(".product-container").find(".product_img_link").attr("href");
		prod_image = $(this).closest(".product-container").find(".img-responsive").attr("src");
		prod_name = $(this).closest(".right-block").find(".product-name").text();
		prod_price = $(this).closest(".right-block").find(".price").text();

		html = '<li class="clearfix" data-id-product="'+pid+'"><a href="'+prod_link+'" class="content_img"><img src="'+prod_image+'" /></a><div class="text_desc"><span class="pName"><a href="'+prod_link+'">'+prod_name+'</a></span><div class="price">'+prod_price+'</div></div></li>';
		
		$(container).find('ul').append(html);
		$(container).find(".dd_container").css({"height": "auto"});

	}

	if (state == true) {

		$(container).find("[data-id-product='" + pid + "']").remove();

	}

});

$('document').ready(function(){
	$('a[rel^=ajax_id_favoriteproduct_]').click(function()
	{
		var idFavoriteProduct =  $(this).attr('rel').replace('ajax_id_favoriteproduct_', '');
		var parent = $(this).parent().parent();

		$.ajax({
			url: ajaxPath,
			type: "POST",
			data: {
				'id_product': idFavoriteProduct,
				'ajax': true
			},
			success: function(result)
			{
				if (result == '0')
				{
					parent.fadeOut("normal", function()
					{
						parent.remove();
						var num = parseInt($(".favQty").text());
						$(".favQty").text(num-1);
						var buttonID = $(this).data("buttonID") // get data
						var favprodID = $(this).data("favprodID") // get data
						if (buttonID == favprodID) $(".product_like").removeClass("active");
						if ($(".favoritelist li").length == 0) {
                        	$(".favoritelist ul").append('<li class="no-products">"No favorite products have been determined just yet.</li>');
                        }
					});
				}
 		 	}
		});
	});
});