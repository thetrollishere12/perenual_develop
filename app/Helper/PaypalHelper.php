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

function paypal_subscription($id,$token){

    $call = Http::withHeaders([
        'Content-Type' =>'application/json',
        'Authorization'=>'Bearer '.$token
    ])->get(env('PAYPAL_LINK')."/v1/billing/subscriptions/".$id);

    return json_decode($call->getBody());

}

function paypal_subscription_activate($id,$token){

    // Http::withHeaders([
    //     'Content-Type'=>'application/json',
    //     'Authorization'=>'Bearer '.$token
    // ])->post(env('PAYPAL_LINK').'/v1/billing/subscriptions/'.$id.'/activate');

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('PAYPAL_LINK').'/v1/billing/subscriptions/'.$id.'/activate',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

}


function paypal_subscription_suspend($id,$token){

    // Http::withHeaders([
    //     'Content-Type'=>'application/json',
    //     'Authorization'=>'Bearer '.$token
    // ])->post('https://api-m.sandbox.paypal.com/v1/billing/subscriptions/'.$id.'/suspend');

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('PAYPAL_LINK').'/v1/billing/subscriptions/'.$id.'/suspend',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);


}


function paypal_subscription_revise($id,$token,$plan_id){

    // Http::withHeaders([
    //     'Content-Type'=>'application/json',
    //     'Authorization'=>'Bearer '.$token
    // ])->post('https://api-m.sandbox.paypal.com/v1/billing/subscriptions/'.$id.'/suspend');

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => env('PAYPAL_LINK').'/v1/billing/subscriptions/'.$id.'/revise',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
            "plan_id":"'.$plan_id.'"
        }',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response);


}


function paypal_subscription_plan_details($token,$plan_id){

    $call = Http::withHeaders([
        'Content-Type' =>'application/json',
        'Authorization'=>'Bearer '.$token
    ])->get(env('PAYPAL_LINK')."/v1/billing/plans/".$plan_id);

    return json_decode($call->getBody());

}