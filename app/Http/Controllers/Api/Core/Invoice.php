<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Invoice extends Controller {
        public function index() {
            $invoice         = Invoice();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($invoice, \request());
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
            if (method_exists(Validation::class, "InvoiceStore")) {
                $validator = Validation::InvoiceStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $invoice = Invoice();
                $invoice->fill($data);
                $invoice->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $invoice->toArray()]);
            }
        }
        public function show($id) {
            $invoice = Invoice()->find($id);
            if (empty($invoice)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $invoice]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "InvoiceUpdate")) {
                $validator = Validation::InvoiceUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $invoice = Invoice()->find($id);
                if (empty($invoice)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $invoice->fill($data);
                $invoice->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $invoice->toArray()]);
            }
        }
        public function destroy($id) {
            $invoice = Invoice()->find($id);
            if (empty($invoice)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $invoice->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $invoice->toArray()]);
        }
    }
