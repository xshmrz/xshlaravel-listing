<?php
    namespace App\Http\Controllers\Api;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    class Authorize extends Controller {
        public function loginCheck(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, 'AuthorizeLoginCheck')) {
                $validator = Validation::AuthorizeLoginCheck($data);
                if ($validator->fails()) {
                    return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
                }
            }
            // TODO : WITH CONFIG
            $user = User()->where(['email' => $data['email'], 'password' => md5($data['password'])])->first();
            if (!empty($user)) {
                $auth = new \App\Models\Authorize();
                $auth->store($user);
                return responseOk(["message" => trans("app.Giriş İşlemi Başarılı"), "data" => $data]);
            }
            return responseUnauthorized(["message" => trans("app.E-Posta Veya Şifre Hatalı"), "data" => $data]);
        }
        public function registerCheck(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, 'AuthorizeRegisterCheck')) {
                $validator = Validation::AuthorizeRegisterCheck($data);
                if ($validator->fails()) {
                    return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
                }
            }
            $user = User();
            $user->fill($data);
            $user->save();
            return responseOk(["message" => trans("app.Kayıt İşlemi Başarılı"), "data" => $data]);
        }
        public function lostPasswordCheck(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, 'AuthorizeLostPasswordCheck')) {
                $validator = Validation::AuthorizeLostPasswordCheck($data);
                if ($validator->fails()) {
                    return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
                }
            }
            // TODO : WITH CONFIG
            $user = User()->where(['email' => $data['email']])->first();
            if (!empty($user)) {
                $password       = Str::random(8);
                $user->password = md5($password);
                $user->save();
                return responseOk(["message" => trans("app.Şifre Sıfırlama İşlemi Başarılı"), "data" => $data]);
            }
            return responseUnauthorized(["message" => trans("app.E-Posta Veya Şifre Hatalı"), "data" => $data]);
        }
        public function logoutCheck(Request $request) {
            $data = $request->all();
            $auth = new \App\Models\Authorize();
            $auth->clear();
            return responseOk(["message" => trans("app.Çıkış İşlemi Başarılı"), "data" => $data]);
        }
    }
