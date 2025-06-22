<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Migration extends Controller {
        public function index() {
            $migration         = Migration();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($migration, \request());
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
            if (method_exists(Validation::class, "MigrationStore")) {
                $validator = Validation::MigrationStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $migration = Migration();
                $migration->fill($data);
                $migration->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $migration->toArray()]);
            }
        }
        public function show($id) {
            $migration = Migration()->find($id);
            if (empty($migration)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $migration]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "MigrationUpdate")) {
                $validator = Validation::MigrationUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $migration = Migration()->find($id);
                if (empty($migration)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $migration->fill($data);
                $migration->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $migration->toArray()]);
            }
        }
        public function destroy($id) {
            $migration = Migration()->find($id);
            if (empty($migration)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $migration->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $migration->toArray()]);
        }
    }
