<?php

use App\Models\Order;
use App\Models\OrderProduct;

use App\Models\ShopConfirmOrder;

use App\Models\OrderTracking;
use App\Models\CustomerOrder;
use App\Models\User;
use App\Models\Refund;
use App\Models\RefundHistory;
use App\Models\PayoutHistory;
use App\Models\Payout;
use Carbon\Carbon;

use App\Models\CurrencyRate;

// Get details for user
function user_details($id){

	$user = User::where('id',$id)->select('id','name','email','profile_photo_path')->first();
	return $user;

}

// Get order tracking for order
function get_order_tracking($order){

	$tracking = OrderTracking::where('number',$order->number)->select('courier','tracking')->first();

	return $tracking;

}

// Get products for order
function get_order_products($number){

	$products = OrderProduct::where('order_number',$number)
	->leftJoin('products','order_products.sku','=','products.sku')
	->select('order_products.*','products.id as product_id','products.image as product_image','products.default_image as product_default_image','products.name')
	->get();

	return $products;

}



// Get payment details for order
function get_order_payment_details($order){

	if ($order->payment_method == 'Stripe') {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $pi = Stripe\PaymentIntent::retrieve(['id' => $order->payment_id]);

        $method = Stripe\Charge::retrieve(['id' => $pi->latest_charge]);

    }elseif ($order->payment_method == 'Paypal') {
        
        $bearer_token = paypal_bearer_token();

        $payment = show_authorized_payment($order->payment_id,$bearer_token);

        $method = (object)[
            "shipping"=>(object)[
                "name"=> $payment->purchase_units[0]->shipping->name->full_name,
                "address"=>(object)[
                    "line1"=> $payment->purchase_units[0]->shipping->address->address_line_1,
                    "city"=> $payment->purchase_units[0]->shipping->address->admin_area_2,
                    "state"=> $payment->purchase_units[0]->shipping->address->admin_area_1,
                    "postal_code"=> $payment->purchase_units[0]->shipping->address->postal_code,
                    "country"=> $payment->purchase_units[0]->shipping->address->country_code
                ]
            ],
            "payment_method_details"=>(object)[
                "paypal"=>(object)[
                    "email_address"=> $payment->payer->email_address
                ]
            ]
        ];

    }

    return $method;

}

// ======================Order Refund===============================================

// Refunding specific amount for order
function order_refund($order,$amount){

	Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

	if ($order->payment_method == 'Stripe') {

		$stripe = Stripe\Refund::create([
			'payment_intent'=>$order->payment_details->payment_intent,
			'amount'=>stripe_num($amount*$order->currency_rate)
		]);

		$refund_details = [
			'amount' => substr_replace($stripe->amount,'.',-2,0),
			'currency' => strtoupper($stripe->currency)
		];

	}elseif($order->payment_method == 'Paypal'){

	    $bearer_token = paypal_bearer_token();
	    $payment = show_authorized_payment($order->payment_id,$bearer_token);
	    $data = (object)[
	        "value"=>$amount*$order->currency_rate,
	        "currency"=>$order->customer_currency,
	        "note"=>"Refunding Order",
	    ];

	    $paypal = refund_captured_payment($payment->purchase_units[0]->payments->captures[0]->id,$bearer_token,$data);

	    $refund_details = [
			'amount' => $paypal->amount->value,
			'currency' => $paypal->amount->currency_code
		];

	}

	$order->refund_details = $refund_details;
	
	$reversed_amount = $order->reversing_fee;

	if ($amount >= $order->remaining) {

    	Order::where('number',$order->number)->update([
            'status'=>'Cancelled',
            'status_date'=>Carbon::now()
        ]);

    	$refund = new Refund;
	    $refund->order_number = $order->number;
	    $refund->reason = $order->reason;
	    $refund->currency_rate = [];
	    $refund->reversed_fee = $order['fee_breakdown']['flat'];
	    $refund->reversed_amount = 0;
	    $refund->comment = null;
	    $refund->customer_total_reversed = 0;
	    $refund->save();

	    $order->reversing_fee += $order['fee_breakdown']['flat'];

    }

	$refund = new Refund;
    $refund->order_number = $order->number;
    $refund->reason = $order->reason;

    $refund->currency_rate = CurrencyRate::where('base_currency',$order->currency)->where('foreign_currency',$refund_details['currency'])->get(['base_currency','foreign_currency','rate'])->first();

    $refund->reversed_fee = $reversed_amount;
    $refund->reversed_amount = $amount;
    $refund->comment = $order->comment;

    $refund->customer_total_reversed = $refund_details['amount'];

    $refund->save();

	// Transfer From Shop To Platform - Amount without fees - ex 14.25
	bank_balance_timestamp($order->store_id,null,'fee_reversal',$order->reversing_fee);
	sleep(1);
	bank_balance_timestamp($order->store_id,null,'refund',-$amount);

	// Stripe\Transfer::createReversal($order->transfer_id,["amount"=>stripe_num($refund->reversed_amount)]);
	return $refund;
}

// Get refund details on order
function get_order_refund($order){

	// Get refund and combine products for image and order_product for order info on product

	$order->refund = Refund::where('refunds.order_number',$order->number)
    ->get();


    foreach ($order->refund as $key => $refund) {
    	$refund->history = RefundHistory::where('refund_histories.refund_id',$refund->id)
	    ->leftJoin('products','refund_histories.order_product_sku','=','products.sku')
	    ->leftJoin('order_products',function($join){
	        $join->on('refund_histories.order_product_sku','=','order_products.sku');
	        $join->on('refund_histories.order_number','=','order_products.order_number');
	    })
	    ->select('refund_histories.*','products.id as product_id','products.name','products.image as product_image','products.default_image as product_default_image','order_products.from_price','order_products.to_price','order_products.currency_rate',)
	    ->get();
    }

    $order->customer_reversed = $order->refund->sum('customer_total_reversed');
    $order->reversed = $order->refund->sum('reversed_amount');
    $order->remaining = $order->total-$order->refund->sum('reversed_amount');
	

}


// ====================================================================================


function get_order($number){

	$order = Order::when($number != 'all',function($q) use($number){
		$q->where('number',$number);
	})
	->leftjoin('customer_orders','orders.cus_order_id','=','customer_orders.id')
	->select('orders.*','customer_orders.user_id as user_id','customer_orders.payment_method as payment_method','customer_orders.payment_id as payment_id','customer_orders.shipping_details as shipping_details')
	->get();

	return $order;

}

function get_full_order($number){
	// Get basic order
	$order = get_order($number);

	$order[0]->fee = rounded2($order[0]->total*($order[0]->marketplace_fee_percentage/100));
	// Get Buyer ID
	$order[0]->user = user_details($order[0]->user_id);
	// Get refund history
	get_order_refund($order[0]);
	// Get product list for order
	$order[0]->order_products = get_order_products($order[0]->number);
	// Get payment details for order
	$order[0]->payment_details = get_order_payment_details($order[0]);
	// Get tracking details for order
	$order[0]->tracking = get_order_tracking($order[0]);

	if (env('LOCAL_PICKUP') == true && $order[0]->type == 'pickup') {
		$order[0]->store_address = get_store_address_by_id($order[0]->store_id)->first();
	}

	return $order;
}

// ============================ For Sellers =================================================

function get_sold_order($number){

	$order = ShopConfirmOrder::when($number != 'all',function($q) use($number){
		$q->where('shop_confirm_orders.number',$number);
	})
	->leftjoin('customer_orders','shop_confirm_orders.cus_order_id','=','customer_orders.id')
	->leftjoin('orders','shop_confirm_orders.number','=','orders.number')
	->select('shop_confirm_orders.*','customer_orders.user_id as user_id','customer_orders.payment_method as payment_method','customer_orders.payment_id as payment_id','customer_orders.shipping_details as shipping_details','orders.currency as customer_currency','orders.total as customer_total')
	->get();

	return $order;

}

function get_sold_full_order($number){
	// Get basic order
	$order = get_sold_order($number);

	$order[0]->fee = rounded2($order[0]->total*($order[0]->marketplace_fee_percentage/100));
	// Get Buyer ID
	$order[0]->user = user_details($order[0]->user_id);
	// Get refund history
	get_order_refund($order[0]);
	// Get product list for order
	$order[0]->order_products = get_order_products($order[0]->number);
	// Get payment details for order
	$order[0]->payment_details = get_order_payment_details($order[0]);
	// Get tracking details for order
	$order[0]->tracking = get_order_tracking($order[0]);

	if (env('LOCAL_PICKUP') == true && $order[0]->type == 'pickup') {
		$order[0]->store_address = get_store_address_by_id($order[0]->store_id)->first();
	}

	return $order;
}