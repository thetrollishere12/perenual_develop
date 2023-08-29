<?php

use Illuminate\Support\Facades\Http;

function UnsplashImages($string,$page){

	$response = Http::get('https://api.unsplash.com/search/photos',[
        'client_id'=>env('UNSPLASH_CLIENT'),
        'query' => $string,
        'per_page' => 30,
        'page'=>($page ? $page : 1),
        // 'orientation'=>'squarish'
    ]);

    return json_decode($response->body());

}


function UnsplashCollection($string,$page){

    $response = Http::get('https://api.unsplash.com/collections',[
        'client_id'=>env('UNSPLASH_CLIENT'),
        'query' => $string,
        'per_page' => 30,
        'page'=>($page ? $page : 1)
    ]);

    return json_decode($response->body());

}
