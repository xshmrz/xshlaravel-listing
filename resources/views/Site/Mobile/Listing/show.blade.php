<?php
    $listing = Listing()->find($id)
?>
@extends("Site.Mobile.layout")
@section("section-header")
	@include("Site.Mobile.Module.header")
@endsection
@section("section-main")
	<div id="appCapsule" class="full-height">
		<div class="mt-2 mb-4">
			<div class="section">
				@include("Site.Mobile.Module.listing-card")
			</div>
		</div>
		@include("Site.Mobile.Module.search")
	</div>
@endsection
@section("section-footer")
	@include("Site.Mobile.Module.footer")
@endsection
