<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    // Cookie::queue(Cookie::forget('recently_viewed'));

    return view('welcome');
});


// Currency

Route::post('currency','CurrencyController@currency');

// Country

Route::post('country','CurrencyController@country');

// Plant Database Search
Route::get('plant-database-seach-finder','PlantSearchController@index');

Route::get('plant-database-seach-finder/species/{id}','PlantSearchController@show');

// Plant Database Help Guide
Route::get('plant-database-seach-guide','PlantSearchController@index_guide');
Route::get('plant-database-seach-guide/species/{id}/guide','PlantSearchController@show_guide');

// Sandbox
if (env('APP_STATUS') == 'SANDBOX') {
    Route::get('sandbox','SandboxController@index');
    Route::get('sandbox/test','SandboxController@test');

    Route::get('sandbox/image-post','SandboxController@image_post');

}


Route::fallback(function() {
    return view('errors.custom.404');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    // Developer

    Route::get('user/developer',function(){
        return view('profile.user.developer');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



