<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Settings extends Controller {
        public function index() {
            $settings         = Settings();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($settings, \request());
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
            if (method_exists(Validation::class, "SettingsStore")) {
                $validator = Validation::SettingsStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $settings = Settings();
                $settings->fill($data);
                $settings->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $settings->toArray()]);
            }
        }
        public function show($id) {
            $settings = Settings()->find($id);
            if (empty($settings)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $settings]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "SettingsUpdate")) {
                $validator = Validation::SettingsUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $settings = Settings()->find($id);
                if (empty($settings)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $settings->fill($data);
                $settings->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $settings->toArray()]);
            }
        }
        public function destroy($id) {
            $settings = Settings()->find($id);
            if (empty($settings)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $settings->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $settings->toArray()]);
        }
    }
