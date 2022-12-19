<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subset;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/categories-mysql',function(){

//     $categories = json_decode(File::get(public_path('storage/json/categories.json')), true);

//     foreach($categories as $category){

//         $new = new Category;
//         $new->name = $category['name'];
//         $new->save();

//         foreach($category['subcategory'] as $key => $subcategory){
            
//             $new1 = new Subcategory;
//             $new1->category_id = $new->id;
//             $new1->name = $key;
//             $new1->save();

//             if(isset($subcategory['subset'])){

//                 foreach($subcategory['subset'] as $subset){

//                     $new2 = new Subset;
//                     $new2->subcategory_id = $new1->id;
//                     $new2->name = $subset;
//                     $new2->save();

//                 }

//             }

//         }


//     }

// });

Route::get('/species', function (Request $request){

    return [];

})->name('api.species');

Route::get('/categories', function (Request $request) {
        

        $categories = Category::leftJoin('subcategories','categories.id','=','subcategories.category_id')
        ->leftJoin('subsets','subcategories.id','=','subsets.subcategory_id')
        ->select('categories.*','subcategories.id as subCategoryId','subcategories.name as SubcategoryName','subsets.id as SubsetId','subsets.name as SubsetName')
        ->orderBy('categories.name')
        ->orderBy('subcategories.name')
        ->orderBy('subsets.name')
        ->when($request->search != null,function($q) use ($request){
            $q->where('categories.name', 'like', "%{$request->search}%");
            $q->orWhere('subcategories.name', 'like', "%{$request->search}%");
            $q->orWhere('subsets.name', 'like', "%{$request->search}%");
        })
        ->get();    

        foreach($categories as $key => $category){

            $category->category = $category->name ? $category->category = $category->name : $category->category;
            $category->category = $category->SubcategoryName ? $category->category .= '<span class="px-2 text-sm icon-play3"></span>'.$category->SubcategoryName : $category->category;
            $category->category = $category->SubsetName ? $category->category .= '<span class="px-2 text-sm icon-play3"></span>'.$category->SubsetName : $category->category;

        }

        return $categories;


})->name('api.category');

Route::get('/country', function () {
    return json_decode(File::get(public_path('storage/json/backup.json')), true); 
})->name('api.country');

Route::get('/country+everywhere', function () {

    $json = json_decode(File::get(public_path('storage/json/backup.json')), true);

    $json[] = array("name"=>"Everywhere","code"=>"Everywhere");

    $json_with_key =  array(key($json)=>end($json));

    array_pop($json);

    return array_merge($json_with_key,$json);


})->name('api.country+everywhere');