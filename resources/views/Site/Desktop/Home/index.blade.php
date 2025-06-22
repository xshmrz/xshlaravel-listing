@extends("Site.Desktop.layout")
@section("section-main")
	{{ Route::currentRouteAction() }}
@endsection
