<!-- begin twitter widget -->
<div id="twitter-feed" class="block">
  <div class="block_content">
    <h4 class="dropdown-cntrl dd_el_mobile">{l s='Latest Tweets' mod='pk_twitterwidget'}</h4>
    <div class="dropdown-content dd_container_mobile">
      <div class="tweet"></div>
    </div>  
  </div>
</div>
<script>
/*jQuery(function($){ 
    var twitterSlider = function(){  
        $(".tweet_list").flexisel({
      pref: "twt",
          visibleItems: 1,
          animationSpeed: 1000,
          autoPlay: true,
          autoPlaySpeed: 4000,            
          pauseOnHover: true,
          enableResponsiveBreakpoints: true,
          responsiveBreakpoints: { 
              portrait: { 
                  changePoint:480,
                  visibleItems: 1
              }, 
              landscape: { 
                  changePoint:640,
                  visibleItems: 1
              },
              tablet: { 
                  changePoint:768,
                  visibleItems: 1
              }
          }
      });
            
    }; */     
    $('.tweet').tweet({
        join_text: "auto",
          username: '{if isset($username) && $username != ""}{$username}{else}PromokitTest{/if}',
          avatar_size: 0,
          count: {if isset($count) && $count != ""}{$count}{else}2{/if},
          auto_join_text_default: "",
          auto_join_text_ed: "",
          auto_join_text_ing: "",
          auto_join_text_reply: "",
          auto_join_text_url: "",
          loading_text: "loading tweets...",
          modpath: '{if isset($tw_this_path) && $tw_this_path != ""}{$tw_this_path}{else}/modules/twitterwidget/ajax.php{/if}',
        // loaded: twitterSlider
    });     
//});
</script>
<!-- end twitter widget -->