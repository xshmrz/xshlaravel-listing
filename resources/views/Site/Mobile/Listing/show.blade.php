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
			<div class="section mt-2">
				<div class="card">
					<div class="card-header">{{ trans("app.Çalışma Günleri & Saatleri") }}</div>
					<ul class="listview flush transparent simple-listview">
						<li>Pzt, Sal, Çar, Per, Cum, Cmt, Paz</li>
						<li>09:00 - 23:00</li>
					</ul>
				</div>
			</div>
			<div class="section mt-2">
				<div class="card">
					<div class="card-header">{{ trans("app.Personel") }}</div>
					<ul class="listview flush transparent image-listview">
						@foreach(Worker()->where([listing_id => $listing->id])->get() as $worker)
							<li>
		                        <a href="#" class="item">
		                           <img src="{{ placeholder(100,100) }}" alt="image" class="image">
		                            <div class="in">
		                                <div>
			                                <div>{{ $worker->first_name }} {{ $worker->last_name }}</div>
			                                <div class="text-muted">{{ fake()->words(rand(2,3),true) }}</div>
		                                </div>
		                                <span class="badge badge-secondary rounded">{{ number_format(fake()->randomFloat(1,3,5),1) }}</span>
		                            </div>
		                        </a>
		                    </li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		@include("Site.Mobile.Module.search")
	</div>
@endsection
@section("section-footer")
	<div class="appBottomMenu">
		<a href="javascript:void(0)" class="item w-75"><div class="col"><button type="button" class="btn btn-success w-100">{{ trans("app.Randevu Al") }}</button></div></a>
		<a href="javascript:void(0)" class="item w-25"><div class="col"><i class="fe fe-map-pin fs-4"></i><strong>Menu</strong></div></a>
	</div>

@endsection
