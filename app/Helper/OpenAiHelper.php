<?php

use Illuminate\Support\Facades\Http;

function AiGenerateText($string){

	$response = Http::withHeaders([
        'Authorization'=>'Bearer '.env('OPENAI_KEY'),
    ])->post('https://api.openai.com/v1/completions',[
    	'model'=>"text-davinci-003",
    	'prompt'=>$string,
    	'temperature'=>1,
    	'max_tokens'=>4000
    ]);

    return json_decode($response->body())->choices[0]->text;

}

function AiGenerateImg($string){

	$response = Http::withHeaders([
        'Authorization'=>'Bearer '.env('OPENAI_KEY'),
    ])->post('https://api.openai.com/v1/images/generations',[
    	'prompt'=>$string,
    	'n'=>1,
    	'size'=>'1024x1024'
    ]);

    return json_decode($response->body());

}