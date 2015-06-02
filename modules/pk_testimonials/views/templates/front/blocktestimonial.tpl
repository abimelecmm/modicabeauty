{if $page_name == "index"}
<!-- Block testimonial module -->
<div id="block_testimonials" class="homemodule{if $hookn != ""} load-animate{/if}{if $displayImage == 0} no-bg{/if}">
	<div class="testimonials-bg"{if ($displayImage == 1)} style="background-image:url('{$testimonial_bg}');"{/if}>
		<div class="page_width">
			<div class="testimonials-wrapper">
			<a href="{$link->getModuleLink('pk_testimonials', 'testimonials')}" class="testimonial-blocktitle lmromandemi">{l s='Testimonials' mod='pk_testimonials'}</a>
		    <ul id="{$hookn}testimonials">
				{if isset($testims)}		  
				{foreach from=$testims item=nr}	
				<li class="testimonial">
					<div class="indent">
						<div class="testimonial-body">
							<div class="item-wrapper">
				    			<div class="testimonial-title{if ($nr.testimonial_title == "“")} nt main_color{/if}">{$nr.testimonial_title}</div>
				    			<div class="testimonial-message">{$nr.testimonial_main_message|truncate:250}</div>	
				    		</div>
			    			<div class="bott"></div>
			    		</div>
			    		<div class="testimonial-avatar">{$nr.avatar}</div>	    			
			    		<div class="testimonial-author lmroman">{$nr.testimonial_submitter_name}, {$nr.date_added|truncate:13:''}</div>
				    </div>
			    </li>
				{/foreach}
				{/if}
		    </ul>
		    </div>
	    </div>
    </div>
</div>
<script>
$(document).ready(function() {
   $("#{$hookn}testimonials").flexisel({
    pref: "testimonials",
    visibleItems: 1,
    animationSpeed: 1000,
    autoPlay: false,
    autoPlaySpeed: 3500,            
    pauseOnHover: true,
    enableResponsiveBreakpoints: false,
    clone : true    
  });
  {if ($hookn != "") && ($displayImage == 1) && ($theme_settings.preset != 4)}
  	parallax($(".testimonials-bg"));
  {/if}
});
</script>
<!-- /Block testimonial module -->
{/if}