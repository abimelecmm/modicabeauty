$(document).ready(function () {

	if ($.cookie("pk_popup") != "true") {

		$.fancybox.open($('.pk_popup'), {
	        'padding' : 0,
	        'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'easingIn'      : 'none',
			'easingOut'     : 'none'
	    });

		$(".pk_popup").closest(".fancybox-overlay").addClass('pkpopup');
		$(".fancybox-skin").append("<div class='fancybox-close-overlay'></div>");

		$(".send-reqest").click(function(){
			var email = $("#newsletter-input-popup").val();
			$.ajax({
				type: "POST",
				headers: { "cache-control": "no-cache" },
				async: false,
				url: pk_popup_path,
				data: "name=marek&email="+email,
				success: function(data) {
					if (data)
						$(".send-response").text(data);
				}
			});
		});

	}

	$(".fancybox-close-overlay").click(function(e){
		$.cookie("pk_popup", "true");
		parent.$.fancybox.close();
	});

});