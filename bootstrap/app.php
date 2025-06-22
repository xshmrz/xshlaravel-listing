<?php
    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );
    # -> ENVIRONMENT LOAD START
    function isLocalhost() : bool {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        return in_array($ip, ['127.0.0.1', '::1']);
    }
    if ($app->runningInConsole() || isLocalhost()) {
        $app->loadEnvironmentFrom('.env');
    }
    else {
        $app->loadEnvironmentFrom('.env.production');
    }
    # -> ENVIRONMENT LOAD END
    $app->singleton(
        Illuminate\Contracts\Http\Kernel::class,
        App\Http\Kernel::class
    );
    $app->singleton(
        Illuminate\Contracts\Console\Kernel::class,
        App\Console\Kernel::class
    );
    $app->singleton(
        Illuminate\Contracts\Debug\ExceptionHandler::class,
        App\Exceptions\Handler::class
    );
    return $app;
