<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class User extends Controller {
        public function index() {
            $user         = User();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($user, \request());
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
            if (method_exists(Validation::class, "UserStore")) {
                $validator = Validation::UserStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $user = User();
                $user->fill($data);
                $user->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $user->toArray()]);
            }
        }
        public function show($id) {
            $user = User()->find($id);
            if (empty($user)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $user]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "UserUpdate")) {
                $validator = Validation::UserUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $user = User()->find($id);
                if (empty($user)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $user->fill($data);
                $user->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $user->toArray()]);
            }
        }
        public function destroy($id) {
            $user = User()->find($id);
            if (empty($user)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $user->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $user->toArray()]);
        }
    }
