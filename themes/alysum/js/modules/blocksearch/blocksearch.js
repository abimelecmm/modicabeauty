$(document).ready(function() {   
	$('#search_query_top').on({
        focus: function() {
            $("#search_block_top").width(300);
            $("#search_query_top").css({ "background-color": $("#white_bg").css("background-color") });
        },
        blur: function() {
           $("#search_block_top").removeAttr("style");
        }
    });

		$("#search_query_top").autocomplete(
			search_url,
			{
				minChars: 3,
				max: 10,
				width: 300,
				selectFirst: false,
				scroll: false,
				dataType: "json",
				formatItem: function(data, i, max, value, term) {
					return value;
				},
				parse: function(data) {
					var mytab = new Array();
					var allIDs = new Array();
					var allCrw = new Array();
					for (var i = 0; i < data.length; i++) {
						allIDs[i] = data[i].id_product;
						allCrw[i] = data[i].crewrite;
					}
					
					var prodImgs = getProducts(allIDs, allCrw);
					var obj = $.parseJSON(prodImgs);
						
					for (var i = 0; i < data.length; i++) {
						mytab[mytab.length] = { data: data[i], value:  ' <img src="'+ obj[i].link + '" /><span class="prname">'  + data[i].pname + ' </span><span class="prPrice dib price lmroman">'+ obj[i].price + '</span>'};
					}
					return mytab;
				},
				extraParams: {
					ajaxSearch: 1,
					id_lang: id_lang
				}
			}
		)
		.result(function(event, data, formatted) {
			$('#search_query_' + blocksearch_type).val(data.pname);
			document.location.href = data.product_link;
		});

		function getProducts(pID, crewrite) {
			var tmp = 0;
			$.ajax({
			    type: 'POST',
			    async:false,
			    url: baseDir + 'modules/pk_themesettings/ajax.php?spID='+pID+'&crewrite='+crewrite+'&imgName=medium_default',
			    success: function(result){
			      if (result == '0') {
			        console.log("no data")
			      } else {
					tmp = result;					
			      }
			    }
			});
			return tmp;
		}

});	            