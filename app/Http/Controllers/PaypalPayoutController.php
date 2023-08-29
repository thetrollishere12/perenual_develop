<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\CustomerOrder;
use App\Models\OrderProduct;

use App\Models\PayoutExternalAccount;
use App\Models\PayoutExternalAccount;
use Redirect;
use Auth;
use Carbon\Carbon;
use App\Models\Payout;
use GuzzleHttp\Client;


class PaypalPayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        try{

        $client = new Client();

        $bearer_token = paypal_bearer_token();

        $response = Http::withHeaders([
            'Content-Type'=>'application/json',
            'Authorization'=>'Bearer '.$bearer_token
        ])->get('https://api-m.sandbox.paypal.com/v1/identity/oauth2/userinfo?schema=paypalv1.1');

        $response = json_decode($response->getBody());

        $methods = get_dual_payout_external();

        $account = new PayoutExternalAccount;
        $account->store_id = get_store()->first()->id;
        if ($methods->count() == 0) {
           $setting->default_method = "default";
        }
        $account->payment_method = 'Paypal';
        $account->paypal_email = $response->emails[0]->value;
        $account->paypal_payer_id = $response->payer_id;

        $account->save();

        return Redirect::back();

        }catch(\Exception $e){
            return redirect(url('user/seller/payment-method'))->with('error','There was an Paypal error. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {   

        $store = get_payout()->first();

        $methods = PayoutExternalAccount::where('account_id',$store->account_id)->get()->concat(PayoutExternalAccount::where('store_id',$store->id)->get());

        foreach ($methods as $method) {
            $method->update(["default_method"=>NULL]);
        }

        PayoutExternalAccount::where('store_id',$store->id)->where('id',$id)->update([
            "default_method"=>"default"
        ]);

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $store = get_store()->first();

        PayoutExternalAccount::where('store_id',$store->id)->where('id',$req->id)->delete();

        return Redirect::back();

        // $selected = PayoutExternalAccount::where('seller_id',Auth::id())->where('id',$req->id)->get();
        
        // PayoutExternalAccount::where('seller_id',Auth::id())->where('id',$req->id)->delete();

        // if ($selected->first()->default_method == "default") {

        //     $methods = PayoutExternalAccount::where('seller_id',Auth::id())->get()->concat(PayoutExternalAccount::where('seller_id',Auth::id())->get());

        //     if ($methods->count() > 0) {

        //         if ($methods->first()->payment_method == "Stripe") {

        //             $update = \Stripe\Account::updateExternalAccount(
        //               $methods->first()->account_number,
        //               $methods->first()->bank_id,
        //               ['default_for_currency' => true]
        //             );

        //         }
                
        //         $methods->first()->update([
        //             "default_method"=>"default"
        //         ]);
        //     }
        // }

    }
}
