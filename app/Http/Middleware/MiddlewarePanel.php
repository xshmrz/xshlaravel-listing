<?php
    namespace App\Http\Middleware;
    use Closure;
    use Illuminate\Http\Request;
    class MiddlewarePanel {
        public function handle(Request $request, Closure $next) {
            if (!auth_model()->active()) {
                return redirect()->route("panel.login");
            }
            return $next($request);
        }
    }
