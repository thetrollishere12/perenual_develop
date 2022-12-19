<?php

use GuzzleHttp\Client;

function paypal_bearer_token(){

	$client = new Client();
	
	$response = $client->request(
        'POST',
        env('PAYPAL_LINK').'/v1/oauth2/token?grant_type=client_credentials',
        ['auth' => [env('PAYPAL_CLIENT_ID'),env('PAYPAL_SECRET_ID')]] 
    );

    return json_decode($response->getBody())->access_token;

}

function show_authorized_payment($id,$token){

    $call = Http::withHeaders([
        'Content-Type' =>'application/json',
        'Authorization'=>'Bearer '.$token
    ])->get(env('PAYPAL_LINK')."/v2/checkout/orders/".$id);

    return json_decode($call->getBody());

}

function refund_captured_payment($id,$token,$data){
    
    $call = Http::withHeaders([
        'Content-Type' =>'application/json',
        'Authorization'=>'Bearer '.$token,
        'prefer'=>'return=representation'
    ])->post(env('PAYPAL_LINK')."/v2/payments/captures/".$id."/refund",[
        "amount"=>[
            "value" => $data->value,
            "currency_code" => $data->currency
        ],
        "note_to_payer" => $data->note
    ]);
    
    return json_decode($call->getBody());

}