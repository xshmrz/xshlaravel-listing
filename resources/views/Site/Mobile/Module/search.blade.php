<!-- Search -->
<div class="offcanvas offcanvas-bottom action-sheet actionSheetSearchMdl" tabindex="-1">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title">{{ trans("app.Arama") }}</h5>
	</div>
	<div class="offcanvas-body">
		<div class="action-sheet-content">
			{{ form_builder()::open()->class("actionSheetSearchFrm") }}
			<div class="form-group basic">
				<div class="input-wrapper">
					<label class="form-label">{{ trans("app.Kategori") }}</label>
					{{ form_builder()::select(ListingCategory()->pluck(name,id),"listing_category_id")->prependEmptyOption(trans("app.Kategori Seç"))->class("form-control form-select")->withoutGroup() }}
				</div>
				<div class="input-info">{{ trans("app.Randevu Almak İstediğiniz Kategori") }}</div>
			</div>
			<div class="form-group basic">
				<div class="input-wrapper">
					<label class="form-label">{{ trans("app.Şehir") }}</label>
					{{ form_builder()::select(Location()->findState()->pluck(name,id),"location_parent_id")->prependEmptyOption(trans("app.Şehir Seç"))->data("location-select","state")->class("form-control form-select")->withoutGroup() }}
				</div>
				<div class="input-info">{{ trans("app.Randevu Almak İstediğiniz Şehir") }}</div>
			</div>
			<div class="form-group basic">
				<div class="input-wrapper">
					<label class="form-label">{{ trans("app.İlçe") }}</label>
					{{ form_builder()::select([],"location_id")->prependEmptyOption(trans("app.İlçe Seç"))->data("location-select","city")->class("form-control form-select")->withoutGroup() }}
				</div>
				<div class="input-info">{{ trans("app.Randevu Almak İstediğiniz İlçe") }}</div>
			</div>
			<div class="form-group basic">
				<button type="button" class="btn btn-primary btn-block actionSheetSearchBtn">{{ trans("app.Arama Yap") }}</button>
			</div>
			{{ form_builder()::close() }}
		</div>
	</div>
</div>
<script>
    $(function () {
        const $actionSheetSearchMdl    = $('.actionSheetSearchMdl');
        const $actionSheetSearchMdlBtn = $('.actionSheetSearchMdlBtn');
        const $actionSheetSearchBtn    = $('.actionSheetSearchBtn');
        const $actionSheetSearchFrm    = $('.actionSheetSearchFrm');
        $actionSheetSearchMdlBtn.click(function () {
            const actionSheet = new bootstrap.Offcanvas($actionSheetSearchMdl);
            actionSheet.show();
        });
        $actionSheetSearchBtn.click(function () {
            const data = xsh.getFormData($actionSheetSearchFrm);
            xsh.redirectTo('/find/' + data.listing_category_id + '/' + data.location_id);
        });
        $('[data-location-select="state"]').on('change', function () {
            const location_parent_id = $(this).val();
            console.log(location_parent_id);
            const location_id = $(this).closest('form').find('[data-location-select="city"]');
            location_id.find('option:not(:first)').remove();
            if (location_parent_id) {
                Location.GetAll({
                                    q : {
                                        'where[parent_id]': location_parent_id,
                                        'orderBy[name]'   : 'asc'
                                    },
                                    ok: function (response) {
                                        response.data.forEach(function (item) {
                                            location_id.append(
                                                $('<option>').val(item.id).text(item.name)
                                            );
                                        });
                                    }
                                });
            }
        });
    });
</script>
<!-- Search -->
