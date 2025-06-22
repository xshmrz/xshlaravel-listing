<?php
use Illuminate\Support\Facades\Route;
Route::get('api', [\App\Http\Controllers\Api\Home::class, 'index'])->middleware(['MiddlewareApi'])->name('api.index');
Route::prefix('api')->as('api.')->middleware(['MiddlewareApi'])->group(function () {
Route::apiResource('appointment', \App\Http\Controllers\Api\Base\Appointment::class);
Route::apiResource('comment', \App\Http\Controllers\Api\Base\Comment::class);
Route::apiResource('customer', \App\Http\Controllers\Api\Base\Customer::class);
Route::apiResource('income-expense', \App\Http\Controllers\Api\Base\IncomeExpense::class);
Route::apiResource('income-expense-category', \App\Http\Controllers\Api\Base\IncomeExpenseCategory::class);
Route::apiResource('invoice', \App\Http\Controllers\Api\Base\Invoice::class);
Route::apiResource('listing', \App\Http\Controllers\Api\Base\Listing::class);
Route::apiResource('listing-category', \App\Http\Controllers\Api\Base\ListingCategory::class);
Route::apiResource('location', \App\Http\Controllers\Api\Base\Location::class);
Route::apiResource('migration', \App\Http\Controllers\Api\Base\Migration::class);
Route::apiResource('settings', \App\Http\Controllers\Api\Base\Settings::class);
Route::apiResource('user', \App\Http\Controllers\Api\Base\User::class);
Route::apiResource('worker', \App\Http\Controllers\Api\Base\Worker::class);
});
    // AUTHORIZE : API ROUTES
    Route::prefix('api')->name('api.')->group(function () {
        Route::post('login', [\App\Http\Controllers\Api\Authorize::class, 'loginCheck'])->name('login.check');
        Route::post('register', [\App\Http\Controllers\Api\Authorize::class, 'registerCheck'])->name('register.check');
        Route::post('lost-password', [\App\Http\Controllers\Api\Authorize::class, 'lostPasswordCheck'])->name('lostPassword.check');
        Route::post('logout', [\App\Http\Controllers\Api\Authorize::class, 'logoutCheck'])->name('logout.check');
    });

Route::get('dashboard', [\App\Http\Controllers\Dashboard\Home::class, 'index'])->middleware(['MiddlewareDashboard'])->name('dashboard.index');
Route::prefix('dashboard')->as('dashboard.')->middleware(['MiddlewareDashboard'])->group(function () {
Route::resource('appointment', \App\Http\Controllers\Dashboard\Base\Appointment::class);
Route::resource('comment', \App\Http\Controllers\Dashboard\Base\Comment::class);
Route::resource('customer', \App\Http\Controllers\Dashboard\Base\Customer::class);
Route::resource('income-expense', \App\Http\Controllers\Dashboard\Base\IncomeExpense::class);
Route::resource('income-expense-category', \App\Http\Controllers\Dashboard\Base\IncomeExpenseCategory::class);
Route::resource('invoice', \App\Http\Controllers\Dashboard\Base\Invoice::class);
Route::resource('listing', \App\Http\Controllers\Dashboard\Base\Listing::class);
Route::resource('listing-category', \App\Http\Controllers\Dashboard\Base\ListingCategory::class);
Route::resource('location', \App\Http\Controllers\Dashboard\Base\Location::class);
Route::resource('migration', \App\Http\Controllers\Dashboard\Base\Migration::class);
Route::resource('settings', \App\Http\Controllers\Dashboard\Base\Settings::class);
Route::resource('user', \App\Http\Controllers\Dashboard\Base\User::class);
Route::resource('worker', \App\Http\Controllers\Dashboard\Base\Worker::class);
});
    // AUTHORIZE : DASHBOARD ROUTES
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('login', [\App\Http\Controllers\Dashboard\Authorize::class, 'login'])->name('login');
        Route::post('login', [\App\Http\Controllers\Dashboard\Authorize::class, 'loginCheck'])->name('login.check');
        Route::get('register', [\App\Http\Controllers\Dashboard\Authorize::class, 'register'])->name('register');
        Route::post('register', [\App\Http\Controllers\Dashboard\Authorize::class, 'registerCheck'])->name('register.check');
        Route::get('lost-password', [\App\Http\Controllers\Dashboard\Authorize::class, 'lostPassword'])->name('lostPassword');
        Route::post('lost-password', [\App\Http\Controllers\Dashboard\Authorize::class, 'lostPasswordCheck'])->name('lostPassword.check');
        Route::get('logout', [\App\Http\Controllers\Dashboard\Authorize::class, 'logout'])->name('logout');
    });
Route::get('panel', [\App\Http\Controllers\Panel\Home::class, 'index'])->middleware(['MiddlewarePanel'])->name('panel.index');
Route::prefix('panel')->as('panel.')->middleware(['MiddlewarePanel'])->group(function () {
Route::resource('appointment', \App\Http\Controllers\Panel\Base\Appointment::class);
Route::resource('comment', \App\Http\Controllers\Panel\Base\Comment::class);
Route::resource('customer', \App\Http\Controllers\Panel\Base\Customer::class);
Route::resource('income-expense', \App\Http\Controllers\Panel\Base\IncomeExpense::class);
Route::resource('income-expense-category', \App\Http\Controllers\Panel\Base\IncomeExpenseCategory::class);
Route::resource('invoice', \App\Http\Controllers\Panel\Base\Invoice::class);
Route::resource('listing', \App\Http\Controllers\Panel\Base\Listing::class);
Route::resource('listing-category', \App\Http\Controllers\Panel\Base\ListingCategory::class);
Route::resource('location', \App\Http\Controllers\Panel\Base\Location::class);
Route::resource('migration', \App\Http\Controllers\Panel\Base\Migration::class);
Route::resource('settings', \App\Http\Controllers\Panel\Base\Settings::class);
Route::resource('user', \App\Http\Controllers\Panel\Base\User::class);
Route::resource('worker', \App\Http\Controllers\Panel\Base\Worker::class);
});
    // AUTHORIZE : PANEL ROUTES
    Route::prefix('panel')->name('panel.')->group(function () {
        Route::get('login', [\App\Http\Controllers\Panel\Authorize::class, 'login'])->name('login');
        Route::post('login', [\App\Http\Controllers\Panel\Authorize::class, 'loginCheck'])->name('login.check');
        Route::get('register', [\App\Http\Controllers\Panel\Authorize::class, 'register'])->name('register');
        Route::post('register', [\App\Http\Controllers\Panel\Authorize::class, 'registerCheck'])->name('register.check');
        Route::get('lost-password', [\App\Http\Controllers\Panel\Authorize::class, 'lostPassword'])->name('lostPassword');
        Route::post('lost-password', [\App\Http\Controllers\Panel\Authorize::class, 'lostPasswordCheck'])->name('lostPassword.check');
        Route::get('logout', [\App\Http\Controllers\Panel\Authorize::class, 'logout'])->name('logout');
    });
Route::get('', [\App\Http\Controllers\Site\Home::class, 'index'])->middleware(['MiddlewareSite'])->name('site.index');
Route::prefix('')->as('site.')->middleware(['MiddlewareSite'])->group(function () {
Route::resource('appointment', \App\Http\Controllers\Site\Base\Appointment::class);
Route::resource('comment', \App\Http\Controllers\Site\Base\Comment::class);
Route::resource('customer', \App\Http\Controllers\Site\Base\Customer::class);
Route::resource('income-expense', \App\Http\Controllers\Site\Base\IncomeExpense::class);
Route::resource('income-expense-category', \App\Http\Controllers\Site\Base\IncomeExpenseCategory::class);
Route::resource('invoice', \App\Http\Controllers\Site\Base\Invoice::class);
Route::resource('listing', \App\Http\Controllers\Site\Base\Listing::class);
Route::resource('listing-category', \App\Http\Controllers\Site\Base\ListingCategory::class);
Route::resource('location', \App\Http\Controllers\Site\Base\Location::class);
Route::resource('migration', \App\Http\Controllers\Site\Base\Migration::class);
Route::resource('settings', \App\Http\Controllers\Site\Base\Settings::class);
Route::resource('user', \App\Http\Controllers\Site\Base\User::class);
Route::resource('worker', \App\Http\Controllers\Site\Base\Worker::class);
});
    // AUTHORIZE : SITE ROUTES
    Route::prefix('')->name('site.')->group(function () {
        Route::get('login', [\App\Http\Controllers\Site\Authorize::class, 'login'])->name('login');
        Route::post('login', [\App\Http\Controllers\Site\Authorize::class, 'loginCheck'])->name('login.check');
        Route::get('register', [\App\Http\Controllers\Site\Authorize::class, 'register'])->name('register');
        Route::post('register', [\App\Http\Controllers\Site\Authorize::class, 'registerCheck'])->name('register.check');
        Route::get('lost-password', [\App\Http\Controllers\Site\Authorize::class, 'lostPassword'])->name('lostPassword');
        Route::post('lost-password', [\App\Http\Controllers\Site\Authorize::class, 'lostPasswordCheck'])->name('lostPassword.check');
        Route::get('logout', [\App\Http\Controllers\Site\Authorize::class, 'logout'])->name('logout');
        Route::post('logout', [\App\Http\Controllers\Site\Authorize::class, 'logoutCheck'])->name('logout.check');
    });

