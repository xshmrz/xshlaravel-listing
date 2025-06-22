<?php
    namespace App\Exceptions;
    use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
    use Throwable;
    class Handler extends ExceptionHandler {
        protected $dontFlash = [
        ];
        public function register() : void {
            $this->reportable(function (Throwable $e) {});
        }
    }
