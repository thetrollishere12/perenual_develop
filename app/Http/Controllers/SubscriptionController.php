<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

use Stripe\Stripe;
use Auth;
use Laravel\Cashier\PaymentMethod;
use App\Models\User;

use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

use App\Models\PaypalSubscription;
use App\Models\PaypalSubscriptionItem;
use Carbon\Carbon;
use GuzzleHttp\Client;

class SubscriptionController extends Controller
{
    
    public function pricing(){
        return view('subscription.pricing');
    }

    public function identify_pricing(){
        return view('subscription.identify.pricing');
    }

    public function identify_upgrade(Request $req){

        try{

        $json = json_decode(Storage::disk('local')->get('json/subscription_identify.json'), true); 
        
        foreach ($json as $value) {

            if ($value["plan_id"] == $req->plan_id) {
                $plan = $value;
                $req->type = $value['type'];
            }

        }

        if (user_is_subscribed_to($plan['name'])->count() > 0) {
            return redirect('subscription-api-pricing/identify');
        }

        return view('subscription.identify.upgrade',["plan"=>$plan,"req"=>$req]);

        }catch(\Exception $e){
            return redirect('subscription-api-pricing/identify');
        }

    }

    public function upgrade(Request $req){

        try{

        $json = json_decode(Storage::disk('local')->get('json/subscription.json'), true); 

        foreach ($json as $value) {

            if ($value["plan_id"] == $req->plan_id) {
                $plan = $value;
                $req->type = $value['type'];
            }

        }

        if (user_is_subscribed_to($plan['name'])->count() > 0) {
            return redirect('subscription-api-pricing');
        }

        return view('subscription.upgrade',["plan"=>$plan,"req"=>$req]);

        }catch(\Exception $e){
            return redirect('subscription-api-pricing');
        }

    }

    public function change(Request $req){

        $json = json_decode(Storage::disk('local')->get('json/subscription.json'), true); 

        foreach ($json as $value) {

            if ($value["plan_id"] == $req->plan_id) {
                $plan = $value;
                $req->type = $value['type'];

                $subscription = user_is_subscribed_type('subscription')->first();
        
                if ($subscription->name == $plan['name'] || $subscription->name == $plan['name']) {
                    return redirect(url('subscription-api-pricing'));
                }

            }

        }

        return view('subscription.change',["plan"=>$plan,"req"=>$req]);
    }




    public function stripe_payment_subscription_identify(Request $req){

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = $req->user();

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($req->paymentMethod);

        $json = json_decode(Storage::disk('local')->get('json/subscription_identify.json'), true); 

        foreach ($json as $value) {
            if ($value["plan_id"] == $req->plan_id) {
                $name = $value["name"];
            }
        }

        auth()->user()->newSubscription($name)
        ->meteredPrice($req->plan_id)
        ->create($req->paymentMethod,[
            'email'=>$user->email
        ]);

        Subscription::where('user_id', '=', Auth::id())->update([
            'payment_method'=>'Stripe'
         ]);

        return redirect('user/developer');

    }


    public function stripe_payment_subscription(Request $req){

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = $req->user();

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($req->paymentMethod);

        $json = json_decode(Storage::disk('local')->get('json/subscription.json'), true); 

        foreach ($json as $value) {
            if ($value["plan_id"] == $req->plan_id) {
                $name = $value["name"];
            }
        }

        auth()->user()->newSubscription($name, $req->plan_id)->create($req->paymentMethod,[
            'email'=>$user->email
        ]);

        Subscription::where('user_id', '=', Auth::id())->update([
            'payment_method'=>'Stripe'
         ]);

        return redirect('user/developer');

    }

    public function change_subscription(Request $req){

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $json = json_decode(Storage::disk('local')->get('json/subscription.json'), true); 

        foreach ($json as $value) {
            if ($value["plan_id"] == $req->plan_id) {
                $plan = $value;
            }
        }

        $subscription = user_is_subscribed_type('subscription')->first();

        if ($subscription) {
            
            if ($subscription->payment_method == "Stripe") {
                
                Auth::user()->subscription(Auth::user()->subscriptions()->active()->get()->first()->name)->swapAndInvoice($plan['plan_id']);
                Auth::user()->subscriptions()->active()->get()->first()->update(["name"=>$plan['name']]);

            }elseif($subscription->payment_method == "Paypal") {
                
                $bearer_token = paypal_bearer_token();

                $revised = paypal_subscription_revise($subscription->paypal_id,$bearer_token,$plan['paypal_plan_id']);

                PaypalSubscription::where('paypal_id',$subscription->paypal_id)->update([
                    'name'=>$value["name"],
                    'paypal_plan'=>$revised->plan_id
                ]);

                PaypalSubscriptionItem::where('paypal_id',$subscription->paypal_id)->update([
                    'paypal_product'=>$revised->plan_id,
                    'paypal_plan'=>$revised->plan_id
                ]);

            }

            return redirect('user/developer');

        }else{

        }
        

    }

    // Paypal

    public function paypal_payment_subscription(Request $req){

        $bearer_token = paypal_bearer_token();

        $data = paypal_subscription($req->sub_id,$bearer_token);

        if ($data->status === 'ACTIVE' && $req->sub_id === $data->id) {

            User::where('id', '=', Auth::id())->update([
                'paypal_id'=>$data->subscriber->payer_id,
                'paypal_email'=>$data->subscriber->email_address
            ]);

            $subscription = new PaypalSubscription;
            $subscription->user_id = Auth::id();
            $subscription->name = $req->name;
            $subscription->paypal_id = $data->id;
            $subscription->paypal_status = $data->status;
            $subscription->paypal_plan = $data->plan_id;
            $subscription->quantity = $data->quantity;
            $subscription->trial_ends_at = null;
            $subscription->ends_at = null;
            $subscription->payment_method = "Paypal";
            $subscription->save();


            $item = new PaypalSubscriptionItem;
            $item->subscription_id = $subscription->id;
            $item->paypal_id = $data->id;
            $item->paypal_product = $data->plan_id;
            $item->paypal_plan = $data->plan_id;
            $item->quantity = $data->quantity;
            $item->save();

            return response()->json(['message' => 'Subscribed'],200);

        }else{

            return response()->json(['message' => 'There was an error with subscription'],404);

        }

    }







    public function custom_upgrade(Request $req){

        $plan = [
           "name" => "premium api", 
           "type" => "monthly", 
           "currency" => "usd", 
           "cost" => "$9.99/mo", 
           "actual_cost" => "9.99", 
           "plan_id" => "price_1MW67IL0y3BaIHedWJDvYNqg", 
           "paypal_plan_id" => "P-1R3746012Y4688002MPRVZWY", 
           "image" => "olive-tree.png", 
           "benefits" => [
                 "Access to premium API", 
                 "Access to all species(1-10000+) API", 
                 "Access to fungus API *Coming Soon", 
                 "Access to plant disease API *Coming Soon" 
              ], 
           "bandwidth" => [
                "type" => "Minute", 
                "limit" => 900 
             ] 
        ];

       try{

        if (user_is_subscribed_to($plan['name'])->count() > 0) {
            return redirect('subscription-api-pricing');
        }

        return view('subscription.custom_upgrade',["plan"=>$plan,"req"=>$req]);

        }catch(\Exception $e){
            return redirect('subscription-api-pricing');
        }

    }



    public function stripe_payment_subscription_custom(Request $req){

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = $req->user();

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($req->paymentMethod);

        $name = "premium api";

        auth()->user()->newSubscription($name, $req->plan_id)->create($req->paymentMethod,[
            'email'=>$user->email
        ]);

        Subscription::where('user_id', '=', Auth::id())->update([
            'payment_method'=>'Stripe'
         ]);

        return redirect('user/developer');

    }



}
