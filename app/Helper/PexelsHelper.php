<?php

use Illuminate\Support\Facades\Http;

function PexelImages($string,$page){

	$response = Http::withHeaders([
        'Authorization'=>env('PEXELS_KEY'),
    ])->get('https://api.pexels.com/v1/search',[
        'query'=>$string,
        'per_page'=>80,
        'page'=>($page ? $page : 1),
        // 'orientation'=>'square'
    ]);

    return json_decode($response->body());

}

function PexelVideos($string){
    $response = Http::withHeaders([
        'Authorization'=>env('PEXELS_KEY'),
    ])->get('https://api.pexels.com/v1/videos/search',[
        'query'=>$string,
        'per_page'=>5,
        'page'=>($page ? $page : 1),
    ]);

    return json_decode($response->body());
}