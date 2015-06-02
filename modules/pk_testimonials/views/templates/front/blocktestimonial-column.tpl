{if $page_name == "index"}
<!-- Block testimonial module -->
<div id="block_testimonials_column">
	<div class="testimonials-wrapper">
		<a href="{$link->getModuleLink('pk_testimonials', 'testimonials')}" class="testimonial-blocktitle lmromandemi">{l s='Testimonials' mod='pk_testimonials'}</a>
	    <ul id="{$hookn}testimonials">
			{if isset($testims)}		  
			{foreach from=$testims item=nr}	
			<li class="testimonial">
				<div class="indent">
					<div class="testimonial-body">
						<div class="item-wrapper">
			    			<div class="testimonial-title">{$nr.testimonial_title}</div>
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
<!-- /Block testimonial module -->
<script>
$(document).ready(function() {
   $("#{$hookn}testimonials").flexisel({
    pref: "testimonials",
    visibleItems: 1,
    animationSpeed: 1500,
    autoPlay: true,
    autoPlaySpeed: 3500,            
    pauseOnHover: true,
    enableResponsiveBreakpoints: false,
    clone : true    
  });
});
</script>
{/if}