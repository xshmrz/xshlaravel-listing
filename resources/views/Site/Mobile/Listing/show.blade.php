<?php
    $listing = Listing()->find($id)
?>
@extends("Site.Mobile.layout")
@section("section-main")
	<div id="appCapsule" class="full-height">
		<div class="carousel-full splide">
			<div class="splide__track">
				<ul class="splide__list">
                    <li class="splide__slide">
                        <img src="assets/demo/bg-{{ rand(1,60) }}.jpg" alt="" class="imaged w-100 square">
                    </li>
					<li class="splide__slide">
                        <img src="assets/demo/bg-{{ rand(1,60) }}.jpg" alt="" class="imaged w-100 square">
                    </li>
					<li class="splide__slide">
                        <img src="{{ placeholder(600,300) }}" alt="" class="imaged w-100 square">
                    </li>
				</ul>
			</div>
		</div>
		<div class="section full">
			<div class="wide-block pt-2 pb-2 product-detail-header border-top-0">

				<h1 class="title">{{ $listing->name }}</h1>
				<div class="text">{{ $listing->location->parent_name }} / {{ $listing->location->name }}</div>
			</div>
		</div>
		<!-- Review -->
		<div class="section full">
			<div class="section-title">Reviews (2)</div>
			<div class="wide-block pt-2 pb-2">
				<!-- comment block -->
				<div class="comment-block">
					<!--item -->
					<div class="item">
						<div class="avatar">
							<img src="assets/site-pwa/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w32 rounded">
						</div>
						<div class="in">
							<div class="comment-header">
								<h4 class="title">Diego Morata</h4>
								<span class="time">just now</span>
							</div>
							<div class="rate-block mb-1 mt-05">
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star"></ion-icon>
							</div>
							<div class="text">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit.
							</div>
							<div class="comment-footer">
                                <a href="#" class="comment-button">
                                    <ion-icon name="happy-outline"></ion-icon>
                                    Helpful (523)
                                </a>
								<a href="#" class="comment-button">
                                    <ion-icon name="flag-outline"></ion-icon>
                                    Report
                                </a>
							</div>
						</div>
					</div>
					<!-- * item -->
					<!--item -->
					<div class="item">
						<div class="avatar">
							<img src="assets/site-pwa/img/sample/avatar/avatar4.jpg" alt="avatar" class="imaged w32 rounded">
						</div>
						<div class="in">
							<div class="comment-header">
								<h4 class="title">Carmelita Marsham</h4>
								<span class="time">Sep 23, 2020</span>
							</div>
							<div class="rate-block mb-1 mt-05">
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
								<ion-icon name="star" class="active"></ion-icon>
							</div>
							<div class="text">
								Vivamus lobortis, orci et commodo pulvinar, eros nibh volutpat ipsum, in rhoncus risus
								dolor
								sed ipsum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec nisi
								odio,
								dapibus in felis vel, lobortis iaculis quam.
							</div>
							<div class="comment-footer">
                                <a href="#" class="comment-button">
                                    <ion-icon name="happy-outline"></ion-icon>
                                    Helpful (43)
                                </a>
								<a href="#" class="comment-button">
                                    <ion-icon name="flag-outline"></ion-icon>
                                    Report
                                </a>
							</div>
						</div>
					</div>
					<!-- * item -->
				</div>
				<!-- * comment block -->
				<div class="divider mt-3 mb-2"></div>
				<a href="#" class="btn btn-block btn-primary">Add a review</a>
			</div>
		</div>

		@include("Site.Mobile.Module.search")
	</div>
@endsection
