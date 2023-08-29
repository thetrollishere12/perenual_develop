<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Storage;
use App\Models\ApiCallLog;
use App\Models\User;

class IdentityApiController extends Controller
{
    
    private static $callLimit = 20;

    private static function api_log($user_id,$key,$url){

        ApiCallLog::create([
            'user_id'=>$user_id,
            'api_key'=>$key,
            'request_uri'=>$url
        ]);

    }


    public function plantSpecies(Request $req)
    {
        $check = api_key_check($req->key);
        $subscription = is_subscribed_type($check->user_id, 'subscription_identify');
        $callLimit = self::$callLimit;

        $call = ApiCallLog::where('user_id', $check->user_id)
            ->where('request_uri', 'LIKE', '%' . 'api/identify' . '%')
            ->get();

        if ($call->count() > $callLimit && $subscription->count() == 0) {
            return response('Please Upgrade Plan - ' . url("subscription-api-pricing/identify") . '. Passed Call Limit Sorry😬', 429);
        }

        $this->api_log($check->user_id, $req->key, $req->getRequestUri());

        $url = [];

        if ($req->hasFile('file') || $req->url) {
            if ($req->hasFile('file')) {
                $url = array_map(function ($file) {
                    return $file->store('species_identify', 'public');
                }, $req->file('file'));
            }

            if ($req->url) {
                foreach ($req->url as $value) {
                    $context = stream_context_create([
                        'http' => [
                            'header' => 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'],
                        ],
                    ]);

                    $random = random_id('P-I-');
                    Storage::disk('public')->put('species_identify/' . $random . '.jpg', file_get_contents($value, true, $context));

                    $url[] = 'species_identify/' . $random . '.jpg';
                }
            }

            if (empty($url)) {
                return response('There were no images. Please try again or contact us at info@perenual.com', 404);
            }

            try {
                $check->count = 5;
                $identify = plantIdentify($url, $check);

                if ($call->count() > $callLimit) {
                    User::find($check->user_id)->subscription($subscription->first()->name)->reportUsageFor($subscription->first()->stripe_price, 1);
                }

                return $identify;
            } catch (\Exception $e) {
                return response('There was an error. Please try again or contact us at info@perenual.com', 404);
            }
        } else {
            return response('Missing images. Please try again or contact us at info@perenual.com', 404);
        }
    }

}
