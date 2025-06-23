<?php
    $listingData = Listing()->where([listing_category_id => $listing_category_id, location_id => $location_id])->get()
?>
@extends("Site.Mobile.layout")
@section("section-header")
	@include("Site.Mobile.Module.header")
@endsection
@section("section-main")
	<div id="appCapsule" class="full-height">
		<div class="mt-2 mb-4">
			@foreach($listingData as $listing)
				<div class="section mb-2">
					@include("Site.Mobile.Module.listing-card")
				</div>

			@endforeach
		</div>
		@include("Site.Mobile.Module.search")
	</div>
@endsection
@section("section-footer")
	@include("Site.Mobile.Module.footer")
@endsection
