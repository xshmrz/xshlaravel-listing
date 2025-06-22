@extends("Dashboard.Desktop.layout-authorize")
@section("section-main")
	{{ form_builder()::open()->addClass(FrmLogin) }}
	<div class="block block-rounded">
		<div class="block-header block-header-default border-bottom">
			<h3 class="block-title text-center">{{ trans("app.Giriş Formu") }}</h3>
		</div>
		<div class="block-content p-3">
			<div class="row g-2">
				{{ form_builder()::input(email)->placeholder(trans("app.E-Posta Adresi"))->groupClass("col-md-12") }}
				{{ form_builder()::password(password)->placeholder(trans("app.Şifre"))->groupClass("col-md-12") }}
			</div>
		</div>
		<div class="block-content block-content-full bg-body-light border-top p-3">
			<button type="button" class="btn btn-primary w-100 {{ BtnLogin }}">{{ trans("app.Giriş Yap") }}</button>
		</div>
	</div>
	<div class="text-center">
		@if(Session::has('authorize'))
			<div class="small mb-2 text-danger">{{ Session::get('authorize') }}</div>
		@endif
		<div class="small">{{ trans("app.Tüm Hakları Saklıdır") }}</div>
		<div class="small">{{ fake()->sentence() }}</div>
	</div>
	{{ form_builder()::close() }}
@endsection
