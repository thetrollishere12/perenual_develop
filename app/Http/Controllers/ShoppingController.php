<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CustomerOrder;
use App\Models\OrderProduct;
use App\Models\ProductUnlQuantity;

use App\Models\Variation;
use App\Models\VariationList;
use App\Models\Store;

use App\Models\Address;

use Carbon\Carbon;
use DB;
use Auth;
use Stripe;

class ShoppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('shopping.shopping-cart');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        
        if ($req->ajax() && $req->quantity) {

            $this->validate($req, [
                'sku' => 'required',
                'quantity' => 'required',
            ]);

            $product = Product::where('sku',$req->sku)->get();

            // Prevent people fom adding to their own listing to their cart
            $store = get_store()->first();

            if ($store) {
                if ($product->where('store_id',$store->id)->count() > 0) {
                    return response()->json(['status'=>'error','message'=>'Cannot Buy Your Listing'],400);
                }
            }

            $product = $product->first();

            $store = Store::where('id',$product->store_id)->first();

            $unl_quantity = ProductUnlQuantity::where('product_id',$product->id)->first();

            $cart = session()->get('cart.shopping_cart');

            if($req->variation){

                $variation = VariationList::where('id',$req->variation)->first();

                $product->price = $variation->price;
                $product->quantity = $variation->quantity;
                $product->name = $product->name."[variation - ".$variation->name."]";
                $product->variation = true;
                $product->variation_id = $variation->id;

                if(!$cart) {
                             
                    $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['product'] = $product;
                    $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['purchased_quantity'] = $req->quantity;

                }else{

                    // // if cart not empty then check if this product exist then increment quantity
                    if(isset($cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']'])) {

                        if ($cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['purchased_quantity']+$req->quantity > $product->quantity) {
                            $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['purchased_quantity'] = $product->quantity;
                        }else{
                            $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['purchased_quantity'] = $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['purchased_quantity']+$req->quantity;
                        }
                                 
                    }else{

                        $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['product'] = $product;
                        $cart[$store->id]['list'][$product->id.'[variation='.$variation->id.']']['purchased_quantity'] = $req->quantity;

                    }

                }

            }else{

                // If first product
                if(!$cart) {
         
                    $cart[$store->id]['list'][$product->id]['product'] = $product;
                    $cart[$store->id]['list'][$product->id]['purchased_quantity'] = $req->quantity;

                }else{

                    // // if cart not empty then check if this product exist then increment quantity
                    if(isset($cart[$store->id]['list'][$product->id])) {

                        // If limited
                        if (!$unl_quantity && $cart[$store->id]['list'][$product->id]['purchased_quantity']+$req->quantity > $product->quantity) {
                            $cart[$store->id]['list'][$product->id]['purchased_quantity'] = $product->quantity;
                        // If Unlimited
                        }else{
                            $cart[$store->id]['list'][$product->id]['purchased_quantity'] = $cart[$store->id]['list'][$product->id]['purchased_quantity']+$req->quantity;
                        }
                    // If Not add new
                    }else{
                        
                        $cart[$store->id]['list'][$product->id]['product'] = $product;
                        $cart[$store->id]['list'][$product->id]['purchased_quantity'] = $req->quantity;

                    }

                }

                // Add store
                $cart[$store->id]['store'] = $store;
                // Add default shipping for type
                if (!isset($cart[$store->id]['shipping']['type'])) {
                    $cart[$store->id]['shipping']['type'] = 'shipping';
                    $cart[$store->id]['shipping']['currency'] = $product->currency;
                }

            }

            session()->put('cart.shopping_cart', $cart);

            return response()->json(['status'=>'valid','message'=>'Added To Cart'],200);

        }else{
            return response()->json(['status'=>'error','message'=>'Select Quantity'],400);
        }

    }

    public function shipping(){
        
        cart_media_ownership();
        revise_cart();
        
        return view('shopping.shipping');

    }

    public function checkout(Request $request){

        cart_media_ownership();
        revise_cart();

        // Check if coupon and if so apply
        if (session('cart.coupon_code_applied')) {   
            apply_promo(session('cart.coupon_code_applied.coupon_code'));
        }

        // Check if user provided shipping address
        if(!session('cart.shipping.address')){
            return redirect()->to('shipping');
        }

        return view('shopping.checkout');

    }

    public function stripe_payment_product(Request $req){
        
            $this->validate($req, [
                'paymentMethod' => 'required',
                'billing'=>'nullable'
            ]);
            
            // Check if everything is eligable for shipping to selected country
            foreach(session('checkoutCart.shopping_cart') as $sku => $cart) {

                foreach($cart['list'] as $list){
                    if ($list['eligable'] == false) {

                        return back()->withErrors('Product '.strtoupper($list['product']->sku).' does not ships to '.country_code_to_string(session('checkoutCart.shipping.address.country')));

                    }
                }

            }
            
            try{
            
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $token = $req->paymentMethod;

            $billing_address = null;
            if ($req->billing != null) {

                $this->validate($req,[
                    'line1' => 'required',
                    'country_code' => 'required',
                    'state_county_province_region' => 'required',
                    'city' => 'required',
                    'postal_zip' => 'required',
                ]);
                
                $billing_address = [
                    "city"=> $req->city,
                    "country"=> $req->country_code,
                    "line1"=> $req->line_1,
                    "line2"=> $req->line_2,
                    "postal_code"=> $req->postal_zip,
                    "state"=> $req->state_county_province_region
                ];

            }else{
                $billing_address=shipping_address();
            }

            $ship_address = shipping_address();

            if(Auth()->user()){

                $user = Auth()->user();

                $user->createOrGetStripeCustomer([
                    "description" => env('APP_NAME')." Customer - ".Auth::id(),
                    "address"=>$billing_address,
                    "email" => $user->email,
                    "name"=>$user->name,
                    'phone'=>session('checkoutCart.user.phone'),
                ]);
                
                // $details = subscription_details();

                $charge = $user->charge(stripe_num(session('checkoutCart.total')), $token, [
                     'currency' => current_currency_code(),
                     'description' => "Product",
                     'shipping'=>[
                        'address'=>$ship_address['address'],
                        'name'=>$ship_address['name']
                     ],
                     'receipt_email'=>env('RECEIPT_EMAIL')
                ]);

            }elseif(env('ANONYMOUS_SHOPPING') == 'TRUE' && !Auth()->user()){

                $customer = Stripe\Customer::create([
                     'payment_method' => $token,
                     'description' => env('APP_NAME')." Customer - Guest",
                     'address'=>$billing_address,
                     'email' => session('checkoutCart.user.email_address'),
                     'name'=>session('checkoutCart.user.contact_name'),
                     'phone'=>session('checkoutCart.user.phone'),
                     // 'shipping'=>$ship_address,
                ]);

                $charge = Stripe\PaymentIntent::create([
                  'amount' => stripe_num(session('checkoutCart.total')),
                  'currency' => current_currency_code(),
                  'customer' => $customer->id,
                  'payment_method'=>$token,
                  'confirm'=>true,
                  'shipping'=>[
                    'address'=>$ship_address['address'],
                    'name'=>$ship_address['name']
                  ],
                  'receipt_email'=>env('RECEIPT_EMAIL')
                ]);

            }

            $order = process_order('Stripe',$charge->id);

            payment_success($order);

            session()->put('confirmed_order',$order);
            return redirect('/thank-you');
            
            }catch(\Exception $e){
                dd($e);
                return back()->withErrors($e->getMessage());
            }

    }

    public function thank_you(Request $req){

        if (session('confirmed_order')) {
            $order = session('confirmed_order');
            session()->forget('cart');
            session()->forget('confirmed_order');
            return view('shopping.thankyou',['order'=>$order]);
        }else{

            return redirect('/');

        }

    }

}
