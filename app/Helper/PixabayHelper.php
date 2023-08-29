<?php

use Illuminate\Support\Facades\Http;

function PixabayImages($string,$page){

	$response = Http::get('https://pixabay.com/api',[
        'key'=>env('PIXABAY_KEY'),
        'q' => $string,
        'per_page' => 200,
        'page'=>($page ? $page : 1),
        'image_type'=>'photo'
    ]);

    return json_decode($response->body());

}

function PixabayVideos($string,$page){

    $response = Http::get('https://pixabay.com/api/videos',[
        'key'=>env('PIXABAY_KEY'),
        'q' => $string,
        'per_page' => 200,
        'page'=>($page ? $page : 1),
        'image_type'=>'photo'
    ]);

    return json_decode($response->body());

}
