<?php
    namespace App\Helpers;
    class Validation {
        private static function check(array $data, array $rules = [], array $messages = []) {
            return request()->boolean('validate', true) ? \Validator::make($data, $rules, $messages) : \Validator::make($data, [], []);
        }
        public static function AuthorizeLoginCheck(array $data) {
            return self::check($data,
                [
                    "email"    => "required|email",
                    "password" => "required",
                ],
                [
                    "email.required"    => trans("app.E-Posta Adresi Zorunludur"),
                    "email.email"       => trans("app.E-Posta Adresi Zorunludur"),
                    "password.required" => trans("app.Şifre Alanı Boş Bırakılamaz"),
                ]);
        }
    }
