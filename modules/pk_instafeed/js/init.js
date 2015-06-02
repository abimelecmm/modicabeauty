$(document).ready(function () {

	if (typeof pk_insta_suffix !== 'undefined') {

		var debug = false;

		var feed = new Instafeed({
		    clientId: pk_insta_api_code,
		    accessToken: pk_insta_at,
		    target: 'instafeed_'+pk_insta_suffix,    
		    get: pk_insta_content_type,	
		    tagName: pk_insta_hashtag,
		    userId: parseInt(pk_insta_userid),
		    sortBy: pk_insta_sortby,
		    limit: parseInt(pk_insta_number),
		    links: Boolean(pk_insta_links),
		    template: pk_insta_template,
		    resolution: "low_resolution"
		});
		feed.run();
	  	
	  	if (pk_insta_carousel == true)
			$("#instafeed_"+pk_insta_suffix).flexisel({
			    pref: "insta"+pk_insta_suffix,
			    visibleItems: parseInt(pk_insta_number_vis),
			    animationSpeed: 500,
			    autoPlay: pk_insta_auto,
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
			            visibleItems: parseInt(pk_insta_number_vis)
			        }
			    }
			});

		if (pk_insta_back == true) 
	    	parallax($(".instagram-feed"));

	    if (debug) {
			console.log("API: "+pk_insta_api_code+"\naccessToken: "+pk_insta_at+"\ntarget: instafeed_"+pk_insta_suffix+"\nget: "+pk_insta_content_type+"\ntagName: "+pk_insta_hashtag+"\nuserId: "+parseInt(pk_insta_userid)+"\nsortBy: "+pk_insta_sortby+"\nlimit: "+parseInt(pk_insta_number)+"\nlinks: "+Boolean(pk_insta_links)+"\ntemplate: "+pk_insta_template+"\nresponse:");
			console.log(feed);
		}
		$(".instagram-feed").find(".flexisel-nav").appendTo(".instagram-feed .carousel-title");
    
	}
});