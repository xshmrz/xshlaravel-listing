<!doctype html>
<html lang="{{ config("app.locale") }}">
@include("Dashboard.Desktop.component-header")
<body>
<div id="page-container" class="sidebar-dark side-scroll page-header-fixed page-header-dark main-content-boxed">
	<nav id="sidebar">
		<div class="sidebar-content">
			<div class="content-header justify-content-lg-center bg-black-10">
				<div>
					<a class="link-fx text-white mx-auto" href="{{ trans("app.Index") }}">{{ config("app.name") }}</a>
				</div>
				<div>
					<button type="button" class="btn btn-sm btn-link text-white d-lg-none" data-toggle="layout" data-action="sidebar_close">
						<i class="fa fa-chevron-left"></i>
					</button>
				</div>
			</div>
			<div class="js-sidebar-scroll">
				<div class="content-side content-side-full">
					<ul class="nav-main">
						<li class="nav-main-item">
							<a class="nav-main-link" href="{{ route("dashboard.index") }}">
								<i class="nav-main-link-icon fa fa-chevron-right"></i>
								<span class="nav-main-link-name">{{ trans("app.Index") }}</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<header id="page-header">
		<div class="content-header">
			<div class="space-x-1">
				<a class="link-fx text-white" href="{{ route("dashboard.index") }}">{{ config("app.name") }}</a>
			</div>
			<div class="d-none d-lg-block">
				<ul class="nav-main nav-main-horizontal nav-main-hover">
					<li class="nav-main-item"><a class="nav-main-link" href="{{ route("dashboard.index") }}">{{ trans("app.Index") }}</a></li>
					<li class="nav-main-item"><a class="nav-main-link" href="{{ route("dashboard.user.index") }}">{{ trans("app.Kullanıcılar") }}</a></li>
				</ul>
			</div>
			<div class="space-x-1">
				<div class="dropdown d-inline-block">
					<button type="button" class="btn btn-sm btn-link text-white" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{{ auth_model()->full_name }}
					</button>
					<div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0">
						<div class="p-2">
							<a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" href="javascript:void(0)"><span>{{ trans("app.Profil") }}</span><i class="fa fa-chevron-right opacity-25"></i></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item d-flex align-items-center justify-content-between space-x-1 BtnLogout" href="javascript:void(0)"><span>{{ trans("app.Çıkış Yap") }}</span><i class="fa fa-chevron-right opacity-25"></i></a>
						</div>
					</div>
				</div>
				<button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
					<i class="fa fa-chevron-right"></i>
				</button>
			</div>
		</div>
	</header>
	<main id="main-container">
		@yield("section-main")
	</main>
	<footer id="page-footer" class="bg-body-light border-top">
		<div class="content py-3">
			<div class="row fs-sm">
				<div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
					<a class="fw-semibold" href="https://xshmrz.com" target="_blank">Xshmrz</a> | Simplicity
				</div>
				<div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
					<a class="fw-semibold" href="javascript:void(0)">{{ config("app.name") }}</a> &copy;
					<span data-toggle="year-copy"></span>
				</div>
			</div>
		</div>
	</footer>
</div>
@include("Dashboard.Desktop.component-footer")
</body>
</html>
