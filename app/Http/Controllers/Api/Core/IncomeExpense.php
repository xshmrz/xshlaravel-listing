<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class IncomeExpense extends Controller {
        public function index() {
            $incomeExpense         = IncomeExpense();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($incomeExpense, \request());
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
            if (method_exists(Validation::class, "IncomeExpenseStore")) {
                $validator = Validation::IncomeExpenseStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $incomeExpense = IncomeExpense();
                $incomeExpense->fill($data);
                $incomeExpense->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpense->toArray()]);
            }
        }
        public function show($id) {
            $incomeExpense = IncomeExpense()->find($id);
            if (empty($incomeExpense)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpense]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "IncomeExpenseUpdate")) {
                $validator = Validation::IncomeExpenseUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $incomeExpense = IncomeExpense()->find($id);
                if (empty($incomeExpense)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $incomeExpense->fill($data);
                $incomeExpense->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpense->toArray()]);
            }
        }
        public function destroy($id) {
            $incomeExpense = IncomeExpense()->find($id);
            if (empty($incomeExpense)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $incomeExpense->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpense->toArray()]);
        }
    }
