<?php
    namespace App\Http\Controllers\Site\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Validator;
    use Str;
    class IncomeExpenseCategory extends Controller {
        public function index() {
            render_view($this->data);
        }
        public function create() {
            render_view($this->data);
        }
        public function store(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, "IncomeExpenseCategoryStore")) {
                $validator = Validation::IncomeExpenseCategoryStore($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $incomeExpenseCategory = IncomeExpenseCategory();
            $incomeExpenseCategory->fill($data);
            $incomeExpenseCategory->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("site.income-expense-category.edit", $incomeExpenseCategory->id);
        }
        public function show($id) {
            $this->data["id"] = $id;
            render_view($this->data);
        }
        public function edit($id) {
            $this->data["id"] = $id;
            render_view($this->data);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "IncomeExpenseCategoryUpdate")) {
                $validator = Validation::IncomeExpenseCategoryUpdate($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $incomeExpenseCategory = IncomeExpenseCategory()->find($id);
            if (!$incomeExpenseCategory) {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
                return redirect()->route("site.income-expense-category.index");
            }
            $incomeExpenseCategory->fill($data);
            $incomeExpenseCategory->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("site.income-expense-category.edit", $id);
        }
        public function destroy($id) {
            $incomeExpenseCategory = IncomeExpenseCategory()->find($id);
            if ($incomeExpenseCategory) {
                $incomeExpenseCategory->delete();
                session()->flash('success', trans("app.İşlem Başarılı"));
            }
            else {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
            }
            return request()->has("redirect") ? redirect()->to(request()->get("redirect"))->withInput() : redirect()->route("site.income-expense-category.index")->withInput();
        }
    }
