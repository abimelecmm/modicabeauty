		{if !$content_only}
				</div>
				{if ($page_name != "index")}
					{if ($page_name != "products-comparison") && ($page_name != "product")}
						<div id="left_column" class="column">
							{$HOOK_LEFT_COLUMN}
						</div>
					{/if}
				{else}
					{if ($theme_settings.homepage_column == '1')}
						<div id="left_column" class="column">
							{$HOOK_LEFT_COLUMN}
						</div>
					{/if}
				{/if}
			</div>
		</div><!-- id="white_bg" -->
		<div class="hook-section section-wide-top wide-section">
			{hook h='wide_top'}
		</div>
		<div class="hook-section section-narrow-top">
			<div class="page_width">
				{hook h='narrow_top'}
			</div>
		</div>
		<div class="hook-section section-wide-middle wide-section">
			{hook h='wide_middle'}
		</div>
		<div class="hook-section section-narrow-middle">
			<div class="page_width">
				{hook h='narrow_middle'}
			</div>
		</div>
		<div class="hook-section section-wide-bottom wide-section">
			{hook h='wide_bottom'}
		</div>
		<div class="hook-section section-narrow-bottom">
			<div class="page_width">
				{hook h='narrow_bottom'}
			</div>
		</div>
		{hook h='footer_twitter'}
		<!-- Footer -->
		{if ($theme_settings.show_map_bottom == 1)}
		<div class="wide-bottom-map">
			<div id="bottom-map"></div>
			<div class="qc-container">
				<div class="page_width">
					<div class="quick-contact sec_bg">
						<div class="qc-indent">
							<h3 class="lmromandemi">{l s='Quick contact'}</h3>
							<form action="{$request_uri|escape:'html':'UTF-8'}" method="post" class="contact-form-box" enctype="multipart/form-data">
		                        <input class="" type="text" name="name" value="" placeholder="{l s='Your name'}" />
		                        <input class="" type="text" name="from" value="" placeholder="{l s='Email address'}" />
		                        <textarea class="form-control" name="message" onfocus="javascript:if(this.value=='{l s='Your message'}')this.value='';" onblur="javascript:if(this.value=='')this.value='{l s='Your message'}';" >{l s='Your message'}</textarea>
		                        <input type="submit" name="submitMessage" value="{l s='Send'}" class="button main_bg_hvr" />
							</form>
						</div>
					</div>
				</div>
			</div>
			<script>
			function initialize() {
			  var myLatlng = new google.maps.LatLng({$theme_settings.location_lat},{$theme_settings.location_lng});
			  var mapOptions = {
			    zoom: 7,
			    scrollwheel: false,
			    center: myLatlng,
			    mapTypeId: google.maps.MapTypeId.ROADMAP
			  }
			  var map = new google.maps.Map(document.getElementById('bottom-map'), mapOptions);

			  var marker = new google.maps.Marker({
			      position: myLatlng,
			      map: map
			  });
			}
			google.maps.event.addDomListener(window, 'load', initialize);
			</script>
		</div>
		{/if}
		<div id="footer" class="clearfix{if ($theme_settings.show_map_bottom == 1)} topmap{/if}">
			<div class="footer-top">
				{hook h="footer_top"}				
			</div>
			{if isset($theme_settings) && ($theme_settings.footer == 1)}
			<div class="page_width">
				{$HOOK_FOOTER}
				<div class="clearfix"></div>
			</div>
			{/if}
			{if isset($theme_settings) && $theme_settings.footer_bottom == 1}
			<div class="footer_bottom">
				<div class="footer_bottom-top-border">							
					<div class="page_width">
						<div class="footer_text dib">{$theme_settings.footer_text}</div><div class="footer_bottom_hook dib">{hook h='footer_bottom'}</div>
					</div>
				</div>	
			</div>				
			{/if}
		</div>
	</div>
</div>
{/if}
{include file="$tpl_dir./global.tpl"}  
</body>
</html>
