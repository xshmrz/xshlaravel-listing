<a href="{{ route("site.listing.show",$listing->id) }}" class="card">
	<img src="assets/demo/bg-{{ rand(1,60) }}.jpg" class="card-img-top" alt="image" style="height: 200px; object-fit: cover">
	<div class="position-absolute p-2">
		<div><span class="badge badge-success rounded">08:30 - 23:00</span></div>
		<div><span class="badge badge-success rounded">Pzt, Sal, Çar, Per, Cum, Cmt</span></div>
	</div>
	<div class="card-body d-flex justify-content-between align-items-center">
		<div>
			<h6 class="card-subtitle">{{ $listing->location->parent_name }} / {{ $listing->location->name }}</h6>
			<h5 class="card-title fs-6 mb-0">{{ $listing->name }}</h5>
		</div>
		<div class="d-flex flex-row align-items-center">
			<div class="me-1 lh-sm"></div>
			<div class="bg-primary p-1 rounded text-center" style="min-width: 40px;">{{ number_format(fake()->randomFloat(1,3,5),1) }}</div>
		</div>
	</div>
</a>
