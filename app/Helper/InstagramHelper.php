<?php

use Illuminate\Support\Facades\Http;

function ig_b_access_token($code){

	$response = Http::get('https://graph.instagram.com/access_token',[
        'grant_type'=>'ig_exchange_token',
        'client_secret'=>env('INSTAGRAMBASIC_CLIENT_SECRET'),
        'access_token'=>$code
    ]);

    return json_decode($response->body());

}

function ig_b_refresh_token($code){

	$response = Http::get('https://graph.instagram.com/refresh_access_token',[
        'grant_type'=>'ig_refresh_token',
        'access_token'=>$code
    ]);

    return json_decode($response->body());

}

function ig_b_media($media_id,$token){

	$response = Http::get('https://graph.instagram.com/'.$media_id,[
        'access_token'=>$token,
        'fields'=>'caption,id,is_shared_to_feed,media_type,media_url,permalink,thumbnail_url,timestamp,username',
        'edge'=>'children'
    ]);

    return json_decode($response->body());

}

function ig_b_media_children($media_id,$token){

	$response = Http::get('https://graph.instagram.com/'.$media_id.'/children',[
        'access_token'=>$token,
        'fields'=>'caption,id,is_shared_to_feed,media_type,media_url,permalink,thumbnail_url,timestamp,username'
    ]);

    return json_decode($response->body());

}

function ig_b_user_media($id,$token){

	$response = Http::get('https://graph.instagram.com/'.$id.'/media',[
        'access_token'=>$token,
        'permission'=>'instagram_graph_user_media, instagram_graph_user_profile',
        'fields'=>'caption,id,is_shared_to_feed,media_type,media_url,permalink,thumbnail_url,timestamp,username'
    ]);

    return json_decode($response->body());

}

// function ig_b_me(){

// }

// function ig_b_user_id{

// }

// function ig_b_user_id_media{

// }