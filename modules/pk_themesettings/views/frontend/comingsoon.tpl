<script src="{$module_dir}js/jq.js" type="text/javascript"></script>		
<script src="{$module_dir}js/jquery.countdown.min.js" type="text/javascript"></script>
<script src="{$module_dir}js/impromtu.js" type="text/javascript"></script>
<script src="{$module_dir}js/jquery.ez-bg-resize.js" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<div class="container">
	<h1><span>{$name}</span><div></div></h1>
	<div id="message">
		<table>
			<tr>
				<td class="col"><div class="bottomLine"><div class="hLine"></div></div></td>
				<td class="mText">{l s='Our shop is coming soon' mod='pk_themesettings'}</td>
				<td class="col"><div class="bottomLine"><div class="hLine"></div></div></td>
			</tr>
		</table>
	</div>
	<div class="someTimer" data-date="{$cs.year}-{$cs.mon}-{$cs.day} {$cs.hour}:{$cs.min}:00">
		<div class="time_circles">
			<canvas height="125" width="500"></canvas>			
		</div>
	</div>
	<div class="progress"><div class="marker"><div class="point"></div></div></div>
	<div class="send_form">
		<div class="form-indent">
			<h4>{l s='Notify me when its ready' mod='pk_themesettings'}</h4>
			<form action="{$module_dir}maintenance/maintenance.php" method="post" >
				<input type="email" name="cs-email" class="cs-email" value="" placeholder="{l s='Enter your email'}" />
				<input type="hidden" name="path" value="{$module_dir}" />
				<input type="hidden" name="mainURL" value="{$mainURL}" />
				<input type="submit" name="submit" class="cs-submit" value="{l s='Send'}" />  
			</form>
		</div>
	</div>
	<div class="footer-columns">
		<div class="about-us col">
			<h5>{l s='About Us' mod='pk_themesettings'}</h5>
			<div class="content">
				{$cs.mes}
			</div>
		</div>		
		<div class="facebook col">
			<h5>{l s='Our Fans' mod='pk_themesettings'}</h5>
			<div class="content">			
			<ul class="fb_followers">	
				{if $facebooklike != false}{include file="$facebooklike"}{/if}
			</ul>
			</div>
		</div>
		<div class="twitter col">
			<h5>{l s='We are on the map' mod='pk_themesettings'}</h5>
			<div class="content">
				<div id="map-canvas"></div>
			</div>
		</div>
	</div>
</div>
<span style="clear:both;">&nbsp;</span>
<script>
$(document).ready(function() {

	$("body").ezBgResize({
		img     : "{$module_dir}images/comming-soon.jpg", // Relative path example.  You could also use an absolute url (http://...).
		opacity : 1, // Opacity. 1 = 100%.  This is optional.
		center  : true // Boolean (true or false). This is optional. Default is true.
	});
	// Grab the current date
	var currentDate = new Date();
	var mnt = parseInt({$cs.mon});
	var futureDate = new Date({$cs.year}, (mnt), {$cs.day}, {$cs.hour}, {$cs.min});
	$('.someTimer').countdown({ until: futureDate });
	
	var readyDate = Math.round(futureDate/1000);
	var readyCurr = Math.round(currentDate/1000);		
	var length = readyDate-{$cs.date_set};	
	var passed = readyCurr-{$cs.date_set};	
	var percent = Math.round(passed*100/length);
	/*console.log({$cs.year}, {$cs.mon}, {$cs.day}, {$cs.hour}, {$cs.min});
	console.log("readyDate: "+readyDate+" => "+timeConverter(readyDate));
	console.log("setteDate: "+{$cs.date_set}+" => "+timeConverter({$cs.date_set}));
	console.log("curreDate: "+readyCurr+" => "+timeConverter(readyCurr));
	console.log("Length:    "+length+" = "+readyDate+" - "+{$cs.date_set});
	console.log("Passed:    "+passed+" = "+readyCurr+" - "+{$cs.date_set});
	*/

	$(".marker").width(percent+"%");
	$(".point").text(percent+"%");

	function initialize() {
		$.getJSON("//maps.google.com/maps/api/geocode/json?address={$cs.addr}&sensor=false",function(data) {
			//console.log(data.results[0].geometry.location);
			console.log(data);
			var location = data.results[0].geometry.location;			
			var myLatlng = new google.maps.LatLng(location.lat,location.lng);
			var mapOptions = {
			zoom: 9,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

			var marker = new google.maps.Marker({
			  position: myLatlng,
			  map: map
			});
		});		
	}

	/*function timeConverter(UNIX_timestamp){
	 var a = new Date(UNIX_timestamp*1000);
	 var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	     var year = a.getFullYear();
	     var month = months[a.getMonth()];
	     var date = a.getDate();
	     var hour = a.getHours();
	     var min = a.getMinutes();
	     var sec = a.getSeconds();
	     var time = year+' '+date+','+month+' '+hour+':'+min+':'+sec ;
	     return time;
	 }*/

	google.maps.event.addDomListener(window, 'load', initialize);

	{if isset($smarty.get.message)}
		$.prompt('{$smarty.get.message}');
	{/if}
});
</script>