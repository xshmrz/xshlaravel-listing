<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class ListingCategory extends Controller {
        public function index() {
            $listingCategory         = ListingCategory();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($listingCategory, \request());
            $queryBuilder = $queryBuilder->build();
            if (\request()->has("pagination") && \request()->get("pagination") == "true") {
                if (\request()->has("per_page")) {
                    return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $queryBuilder->paginate(\request()->get("per_page"))->appends(\request()->except('page'))]);
                }
                else {
                    return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $queryBuilder->paginate()->appends(\request()->get('page'))]);
                }
            }
            else {
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $queryBuilder->get()]);
            }
        }
        public function store(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, "ListingCategoryStore")) {
                $validator = Validation::ListingCategoryStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $listingCategory = ListingCategory();
                $listingCategory->fill($data);
                $listingCategory->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listingCategory->toArray()]);
            }
        }
        public function show($id) {
            $listingCategory = ListingCategory()->find($id);
            if (empty($listingCategory)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listingCategory]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "ListingCategoryUpdate")) {
                $validator = Validation::ListingCategoryUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $listingCategory = ListingCategory()->find($id);
                if (empty($listingCategory)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $listingCategory->fill($data);
                $listingCategory->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listingCategory->toArray()]);
            }
        }
        public function destroy($id) {
            $listingCategory = ListingCategory()->find($id);
            if (empty($listingCategory)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $listingCategory->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listingCategory->toArray()]);
        }
    }
