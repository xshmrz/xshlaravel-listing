<?php
    namespace App\Http\Controllers\Dashboard\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Validator;
    use Str;
    class Migration extends Controller {
        public function index() {
            render_view($this->data);
        }
        public function create() {
            render_view($this->data);
        }
        public function store(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, "MigrationStore")) {
                $validator = Validation::MigrationStore($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $migration = Migration();
            $migration->fill($data);
            $migration->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.migration.edit", $migration->id);
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
            if (method_exists(Validation::class, "MigrationUpdate")) {
                $validator = Validation::MigrationUpdate($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $migration = Migration()->find($id);
            if (!$migration) {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
                return redirect()->route("dashboard.migration.index");
            }
            $migration->fill($data);
            $migration->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.migration.edit", $id);
        }
        public function destroy($id) {
            $migration = Migration()->find($id);
            if ($migration) {
                $migration->delete();
                session()->flash('success', trans("app.İşlem Başarılı"));
            }
            else {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
            }
            return request()->has("redirect") ? redirect()->to(request()->get("redirect"))->withInput() : redirect()->route("dashboard.migration.index")->withInput();
        }
    }
