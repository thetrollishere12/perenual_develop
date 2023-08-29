<?php

use Illuminate\Support\Facades\Http;

function AiGenerateText($string,$array){
    sleep(3);
	$response = Http::withHeaders([
        'Authorization'=>'Bearer '.env('OPENAI_KEY'),
    ])->post('https://api.openai.com/v1/completions',[
    	'model'=>(isset($array['model']) ? $array['temperature'] : "text-davinci-003"),
    	'prompt'=>$string,
    	'temperature'=> (isset($array['temperature']) ? $array['temperature'] : 1),
    	'max_tokens'=>(isset($array['max_tokens']) ? $array['max_tokens'] : 4000)
    ]);
    
    return json_decode($response->body())->choices[0]->text;

}

function AiGenerateImg($string){

	$response = Http::withHeaders([
        'Authorization'=>'Bearer '.env('OPENAI_KEY'),
    ])->post('https://api.openai.com/v1/images/generations',[
    	'prompt'=>$string,
    	'n'=>8,
    	'size'=>'512x512'
    ]);

    return json_decode($response->body());

}

function AiGenerateTextV2($string,$array,$sleep){
    sleep($sleep);
    $response = Http::withHeaders([
        'Authorization'=>'Bearer '.env('OPENAI_KEY_V2'),
    ])->post('https://api.openai.com/v1/completions',[
        'model'=>(isset($array['model']) ? $array['temperature'] : "text-davinci-003"),
        'prompt'=>$string,
        'temperature'=> (isset($array['temperature']) ? $array['temperature'] : 1),
        'max_tokens'=>(isset($array['max_tokens']) ? $array['max_tokens'] : 4000)
    ]);
    
    return string_number_to_number(json_decode($response->body())->choices[0]->text);

}


function AiGenerateTextV3($string,$array,$sleep){
    sleep($sleep);
    $response = Http::withHeaders([
        'Authorization'=>'Bearer '.env('OPENAI_KEY'),
    ])->post('https://api.openai.com/v1/completions',[
        'model'=>(isset($array['model']) ? $array['temperature'] : "text-davinci-003"),
        'prompt'=>$string,
        'temperature'=> (isset($array['temperature']) ? $array['temperature'] : 1),
        'max_tokens'=>(isset($array['max_tokens']) ? $array['max_tokens'] : 4000)
    ]);
    
    return string_number_to_number(json_decode($response->body())->choices[0]->text);

}


function string_number_to_number($string){

    $array = ['zero','one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve','thirteen','fourteen','fifteen'];

    foreach ($array as $key => $value) {
        $string = preg_replace('/\b'.$value.'\b/',$key, $string);
    }

    return $string;

}