<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class IncomeExpenseCategory extends Controller {
        public function index() {
            $incomeExpenseCategory         = IncomeExpenseCategory();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($incomeExpenseCategory, \request());
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
            if (method_exists(Validation::class, "IncomeExpenseCategoryStore")) {
                $validator = Validation::IncomeExpenseCategoryStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $incomeExpenseCategory = IncomeExpenseCategory();
                $incomeExpenseCategory->fill($data);
                $incomeExpenseCategory->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpenseCategory->toArray()]);
            }
        }
        public function show($id) {
            $incomeExpenseCategory = IncomeExpenseCategory()->find($id);
            if (empty($incomeExpenseCategory)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpenseCategory]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "IncomeExpenseCategoryUpdate")) {
                $validator = Validation::IncomeExpenseCategoryUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $incomeExpenseCategory = IncomeExpenseCategory()->find($id);
                if (empty($incomeExpenseCategory)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $incomeExpenseCategory->fill($data);
                $incomeExpenseCategory->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpenseCategory->toArray()]);
            }
        }
        public function destroy($id) {
            $incomeExpenseCategory = IncomeExpenseCategory()->find($id);
            if (empty($incomeExpenseCategory)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $incomeExpenseCategory->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $incomeExpenseCategory->toArray()]);
        }
    }
