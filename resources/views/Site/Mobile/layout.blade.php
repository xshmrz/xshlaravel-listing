<!doctype html>
<html lang="{{ config("app.locale") }}">
@include("Site.Mobile.component-header")
<body>
<div id="loader">
	<div class="spinner-border text-primary" role="status"></div>
</div>
<div class="appHeader bg-primary text-light">
	<div class="left"></div>
	<div class="pageTitle">{{ config("app.name") }}</div>
	<div class="right"></div>
</div>
@yield("section-main")
<div class="appBottomMenu">
	<a href="javascript:void(0)" class="item"><div class="col"><i class="fe fe-code fs-4"></i><strong>Menu</strong></div></a>
	<a href="javascript:void(0)" class="item"><div class="col"><i class="fe fe-code fs-4"></i><strong>Menu</strong></div></a>
	<a href="javascript:void(0)" class="item actionSheetSearchMdlBtn"><div class="col"><div class="action-button large"><i class="fe fe-search fs-4 text-white"></i></div></div></a>
	<a href="javascript:void(0)" class="item"><div class="col"><i class="fe fe-code fs-4"></i><strong>Menu</strong></div></a>
	<a href="javascript:void(0)" class="item"><div class="col"><i class="fe fe-code fs-4"></i><strong>Menu</strong></div></a>
</div>
@include("Site.Mobile.component-footer")
</body>
</html>
