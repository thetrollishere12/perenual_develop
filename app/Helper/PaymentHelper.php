<?php

use App\Helper\AppHelper;
use App\Models\CurrencyRate;
use Symfony\Component\Intl\Currencies;
use Carbon\Carbon;
use App\Models\CustomerOrder;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductUnlQuantity;
use App\Models\ProductElement;

use App\Models\User;

use App\Mail\SendOrder;
use App\Mail\SendCustomerOrder;

use App\Models\ShopConfirmOrder;

use App\Mail\OrderConfirm;

function rounded2($number){
    return number_format(round($number,2),2);
}

function stripe_num($number){
    return str_replace(array('.', ','), '' , number_format($number,2));
}

function current_currency_code(){
    if (Session('currency')) {
        return strtoupper(Session('currency'));
    }else{
        return strtoupper(env('CASHIER_CURRENCY'));
    }
}

function current_currency(){

    if (Session('currency')) {
        return Currencies::getSymbol(Session('currency'));
    }else{
        return Currencies::getSymbol(strtoupper(env('CASHIER_CURRENCY')));
    }

}

function process_order($method,$payment_id){
        
        $cus_order = new CustomerOrder;
        $cus_order->cus_order_number = random_id('CO_');
        $cus_order->user_id = (Auth::id()) ? Auth::id() : 0;
        $cus_order->shipping_details = shipping_address();
        $cus_order->payment_method = $method;
        $cus_order->payment_id = $payment_id;
        $cus_order->save();

        foreach (session('cart.shopping_cart') as $key => $cart) {

            $order_num = random_id('O_');

            $order = new Order;
            $order->cus_order_id = $cus_order->id;
            $order->store_id = $key;
            $order->number = $order_num;
            $order->currency = current_currency_code();
            $order->shipping = $cart['shipping']['convert_amount'];
            $order->discount = 0;
            $order->tax = 0;
            // $order->payout_amount = session('cart.total_cost')*(100-env("MARKETPLACE_FEE_PERCENTAGE"))/100;
            $order->type = $cart['shipping']['type'];
            

            $s_order = new ShopConfirmOrder;
            $s_order->cus_order_id = $cus_order->id;
            $s_order->store_id = $key;
            $s_order->number = $order_num;
            $s_order->currency= $cart['store']->currency;
            
            $s_order->shipping=$cart['shipping']['shipping_amount'];
            $s_order->discount=0;
            $s_order->tax=0;
            $s_order->fee_breakdown=[
                'percentage'=>env("MARKETPLACE_FEE_PERCENTAGE"),
                'flat'=>env("PAYMENT_PROCESSING_FLAT_FEE")
            ];

            
            $rate = CurrencyRate::where('base_currency',$cart['store']->currency)->where('foreign_currency',current_currency_code())->get(['base_currency','foreign_currency','rate'])->first();

            $subtotal = 0;
            $un_subtotal = 0;


            foreach ($cart['list'] as $purchase){

                $product = Product::where('id',$purchase['product']["id"])->where('sku',$purchase['product']->sku)->get()->first();
                $product['purchased_quantity'] =  $purchase['purchased_quantity'];
                $product['converted_currency'] =  current_currency_code();
                $product['converted_price'] = conversion($product->currency,$product->price,false);

                $purchases = new OrderProduct;
                $purchases->order_number = $order_num;
                $purchases->sku = $product["sku"];
                $purchases->currency_rate = $rate;
                $purchases->from_price = $product->price;
                $purchases->to_price = $product['converted_price'];
                $purchases->quantity = $product['purchased_quantity'];
                $purchases->tax = 0;
                $purchases->shipping = $purchase['shipping'];

                $purchases->store_earning = bcdiv($product->price*$product['purchased_quantity']*(100-env("MARKETPLACE_FEE_PERCENTAGE"))/100, 1, 2);
                $purchases->marketplace_fee = $product->price*$product['purchased_quantity']*env("MARKETPLACE_FEE_PERCENTAGE")/100;

                $purchases->save();

                // If theres a product limit
                $unl_quantity = ProductUnlQuantity::where('product_id',$product->id)->first();

                if(!$unl_quantity){
                    if ($product->quantity > 0) {
                        $product->decrement('quantity',$purchase['purchased_quantity']);
                    }else{
                        $product->update(['quantity',0]);
                    }

                    ProductElement::where('product_id',$product->id)->increment('sold',$purchase['purchased_quantity']);
                }

                $un_subtotal += $purchase['purchased_quantity']*$purchase['product']->price;
                $subtotal += conversion_product($product["currency"],$purchase['purchased_quantity'],$purchase['product']->price,false);

            }

            $order->subtotal = $subtotal;
            $order->total = $subtotal+$cart['shipping']['convert_amount'];

            $order->save();


            $s_order->subtotal=$un_subtotal;
            $s_order->total=$un_subtotal+$cart['shipping']['shipping_amount'];
            $s_order->marketplace_fee=(($un_subtotal+$cart['shipping']['shipping_amount'])*(env("MARKETPLACE_FEE_PERCENTAGE"))/100)+env("PAYMENT_PROCESSING_FLAT_FEE");
            $s_order->currency_rate=CurrencyRate::where('base_currency',$cart['store']->currency)->where('foreign_currency',current_currency_code())->value('rate');

            $s_order->save();


            // Add balance for bank account
            // Might have to round up
            bank_balance_timestamp($key,null,'payment',$s_order->total);
            sleep(1);
            bank_balance_timestamp($key,null,'fee',-$s_order->marketplace_fee);

        }

        return $cus_order;

}

function payment_success($data){

    $cus_order = get_customer_order($data->id);

    ProductPurchase($cus_order);

    $cus_order->order = Order::where('cus_order_id',$cus_order->id)->get();

    foreach ($cus_order->order as $key => $order) {

        // Get Store
        $order->store = get_store_by_id($order->store_id)->first();
        // Get Store User
        $order->user = user_details($order->store->user_id);
        ProductSold($order);

    }

    if (env('APP_STATUS') == "production") {
        Mail::to('brandonsanghuynh123@gmail.com')->send(new OrderConfirm($data));
    }

    redeem_promo(session('cart.coupon_code_applied.coupon_code'));

    // session()->put('order_num',$order_num);

}