<?php
    function render_view($data = []) {
        $route      = get_route_info();
        $module     = $route['module'] ?? null;
        $controller = $route['controller'] ?? null;
        $method     = $route['method'] ?? null;
        $device     = (config('core.pwa.'.Str::lower($module)) && is_mobile()) ? 'Mobile' : 'Desktop';
        echo view($module.'.'.$device.'.'.$controller.'.'.$method)->with($data);
    }
    function is_mobile() {
        $agent = request()->server('HTTP_USER_AGENT', '');
        return preg_match('/Mobile|Android|iPhone|iPad|iPod|Opera Mini|IEMobile/', $agent);
    }
    function form_builder() {
        return new \Galahad\Aire\Support\Facades\Aire();
    }
    function auth_model() {
        return new \App\Models\Authorize();
    }
    function get_route_info() : array {
        $action = \Route::currentRouteAction();
        if (!$action) {
            return [
                'module'     => null,
                'controller' => null,
                'method'     => null,
            ];
        }
        [$fullController, $method] = explode('@', $action);
        $segments   = explode('\\', $fullController);
        $module     = $segments[3] ?? null;
        $controller = $segments[array_key_last($segments)] ?? null;
        return [
            'module'     => $module,
            'controller' => $controller,
            'method'     => $method,
        ];
    }
    function is_localhost() : bool {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        return in_array($ip, ['127.0.0.1', '::1']);
    }
    function placeholder($width, $height, $unsplash = false) {
        if ($unsplash == true) {
            return "https://unsplash.it/".$width."/".$height."?".rand(1000, 2000);
        }
        else {
            if (!File::exists('assets/placeholder/'.$width.'x'.$height.".png")) {
                $image = file_get_contents('https://dummyimage.com/'.$width.'x'.$height.'/CECECE/595959.png');
                file_put_contents('assets/placeholder/'.$width.'x'.$height.'.png', $image);
                return 'assets/placeholder/'.$width.'x'.$height.".png";
            }
            else {
                return 'assets/placeholder/'.$width.'x'.$height.".png";
            }
        }
    }
    function request_has_value(string $key, mixed $expected = null) : bool {
        if (!request()->has($key)) {
            return false;
        }
        return is_null($expected) || request()->get($key) == $expected;
    }

