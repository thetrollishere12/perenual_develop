<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IdentityApiController extends Controller
{
    

    public function plantNet(){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://my-api.plantnet.org/v2/identify/all', [
            'api-key' => env('PLANTNET_KEY'),
            'images' => $image,
        ]);

    }

    public function plantId(){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Api-Key' => env('PLANTID_KEY'),
        ])->post('https://api.plant.id/v2/identify', [
            'images' => $image,
        ]);

    }


    public function OpenAI(){

        // // Provide the image URL and text prompt for classification
        // $imageUrl = 'https://cdn.shopify.com/s/files/1/0525/1206/3653/products/Monstera-XL-April-6-2023-Popular.png?v=1680814946';
        // $textPrompt = 'TEXT_PROMPT_FOR_CLASSIFICATION';

        // $response = Http::withHeaders([
        //     'Authorization'=>'Bearer '.env('OPENAI_KEY_V2'),
        //     'Content-Type' => 'application/json',
        // ])->post('https://api.openai.com/v1/engines/davinci/codex/completions', [
        //     'images' => [$imageUrl],
        //     'prompts' => [$textPrompt],
        //     'max_tokens' => 1,  // Set the desired number of classification labels
        // ]);

        // return json_decode($response->body());
        
    }

    public function identify(){



    }

}