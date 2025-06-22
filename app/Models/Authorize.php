<?php
    namespace App\Models;
    use App\Models\Base\User;
    use Illuminate\Support\Facades\Session;
    class Authorize extends User {
        const SESSION_USER = 'session_user';
        public function __construct(array $attributes = []) {
            parent::__construct($attributes);
            $this->initializeFromSession();
        }
        protected function initializeFromSession() : bool {
            if (Session::has(self::SESSION_USER)) {
                $data = Session::get(self::SESSION_USER);
                if (is_array($data)) {
                    $this->fillable(array_merge($this->getFillable(), ['id']));
                    $this->fill($data);
                    return true;
                }
            }
            return false;
        }
        public function store(User $user) : void {
            $data = $user->makeVisible($user->getAppends())->toArray();
            Session::put(self::SESSION_USER, $data);
        }
        public function clear() : void {
            Session::forget(self::SESSION_USER);
        }
        public function active() : bool {
            return Session::has(self::SESSION_USER);
        }
    }

