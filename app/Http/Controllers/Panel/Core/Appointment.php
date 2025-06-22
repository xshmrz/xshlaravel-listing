<?php
    namespace App\Http\Controllers\Panel\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    use Validator;
    use Str;
    class Appointment extends Controller {
        public function index() {
            render_view($this->data);
        }
        public function create() {
            render_view($this->data);
        }
        public function store(Request $request) {
            $data = $request->all();
            if (method_exists(Validation::class, "AppointmentStore")) {
                $validator = Validation::AppointmentStore($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $appointment = Appointment();
            $appointment->fill($data);
            $appointment->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("panel.appointment.edit", $appointment->id);
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
            if (method_exists(Validation::class, "AppointmentUpdate")) {
                $validator = Validation::AppointmentUpdate($data);
            }
            else {
                $validator = Validator::make($data, []);
            }
            if ($validator->fails()) {
                session()->flash('validation', Str::title($validator->errors()->first()));
                return redirect()->back()->withInput();
            }
            $appointment = Appointment()->find($id);
            if (!$appointment) {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
                return redirect()->route("panel.appointment.index");
            }
            $appointment->fill($data);
            $appointment->save();
            session()->flash('success', trans("app.İşlem Başarılı"));
            return $request->has("redirect") ? redirect()->to($request->get("redirect")) : redirect()->route("panel.appointment.edit", $id);
        }
        public function destroy($id) {
            $appointment = Appointment()->find($id);
            if ($appointment) {
                $appointment->delete();
                session()->flash('success', trans("app.İşlem Başarılı"));
            }
            else {
                session()->flash('validation', trans("app.Kayıt Bulunamadı"));
            }
            return request()->has("redirect") ? redirect()->to(request()->get("redirect"))->withInput() : redirect()->route("panel.appointment.index")->withInput();
        }
    }
