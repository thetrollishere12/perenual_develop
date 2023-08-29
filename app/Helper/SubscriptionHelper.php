<?php

use GuzzleHttp\Client;

use Stripe\Stripe;
use Laravel\Cashier\Subscription;
use Laravel\Cashier\SubscriptionItem;

use App\Models\PaypalSubscription;

use App\Models\User;
use Carbon\Carbon;

use App\Models\ApiCredentialKey;

function api_key_check($key){

	$ckey = ApiCredentialKey::where('key',$key)->first();

	return $ckey;

}

function is_subscribed($id){

	$paypal = PaypalSubscription::where('user_id',$id)->where('paypal_status','=','active')->where(function($query){
		$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
	})->get();

	$stripe = User::find($id)->subscriptions()->active()->get();

	$both = $paypal->merge($stripe);

	return $both;

}

function is_subscribed_type($id,$type){

	$subscriptions = json_decode(Storage::disk('local')->get('json/'.$type.'.json'), true);

    $plan_name = array_column($subscriptions, 'name');

	$paypal = PaypalSubscription::where('user_id',$id)->where('paypal_status','=','active')->whereIn('name',$plan_name)->where(function($query){
		$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
	})->get();

	$stripe = User::find($id)->subscriptions()->active()->whereIn('name',$plan_name)->get();

	$both = $paypal->merge($stripe);

	return $both;

}

function is_subscribed_to($id,$name){

	if (is_array($name)) {

		$paypal = PaypalSubscription::where('user_id',$id)->where('paypal_status','=','active')->whereIn('name',$name)->where(function($query){
			$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
		})->get();

		$stripe = User::find($id)->subscriptions()->active()->whereIn('name', $name)->get();

	}else{

		$paypal = PaypalSubscription::where('user_id',$id)->where('paypal_status','=','active')->where('name',$name)->where(function($query){
			$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
		})->get();

		$stripe = User::find($id)->subscriptions()->active()->where('name', $name)->get();

	}

	$both = $paypal->merge($stripe);

	return $both;

}

function user_is_subscribed_type($type){

	$subscriptions = json_decode(Storage::disk('local')->get('json/'.$type.'.json'), true);

    $plan_name = array_column($subscriptions, 'name');

	$paypal = PaypalSubscription::where('user_id',auth()->user()->id)->where('paypal_status','=','active')->whereIn('name',$plan_name)->where(function($query){
		$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
	})->get();

	$stripe = auth()->user()->subscriptions()->active()->whereIn('name',$plan_name)->get();

	$both = $paypal->merge($stripe);

	return $both;

}

function user_is_subscribed_to($name){

	if (is_array($name)) {
		
		$paypal = PaypalSubscription::where('user_id',auth()->user()->id)->where('paypal_status','=','active')->whereIn('name',$name)->where(function($query){
			$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
		})->get();

		$stripe = auth()->user()->subscriptions()->active()->whereIn('name', $name)->get();

	}else{

		$paypal = PaypalSubscription::where('user_id',auth()->user()->id)->where('paypal_status','=','active')->where('name',$name)->where(function($query){
			$query->where('ends_at','>',Carbon::today())->orWhere('ends_at',NULL);
		})->get();

		$stripe = auth()->user()->subscriptions()->active()->where('name', $name)->get();

	}

	$both = $paypal->merge($stripe);

	return $both;

}

function subscription_details($name,$type){

	$json = json_decode(Storage::disk('local')->get('json/'.$type.'.json'), true); 

	foreach ($json as $value) {

	    if($name != null){

	        if ($value["name"] == $name) {
	            $details = $value;
	        }

	    }else{

	        if ($value["name"] == 'personal') {
	            $details = $value;
	        }

	    }
	}

	return $details;

}

function user_is_onGracePeriod($type){

	$subscriptions = json_decode(Storage::disk('local')->get('json/'.$type.'.json'), true);

    $plan_name = array_column($subscriptions, 'name');

	$stripe = Auth::user()->subscriptions()->active()->where('ends_at','!=',NULL)->where('ends_at','>',Carbon::today())->whereIn('name',$plan_name)->get();

	$paypal = PaypalSubscription::where('user_id',auth()->user()->id)->where('paypal_status','=','active')->where('ends_at','!=',NULL)->where('ends_at','>',Carbon::today())->whereIn('name',$plan_name)->get();

	$both = $paypal->merge($stripe);

	return $both;
}

function cancel_user_subscription($type){

	$subscription = user_is_subscribed_type($type)->first();

    switch ($subscription->payment_method) {
        case 'Stripe':

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripe_name = $subscription->name;
            auth()->user()->subscription($stripe_name)->cancel();

        	break;
        case 'Paypal':

            $bearer_token = paypal_bearer_token();

            $subscription_details = paypal_subscription($subscription->paypal_id,$bearer_token);

            $subscription->update([
                'ends_at'=>Carbon::parse($subscription_details->billing_info->next_billing_time)
            ]);

            paypal_subscription_suspend($subscription_details->id,$bearer_token);

        	break;
        default:
        return response()->json(['message' => 'There was an issue with cancel_user_subscription 8331'],404);
        break;
    }
}

function resume_user_subscription($type){

	$subscription = user_is_subscribed_type($type)->first();

    switch ($subscription->payment_method) {
        case 'Stripe':

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $stripe_name = $subscription->name;

            auth()->user()->subscription($stripe_name)->resume();

        	break;
        case 'Paypal':

            $bearer_token = paypal_bearer_token();

            $subscription_details = paypal_subscription($subscription->paypal_id,$bearer_token);

            paypal_subscription_activate($subscription_details->id,$bearer_token);

            $subscription->update([
                'ends_at'=>null
            ]);

        	break;

        default:
        return response()->json(['message' => 'There was an issue with resume_user_subscription 7331'],404);
        break;
    }

}