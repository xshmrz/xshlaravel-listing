<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Listing extends Controller {
        public function index() {
            $listing         = Listing();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($listing, \request());
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
            if (method_exists(Validation::class, "ListingStore")) {
                $validator = Validation::ListingStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $listing = Listing();
                $listing->fill($data);
                $listing->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listing->toArray()]);
            }
        }
        public function show($id) {
            $listing = Listing()->find($id);
            if (empty($listing)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listing]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "ListingUpdate")) {
                $validator = Validation::ListingUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $listing = Listing()->find($id);
                if (empty($listing)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $listing->fill($data);
                $listing->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listing->toArray()]);
            }
        }
        public function destroy($id) {
            $listing = Listing()->find($id);
            if (empty($listing)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $listing->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $listing->toArray()]);
        }
    }
