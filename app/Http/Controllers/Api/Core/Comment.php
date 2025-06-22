<?php
    namespace App\Http\Controllers\Api\Core;
    use Illuminate\Http\Request;
    use App\Helpers\Validation;
    use App\Http\Controllers\Controller;
    class Comment extends Controller {
        public function index() {
            $comment         = Comment();
            $queryBuilder = new \Bjerke\ApiQueryBuilder\QueryBuilder($comment, \request());
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
            if (method_exists(Validation::class, "CommentStore")) {
                $validator = Validation::CommentStore($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $comment = Comment();
                $comment->fill($data);
                $comment->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $comment->toArray()]);
            }
        }
        public function show($id) {
            $comment = Comment()->find($id);
            if (empty($comment)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $comment]);
        }
        public function update(Request $request, $id) {
            $data = $request->all();
            if (method_exists(Validation::class, "CommentUpdate")) {
                $validator = Validation::CommentUpdate($data);
            }
            else {
                $validator = \Validator::make($data, []);
            }
            if ($validator->fails()) {
                return responseUnprocessableEntity(["message" => $validator->errors()->first()]);
            }
            else {
                $comment = Comment()->find($id);
                if (empty($comment)) {
                    return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
                }
                $comment->fill($data);
                $comment->save();
                return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $comment->toArray()]);
            }
        }
        public function destroy($id) {
            $comment = Comment()->find($id);
            if (empty($comment)) {
                return responseNotFound(["message" => trans("app.Kayıt Bulunamadı"), "data" => []]);
            }
            $comment->delete();
            return responseOk(["message" => trans("app.İşlem Başarılı"), "data" => $comment->toArray()]);
        }
    }
