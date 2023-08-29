<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasPaymentMethod() && !$user->defaultPaymentMethod()) {
            $pm = $user->paymentMethods()->first()->id;
            $user->updateDefaultPaymentMethod($pm);
            $user->updateDefaultPaymentMethodFromStripe();
        }
        
        $payment_methods = $user->paymentMethods();

        $default = $user->defaultPaymentMethod();

        return view('profile.user.payment-method',['payment_methods'=>$payment_methods,'default'=>$default]);
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
    public function store(Request $request)
    {

        try{

            $this->validate($request, [
                'paymentMethod' => 'required',
                'line1' => 'required|string|max:100',
                'line2' => 'nullable|string|max:100',
                'country' => 'required|max:100',
                'state_county_province_region' => 'required',
                'city' => 'required|max:100',
                'postal_zip' => 'required|max:100'
            ]);

            $user = Auth::user();

            $user->createOrGetStripeCustomer();

            $user->addPaymentMethod($request->paymentMethod,[
                "billing_details"=>[
                    'address'=>[
                        "city"=> $request->city,
                        "country"=> $request->country,
                        "line1"=> $request->line1,
                        "line2"=> $request->line2,
                        "postal_code"=> $request->postal_zip,
                        "state"=> $request->state_county_province_region
                    ],
                    // "name"=>request('billing-name')
                ]
            ]);

            if (!$user->hasDefaultPaymentMethod()) {
                $user->updateDefaultPaymentMethod($request->paymentMethod);
                $user->updateDefaultPaymentMethodFromStripe();
            }

            return back()->with('success','Card Added');

        }catch(\Exception $e){
            return back()->withErrors($e->getMessage());
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
    public function update(Request $request, $id)
    {
        if(!$id){
            return back()->withErrors(['There was an error. Please try again']);
        }

        $user = Auth::user();

        $user->createOrGetStripeCustomer();
        
        $user->updateDefaultPaymentMethod($id);
        $user->updateDefaultPaymentMethodFromStripe();

        return back()->with('success','Default card changed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if(!$id){
            return back()->withErrors(['There was an error. Please try again']);
        }

        $user = Auth::user();

        $user->createOrGetStripeCustomer();

        $user->deletePaymentMethod($id);

        if ($user->hasPaymentMethod() && !$user->hasDefaultPaymentMethod()) {
            $pm = $user->paymentMethods()->first()->id;
            $user->updateDefaultPaymentMethod($pm);
            $user->updateDefaultPaymentMethodFromStripe();
        }

        return Redirect::back();
    }
}
