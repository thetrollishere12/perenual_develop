<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payout;
use App\Models\PayoutExternalAccount;
use App\Models\PayoutHistory;
use Auth;
use Carbon\Carbon;
use App\Models\PaypalExternalAccount;

use App\Models\OrderProduct;
use App\Models\Product;

use App\Models\PayoutAccount;

use Redirect;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $balance = get_bank_balance()->first();

        $payout = get_payout();

        return view('profile.shop.payout_setting',['balance'=>$balance,'payout'=>$payout]);
    }

    public function verification_documents(Request $req){

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $payout = get_payout();

        $this->validate($req, [
            'front' => 'required|file',
            'back' => 'nullable|file'
        ]); 

        try {

        $front = fopen($req->file('front')->getRealPath(), 'r');
        $back = fopen($req->file('back')->getRealPath(), 'r');
        
        $front_file = \Stripe\File::create([
          'purpose' => 'identity_document',
          'file' => $front
        ]);

        $back_file = \Stripe\File::create([
          'purpose' => 'identity_document',
          'file' => $back
        ]);

        \Stripe\Account::update($payout->account_number,
          ['individual' => [
            'verification' => [
                'document' => [
                    "front"=>$front_file->id,
                    "back"=>$back_file->id
                ]
            ],
        ]]
        );

        return back()->with('success','Verification document submitted');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_external(Request $req)
    {

        $this->validate($req, [
            'token' => 'required'
        ]);            

        try {

            $payout = get_payout();

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            
            $external = \Stripe\Account::createExternalAccount($payout->account_number,
              ['external_account' => $req->token]
            );

            $methods = PayoutExternalAccount::where('account_id',$payout->id)->where('default_method','default')->get();

            $setting = new PayoutExternalAccount;
            if ($methods->count() == 0) {
               $setting->default_method = "default";
            }
            $setting->account_id = $payout->id;
            $setting->bank_id = $external->id;
            $setting->bank_name = $external->bank_name;
            $setting->bank_last4 = $external->last4;
            $setting->save();

            return back()->with('success','Bank account added');

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }

    }

    public function add_Account(Request $req){

        $req->flash();

        $this->validate($req, [
                'first_name' => 'required|max:100',
                'last_name' => 'required|max:100',
                'day' => 'numeric|required|min:1|max:31',
                'month'=>'numeric|required|min:1|max:12|',
                'year' => 'numeric|min:'.date('Y', strtotime('-150 years')).'|max:'.date('Y'),
                'line1' => 'required|string|max:100',
                'line2' => 'nullable|string|max:100',
                'country' => 'required|max:100',
                'state_county_province_region' => 'required',
                'city' => 'required|max:100',
                'postal_zip' => 'required|max:100',
                'phone' => 'required|max:20'
            ]);

        try{

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                
            $account = \Stripe\Account::create([
              'country' => $req->country,
              'type' => 'custom',
              'business_type'=> 'individual',
              'business_profile' => [
                'mcc' => 5261,
                'url' => env('APP_URL')
              ],
              'capabilities' => [
                'card_payments' => [
                  'requested' => true,
                ],
                'transfers' => [
                  'requested' => true,
                ],
              ],
              'tos_acceptance' => [
                'date' => time(),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
              ],
              'individual'=>[
                'dob'=>[
                    "day"=> $req->day,
                    "month"=> $req->month,
                    "year"=> $req->year
                ],
                'email'=>Auth::user()->email,
                'first_name'=>$req->first_name,
                'last_name'=>$req->last_name,
                'phone'=>$req->phone,
                'address'=>[
                    "city"=> $req->city,
                    "country"=> $req->country,
                    "line1"=> $req->line1,
                    "line2"=> $req->line2,
                    "postal_code"=> $req->postal_zip,
                    "state"=> $req->state_county_province_region
                ]
              ],
              'settings'=>[
                'payouts'=>[
                    'debit_negative_balances' => true
                ]
              ]
            ]);

            $payout = new PayoutAccount;
            $payout->user_id = Auth::id();
            $payout->payment_method = 'Stripe';
            $payout->account_number = $account->id;
            $payout->save();

        return Redirect('user/shop/payout')->with('success','Details saved');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

    }

}
