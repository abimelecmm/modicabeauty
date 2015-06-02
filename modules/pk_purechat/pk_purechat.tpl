{literal}
<!--Start of PureChat Live Chat Script -->
<script type='text/javascript'>
		(function () { var done = false;
		var script = document.createElement('script');
		script.async = true;
		script.type = 'text/javascript';
		script.src = 'https://app.purechat.com/VisitorWidget/WidgetScript';
		document.getElementsByTagName('HEAD').item(0).appendChild(script);
		script.onreadystatechange = script.onload = function (e) {
			if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
				var w = new PCWidget({ c: '{/literal}{$purechat}{literal}', f: true });
				done = true;
			}
		};
	})();
</script>
<!--End of PureChat Live Chat Script -->
{/literal}
