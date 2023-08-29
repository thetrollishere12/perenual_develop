<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EtsyAccount;
use Illuminate\Support\Facades\Http;
use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;

class EtsyLoginController extends Controller
{
    
    public function redirect(Request $req){

        if (isset($req->link)) {
            session()->put('redirect_link',$req->link);
        }

        return Socialite::driver('etsy')->scopes(['address_r','email_r','listings_r','feedback_r','profile_r','transactions_r','listings_d','listings_w','shops_r'])->redirect();

    }

    public function success(){

        try {

            $oauth = Socialite::driver('etsy')->user();

            // Get SHOP DETAILS

            $decode = etsy_get_shop_by_userid($oauth->token,$oauth->id);

            EtsyAccount::updateOrCreate([
                'user_id'=>$oauth->id,
                'userId'=>auth()->user()->id
            ],[
                'email' => $oauth->email,
                'userId' => auth()->user()->id,
                'bearer_token' => $oauth->token,
                'refresh_token' => $oauth->refreshToken,
                'expires_in' => Carbon::now()->addSeconds($oauth->expiresIn),
                'user_id' => $oauth->id,
                'shop_id' => $decode->shop_id,
                'shop_name' => $decode->shop_name,
                'shop_url' => $decode->url,
                'shop_icon' => $decode->icon_url_fullxfull,
                'shop_transaction' => $decode->transaction_sold_count,
                'review_count' => ($decode->review_count)?$decode->review_count:0,
                'review_average' => ($decode->review_count)?$decode->review_average:0
            ]);

            custom_etsy_extract_shipping();

        }catch(\Exception $e){
            
        }


        if (session('redirect_link')) {
            return redirect()->intended(session('redirect_link'));
        }else{
            return redirect()->intended('/');
        }


    }

}
