<!doctype html>
<html lang="{{ config("app.locale") }}">
@include("Site.Mobile.component-header")
<body>
<div id="loader">
	<div class="spinner-border text-primary" role="status"></div>
</div>
@yield("section-header")
@yield("section-main")
@yield("section-footer")
@include("Site.Mobile.component-footer")
</body>
</html>
