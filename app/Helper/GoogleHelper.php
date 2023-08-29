<?php

use Illuminate\Support\Facades\Http;

function GoogleImages($string){

	$response = Http::get('https://www.googleapis.com/customsearch/v1',[
        'q'=>$string,
        'key'=>env('GOOGLE_API_KEY'),
        'num'=>3,
        'searchType'=>'image',
        'cx'=>env('GOOGLE_SEARCH_CX'),
        'imgType'=>'stock'
    ]);

    return json_decode($response->body());

}