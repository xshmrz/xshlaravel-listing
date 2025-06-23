@extends("Site.Mobile.layout")
@section("section-header")
	@include("Site.Mobile.Module.header")
@endsection
@section("section-main")
	<div id="appCapsule" class="full-height">
		<div class="section full mt-2">
			<div class="wide-block pt-2 pb-2 text-center">
				{{ Route::currentRouteAction() }}
			</div>
		</div>
		@include("Site.Mobile.Module.search")
	</div>
@endsection
@section("section-footer")
	@include("Site.Mobile.Module.footer")
@endsection
