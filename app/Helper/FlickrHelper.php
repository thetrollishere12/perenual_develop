<?php

use Illuminate\Support\Facades\Http;

function FlickrImages($string,$page){

	$response = Http::get('https://www.flickr.com/services/rest/?method=flickr.photos.search&text='.$string.'&api_key='.env('FLICKR_KEY').'&format=json&license=4,5,6,7,9,10&nojsoncallback=1&page='.($page ? $page : 1));

    return json_decode($response->body())->photos;

}

function FlickrImageGetInfo($id){
	$response = Http::get('https://www.flickr.com/services/rest/?method=flickr.photos.getInfo&photo_id='.$id.'&api_key='.env('FLICKR_KEY').'&format=json&nojsoncallback=1');

    return json_decode($response->body());
}