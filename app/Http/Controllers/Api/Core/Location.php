<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Location extends Controller {
        public function index() {
            $location         = Location();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($location, \request());
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
            if (method_exists(Validation::class, "LocationStore")) {
                $validator = Validation::LocationStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $location = Location();
                $location->fill($data);
                $location->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $location->toArray()]);
            }
        }
        public function show($id) {
            $location = Location()->find($id);
            if (empty($location)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $location]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "LocationUpdate")) {
                $validator = Validation::LocationUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $location = Location()->find($id);
                if (empty($location)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $location->fill($data);
                $location->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $location->toArray()]);
            }
        }
        public function destroy($id) {
            $location = Location()->find($id);
            if (empty($location)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $location->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $location->toArray()]);
        }
    }
