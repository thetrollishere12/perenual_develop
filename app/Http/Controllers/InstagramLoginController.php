<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\InstagramConnectedAccount;

class InstagramLoginController extends Controller
{
    
    public function redirect(Request $req){

        if (isset($req->link)) {
            session()->put('redirect_link',$req->link);
        }

        return Socialite::driver('instagrambasic')->scopes(['user_profile','user_media'])->redirect();

    }


    public function success(){

        $platformUser = Socialite::driver('instagrambasic')->user();

        $token = ig_b_access_token($platformUser->token);

        InstagramConnectedAccount::create([
            'user_id'=>Auth::user()->id,
            'account_id'=>$platformUser->id,
            'nickname'=>$platformUser->nickname,
            'name'=>$platformUser->name,
            'email'=>$platformUser->email,
            'user'=>$platformUser->user,
            'attributes'=>$platformUser->attributes,
            'token'=>$token->access_token,
            'refreshToken'=>$platformUser->refreshToken,
            'expiresIn'=> Carbon::now()->addSeconds($token->expires_in)
        ]);
        
        if (session('redirect_link')) {
            return redirect()->intended(session('redirect_link'));
        }else{
            return redirect()->intended('/');
        }

    }



}
