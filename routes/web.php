<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantSearchController;
use App\Http\Livewire\PlantSearch\Comment;

Route::get('/', function () {

    $hot = Product::leftJoin('product_elements','products.id','=','product_elements.product_id')->select('products.*','product_elements.seen')->orderBy('seen','DESC')->limit(15)->get();

    product_details($hot);
    
    $new = Product::orderBy('created_at','DESC')->limit(15)->get();

    product_details($new);

    $recently_viewed = [];
    if (request()->cookie('recently_viewed')) {
            
        $id = implode(',',json_decode(request()->cookie('recently_viewed')));

        $recently_viewed = Product::whereIn('id',json_decode(request()->cookie('recently_viewed')))->limit(10)
        ->orderByRaw("FIELD(id,$id)")
        ->get();

        product_details($recently_viewed);

    }

    // Cookie::queue(Cookie::forget('recently_viewed'));

    return view('welcome',['hot_products'=>$hot,'new_products'=>$new,'recently_viewed'=>$recently_viewed]);
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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// i have put this url to check i didn't find the url for commment component you can removed it
Route::get('product-comment',Comment::class);
