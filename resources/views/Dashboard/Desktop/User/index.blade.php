@extends("Dashboard.Desktop.layout")
@section("section-main")
	<div class="bg-body-light border-bottom">
		<div class="content py-1 text-center">
			<nav class="breadcrumb bg-body-light py-2 mb-0">
				<span class="breadcrumb-item">{{ trans("app.Yönetim : Sistem") }}</span>
				<span class="breadcrumb-item active">{{ trans("app.Kullanıcılar") }}</span>
			</nav>
		</div>
	</div>
	<div class="content content-full">
		<div class="block block-rounded block-bordered">
			<div class="block-header block-header-default">
				<h3 class="block-title">{{ trans("app.Kullanıcılar") }}</h3>
				<div class="block-options">
					{{ html()->a("javascript:void(0)",trans("app.Ekle"))->class("btn btn-sm btn-primary")->style("min-width:75px;") }}
				</div>
			</div>
			<div class="block-content block-content-full p-0 border-top overflow-x-auto">
				<div class="dataTableDisplay d-none">
                    <?php
                        $data = User()->get()->toArray();
                        # -> Defaults
                        $table = new DataTableBuilder();
                        $table->setId("TableUser");
                        $table->setData($data);
                        # -> Cols
                        $table->addCol()->title(trans("app.Id"))->key("id")->class("text-center")->style("width: 50px")->done();
                        $table->addCol()->title(trans("app.Ad Soyad"))->key("full_name")->class("text-start")->done();
                        $table->addCol("html")->title(trans("app.Yetki"))->class("text-center")->style("width:100px")->callback(function ($row) {
                            return html()->span(EnumUserRole::translation($row[role]))->class("badge fs-sm bg-".EnumUserRole::color($row["role"]));
                        })->done();
                        $table->addCol("html")->title(trans("app.İşlem"))->class("text-center")->style("width:100px")->callback(function ($row) {
                            $url = "javascript:void(0)";
                            return html()->a($url, trans("app.Düzenle"))->class("btn btn-sm btn-primary")->style("min-width:75px;");
                        })->done();
                        # -> Render
                        echo $table->render();
                    ?>
				</div>
			</div>
		</div>
	</div>
@endsection
