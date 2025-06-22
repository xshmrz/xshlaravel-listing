<?php
    namespace App\Http\Controllers\Dashboard\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Validator;
    use Str;
    class User extends Controller {
        public function index() {
            render_view($this->data);
        }
        public function create() {
            render_view($this->data);
        }
        public function store(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, "UserStore")) {
                $validator = Validation::UserStore($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $user = User();
            $user->fill($data);
            $user->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.user.edit", $user->id);
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
            if (method_exists(Validation::class, "UserUpdate")) {
                $validator = Validation::UserUpdate($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $user = User()->find($id);
            if (!$user) {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
                return redirect()->route("dashboard.user.index");
            }
            $user->fill($data);
            $user->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.user.edit", $id);
        }
        public function destroy($id) {
            $user = User()->find($id);
            if ($user) {
                $user->delete();
                session()->flash('success', trans("app.İşlem Başarılı"));
            }
            else {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
            }
            return request()->has("redirect") ? redirect()->to(request()->get("redirect"))->withInput() : redirect()->route("dashboard.user.index")->withInput();
        }
    }
