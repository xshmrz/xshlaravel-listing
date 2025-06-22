@extends("Site.Desktop.layout-authorize")
@section("section-main")
	{{ Route::currentRouteAction() }}
@endsection
