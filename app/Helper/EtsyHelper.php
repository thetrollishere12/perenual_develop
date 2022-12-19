<?php

use Illuminate\Support\Facades\Http;
use App\Models\EtsyAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
function etsy_token_refresh($id){

    $account = EtsyAccount::where('parent_email',auth()->user()->email)->where('etsy_shop_id',$id)->get()->first();

    if (!is_null($account) > 0) {

        $response = Http::asForm()->post('https://api.etsy.com/v3/public/oauth/token',[
            'grant_type'=>'refresh_token',
            'client_id'=>env('ETSY_KEYSTRING'),
            'refresh_token'=>$account->refresh_token
        ]);

        $new_oauth = json_decode($response->getBody());

        $account->update([
            'bearer_token'=>$new_oauth->access_token,
            'refresh_token'=>$new_oauth->refresh_token
        ]);

        return $new_oauth->access_token;

    }else{
        return "account doesnt exist";
    }

}

function etsy_get_user($token,$id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->get('https://openapi.etsy.com/v3/application/users/'.$id);

    return json_decode($response->getBody());

}

function etsy_get_store($id){
    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$id);

    return json_decode($response->getBody());
}

function etsy_get_shop_by_userid($id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING')
    ])->get('https://openapi.etsy.com/v3/application/users/'.$id.'/shops');

    return json_decode($response->getBody());

}

function etsy_receipt($token,$shop_id,$min,$max,$limit,$offset){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/receipts?min_created='.$min.'&max_created='.$max.'&limit='.$limit."&offset=".$offset);

    return json_decode($response->getBody());

}

function etsy_receipt_by_id($token,$shop_id,$id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/receipts/'.$id.'/payments');

    return json_decode($response->getBody());

}

function etsy_ledger_entry($token,$shop_id,$min,$max,$limit,$offset){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/payment-account/ledger-entries?min_created='.$min.'&max_created='.$max.'&limit='.$limit."&offset=".$offset);

    return json_decode($response->getBody());

}

function etsy_get_listings_by_shop($token,$shop_id,$state,$limit,$offset,$sort_on,$sort_order){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings?state='.$state.'&limit='.$limit."&offset=".$offset."&sort_on=".$sort_on."&sort_order=".$sort_order);

    return json_decode($response->getBody());

}

function etsy_get_listings_image_by_id($shop_id,$id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING')
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings/'.$id.'/images');

    return json_decode($response->getBody());

}

function etsy_get_listings_image_by_image_id($shop_id,$id,$image_id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING')
    ])->get('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings/'.$id.'/images/'.$image_id);

    return json_decode($response->getBody());

}




function etsy_create_draft_listing($shop_id,$token){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->asForm()
    ->post('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings',
        [
            'quantity'=>2,
            'title'=>'test12313',
            'description'=>'sdfsfsfsfsf',
            'price'=>34,
            'who_made'=>'i_did',
            'when_made'=>'made_to_order',
            'taxonomy_id'=>1,
            'type'=>'download'
        ]
    );
    
    return json_decode($response->getBody());

}

function etsy_update_listing($shop_id,$token,$listing_id,$body){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->asForm()
    ->post('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings',
        [
            'quantity'=>2,
            'title'=>'test12313',
            'description'=>'sdfsfsfsfsf',
            'price'=>34,
            'who_made'=>'i_did',
            'when_made'=>'made_to_order',
            'taxonomy_id'=>1,
            'type'=>'download'
        ]
    );
    
    return json_decode($response->getBody());

}

function etsy_delete_listing($shop_id,$token,$listing_id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->asForm()
    ->post('https://openapi.etsy.com/v3/application/listings/'.$listing_id);
    
    return json_decode($response->getBody());

}


function etsy_upload_listing_image($shop_id,$token,$listing_id,$body){

    // $response = Http::withHeaders([
    //     'Content-Type'=>'multipart/form-data; boundary=' . microtime(true),
    //     'x-api-key'=>env('ETSY_KEYSTRING'),
    //     'authorization'=>'Bearer '.$token
    // ])
    // ->attach('image',$body,'360-degrees (1).png')
    // ->post('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings/'.$listing_id.'/images');
    
    // return json_decode($response->getBody());


//         $client = new Client();
// $headers = [
//   'x-api-key' => '4qa9aor3z104sk3b0bmsdcix',
//   'Authorization' => 'Bearer 225432101.6Rz7WaFlVK58QVdjnN_wUvlB26WZ5uoKQGUjBxSyRbGZYGmsg0nPmpSVQvdUxXezyoOn_3cr9jqSeeCksaL-TQS-5w',
//   'Cookie' => 'user_prefs=YFBU74jeYLuzpFwdH04XY-QPiAtjZACCxL0TjGB0dF5pTo4OeUQsAwA.'
// ];
// $options = [
//   'multipart' => [
//     [
//       'name' => 'image',
//       'contents' => Utils::tryFopen('C:/Users/brandon huynh/Downloads/finalfees import systemm.jpg', 'r'),
//       'filename' => 'C:/Users/brandon huynh/Downloads/finalfees import systemm.jpg',
//       'headers'  => [
//         'Content-Type' => '<Content-type header>'
//       ]
//     ]
// ]];
// $request = new Request('POST', 'https://openapi.etsy.com/v3/application/shops/20464863/listings/1238687272/images', $headers);
// $res = $client->sendAsync($request, $options)->wait();
// echo $res->getBody();



}

function etsy_upload_listing_file($shop_id,$token,$listing_id){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->asForm()
    ->post('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings/'.$listing_id.'/files',
        [
            'file'=>2,
        ]);
    
    return json_decode($response->getBody());

}

function etsy_create_listing_translation($shop_id,$token,$listing_id,$language){

    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING'),
        'authorization'=>'Bearer '.$token
    ])->asForm()
    ->post('https://openapi.etsy.com/v3/application/shops/'.$shop_id.'/listings/'.$listing_id.'/translations/'.$language,
        [
            'title'=>2,
            'description'=>'test12313',
            'tags'=>'sdfsfsfsfsf'
        ]);
    
    return json_decode($response->getBody());

}

function etsy_findAllListingsActive($param){
    $response = Http::withHeaders([
        'x-api-key'=>env('ETSY_KEYSTRING')
    ])->asForm()
    ->get('https://openapi.etsy.com/v3/application/listings/active?keyword=aloe vera&shop_location=CA');
    
    return json_decode($response->getBody());
}