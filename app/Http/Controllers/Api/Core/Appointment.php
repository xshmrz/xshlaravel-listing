<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Appointment extends Controller {
        public function index() {
            $appointment         = Appointment();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($appointment, \request());
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
            if (method_exists(Validation::class, "AppointmentStore")) {
                $validator = Validation::AppointmentStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $appointment = Appointment();
                $appointment->fill($data);
                $appointment->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $appointment->toArray()]);
            }
        }
        public function show($id) {
            $appointment = Appointment()->find($id);
            if (empty($appointment)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $appointment]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "AppointmentUpdate")) {
                $validator = Validation::AppointmentUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $appointment = Appointment()->find($id);
                if (empty($appointment)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $appointment->fill($data);
                $appointment->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $appointment->toArray()]);
            }
        }
        public function destroy($id) {
            $appointment = Appointment()->find($id);
            if (empty($appointment)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $appointment->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $appointment->toArray()]);
        }
    }
