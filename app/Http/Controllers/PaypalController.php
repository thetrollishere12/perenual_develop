<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaypalController extends Controller
{

    public function paypal_payment_product(Request $req){

        // If allows guest checkout
        if(env('ANONYMOUS_SHOPPING') != 'TRUE' && !Auth::user()){
            return redirect('shopping-cart')->withErrors('Please sign in to continue');
        }

        // Check if cart exist and has products
        if(!session('cart.shopping_cart') && empty(session('cart.shopping_cart'))){
            return redirect('shopping-cart')->withErrors('Shopping cart does not exist');
        }
        
        $this->validate($req, [
            'paypal_id' => 'required|string'
        ]);

        $order = process_order('Paypal',$req->paypal_id);

        $order['payment_info'] = (object)[
            'paypal_id'=>$req->paypal_id,
            'payer_id'=>$req->customer['payer_id'],
            'paypal_email_address'=>$req->customer['email_address']
        ];

        payment_success($order);
        session()->put('confirmed_order',$order);
        return response()->json(['message' => 'Purchased'],200);

    }

}
