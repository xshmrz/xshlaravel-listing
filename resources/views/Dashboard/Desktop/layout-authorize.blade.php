<!doctype html>
<html lang="{{ config("app.locale") }}">
@include("Dashboard.Desktop.component-header")
<body>
<div id="page-container" class="main-content-boxed">
	<main id="main-container">
		<div class="bg-body-dark">
			<div class="hero-static content content-full px-1">
				<div class="row mx-0 justify-content-center">
					<div class="col-lg-8 col-xl-6">
						<div class="py-4 text-center">
							<a class="link-fx fw-bold text-dark" href="{{ route("dashboard.index") }}">{{ config("app.name") }}</a>
							<h1 class="h3 fw-bold mt-4 mb-1">{{ trans("app.Yönetim Paneli") }}</h1>
							<h2 class="fs-5 lh-base fw-normal text-muted mb-0">{{ fake()->sentence() }}</h2>
						</div>
						@yield("section-main")
					</div>
				</div>
			</div>
		</div>
	</main>
</div>
@include("Dashboard.Desktop.component-footer")
</body>
</html>
