<?php
    use Illuminate\Support\Facades\Route;
    Route::get('find/{listing_category_id}/{location_id}', [\App\Http\Controllers\Site\Custom::class, 'find'])->name('site.find');

