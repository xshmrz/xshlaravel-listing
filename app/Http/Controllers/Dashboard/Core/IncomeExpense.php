<?php
    namespace App\Http\Controllers\Dashboard\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Validator;
    use Str;
    class IncomeExpense extends Controller {
        public function index() {
            render_view($this->data);
        }
        public function create() {
            render_view($this->data);
        }
        public function store(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, "IncomeExpenseStore")) {
                $validator = Validation::IncomeExpenseStore($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $incomeExpense = IncomeExpense();
            $incomeExpense->fill($data);
            $incomeExpense->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.income-expense.edit", $incomeExpense->id);
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
            if (method_exists(Validation::class, "IncomeExpenseUpdate")) {
                $validator = Validation::IncomeExpenseUpdate($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $incomeExpense = IncomeExpense()->find($id);
            if (!$incomeExpense) {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
                return redirect()->route("dashboard.income-expense.index");
            }
            $incomeExpense->fill($data);
            $incomeExpense->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.income-expense.edit", $id);
        }
        public function destroy($id) {
            $incomeExpense = IncomeExpense()->find($id);
            if ($incomeExpense) {
                $incomeExpense->delete();
                session()->flash('success', trans("app.İşlem Başarılı"));
            }
            else {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
            }
            return request()->has("redirect") ? redirect()->to(request()->get("redirect"))->withInput() : redirect()->route("dashboard.income-expense.index")->withInput();
        }
    }
