<?php
    namespace App\Http\Middleware;
    use Closure;
    use EnumUserCanLoginDashboard;
    use Illuminate\Http\Request;
    class MiddlewareDashboard {
        public function handle(Request $request, Closure $next) {
            if (!auth_model()->active()) {
                return redirect()->route("dashboard.login");
            }
            if (auth_model()->status == \EnumUserStatus::Passive or auth_model()->can_login_dashboard == EnumUserCanLoginDashboard::No) {
                auth_model()->clear();
                session()->flash("authorize", trans("app.Yönetim Paneli Giriş Yetkiniz Yok"));
                return redirect()->route("dashboard.login");
            }
            return $next($request);
        }
    }
