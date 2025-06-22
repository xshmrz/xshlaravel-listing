<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Worker extends Controller {
        public function index() {
            $worker         = Worker();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($worker, \request());
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
            if (method_exists(Validation::class, "WorkerStore")) {
                $validator = Validation::WorkerStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $worker = Worker();
                $worker->fill($data);
                $worker->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $worker->toArray()]);
            }
        }
        public function show($id) {
            $worker = Worker()->find($id);
            if (empty($worker)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $worker]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "WorkerUpdate")) {
                $validator = Validation::WorkerUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $worker = Worker()->find($id);
                if (empty($worker)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $worker->fill($data);
                $worker->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $worker->toArray()]);
            }
        }
        public function destroy($id) {
            $worker = Worker()->find($id);
            if (empty($worker)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $worker->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $worker->toArray()]);
        }
    }
