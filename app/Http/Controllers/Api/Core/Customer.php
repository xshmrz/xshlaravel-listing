<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Customer extends Controller {
        public function index() {
            $customer         = Customer();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($customer, \request());
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
            if (method_exists(Validation::class, "CustomerStore")) {
                $validator = Validation::CustomerStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $customer = Customer();
                $customer->fill($data);
                $customer->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $customer->toArray()]);
            }
        }
        public function show($id) {
            $customer = Customer()->find($id);
            if (empty($customer)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $customer]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "CustomerUpdate")) {
                $validator = Validation::CustomerUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $customer = Customer()->find($id);
                if (empty($customer)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $customer->fill($data);
                $customer->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $customer->toArray()]);
            }
        }
        public function destroy($id) {
            $customer = Customer()->find($id);
            if (empty($customer)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $customer->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $customer->toArray()]);
        }
    }
