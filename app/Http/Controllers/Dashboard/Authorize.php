<?php
    namespace App\Http\Controllers\Dashboard;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    class Authorize extends Controller {
        public function login() {
            render_view($this->data);
        }
        public function loginCheck(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, 'AuthorizeLoginCheck')) {
                $validator = Validation::AuthorizeLoginCheck($data);
                if ($validator->fails()) {
                    session()->flash('validation', $validator->errors());
                    return back()->withInput();
                }
            }
            // TODO : WITH CONFIG
            $user = User()->where(['email' => $data['email'], 'password' => md5($data['password'])])->first();
            if (!empty($user)) {
                $auth = new \App\Models\Authorize();
                $auth->store($user);
                session()->flash("success", trans("app.Giriş İşlemi Başarılı"));
                return redirect()->route("dashboard.index");
            }
            session()->flash("error", trans("app.E-Posta Veya Şifre Hatalı"));
            return redirect()->back()->withInput();
        }
        public function register() {
            render_view($this->data);
        }
        public function registerCheck(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, 'AuthorizeRegisterCheck')) {
                $validator = Validation::AuthorizeRegisterCheck($data);
                if ($validator->fails()) {
                    session()->flash('validation', $validator->errors());
                    return back()->withInput();
                }
            }
            $user = User();
            $user->fill($data);
            $user->save();
            session()->flash("success", trans("app.Kayıt İşlemi Başarılı"));
            return redirect()->route("dashboard.index");
        }
        public function lostPassword() {
            render_view($this->data);
        }
        public function lostPasswordCheck(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, 'AuthorizeLostPasswordCheck')) {
                $validator = Validation::AuthorizeLostPasswordCheck($data);
                if ($validator->fails()) {
                    session()->flash('validation', $validator->errors());
                    return back()->withInput();
                }
            }
            // TODO : WITH CONFIG
            $user = User()->where(['email' => $data['email']])->first();
            if (!empty($user)) {
                $password       = Str::random(8);
                $user->password = md5($password);
                $user->save();
                session()->flash("success", trans("app.Şifre Sıfırlama İşlemi Başarılı"));
                return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.index");
            }
            session()->flash("error", trans("app.E-Posta Veya Şifre Hatalı"));
            return redirect()->back()->withInput();
        }
        public function logout() {
            render_view($this->data);
        }
        public function logoutCheck(Request $request) {
            $auth = new \App\Models\Authorize();
            $auth->clear();
            session()->flash("success", trans("app.Çıkış İşlemi Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("dashboard.index");
        }
    }
