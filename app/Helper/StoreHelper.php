<?php

use App\Models\Store;
use App\Models\StoreSocialMedia;
use App\Models\Product;
use App\Models\Rating;
use App\Models\PayoutAccount;
use App\Models\PayoutExternalAccount;
use App\Models\PaypalExternalAccount;


use App\Models\ShopAddress;

use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;
use Symfony\Component\Intl\Currencies;

use App\Models\BankBalance;

use App\Models\EtsyAccount;
use App\Models\User;

function valid_store(){

	$store = Store::where('user_id',Auth::id())->get();

    if (count($store) > 0) {
        return true;
    }else{
        return false;
    }

}

function get_store_3rd_party_rating($store,$id){

    $store_f = Store::where('id',$id)->first();
    $etsy = EtsyAccount::where('userId',$store_f->user_id)->first();
    if ($etsy) {
        $store->third_party_ratings_count = $etsy->review_count;
        $store->third_party_ratings = $etsy->review_average;
    }


}

function get_store_rating($store,$id){

    $ratings = Rating::where('store_id',$id)->get('ratings');
    $store->ratings_count = $ratings->count();
    $store->ratings = $ratings->average('ratings');

    return $store;
}

function get_store(){
    return Store::where('user_id',Auth::id())->get();
}

function get_store_with_element(){
    return Store::where('user_id',Auth::id())->leftjoin('store_elements','stores.id','=','store_elements.store_id')->get();
}

function get_store_currency(){
    return Currencies::getSymbol(Store::where('user_id',Auth::id())->first()->currency);
}

function get_payout(){
    return PayoutAccount::where('user_id',Auth::id())->first();
}

// function get_payout_by_id($id){
//     return Store::where('stores.id',$id)->leftjoin('payout_accounts','stores.user_id','=','payout_accounts.user_id')
//     ->select('stores.*','payout_accounts.account_number as account_number','payout_accounts.id as account_id')
//     ->get();
// }

function get_payout_external(){

    $account = get_payout();

    if (!$account) {
        return collect([]);
    }

    return PayoutExternalAccount::where('account_id',$account->id)->get();

}

// function get_dual_payout_external(){

//     $stripe = PayoutAccount::where('user_id',Auth::id())
//     ->leftJoin('payout_external_accounts','payout_accounts.id','=','payout_external_accounts.account_id')
//     ->select('payout_accounts.user_id as user_id','payout_accounts.payment_method as payment_method','payout_accounts.account_number as account_number','payout_external_accounts.*')
//     ->get();

//     $paypal = PayoutExternalAccount::where('user_id',Auth::id())->get();

//     return $stripe->concat($paypal);

// }

function get_store_by_id($id){
    return Store::where('id',$id)->get();
}

function get_store_by_userId($id){
    return Store::where('user_id',$id)->get();
}

function get_store_address_by_id($id){
    return ShopAddress::where('store_id',$id)->get();
}

// Status

// function valid_active_store(){

//     $store = Store::where('user_id',Auth::id())->where('status',true)->get();

//     if (count($store) > 0) {
//         return true;
//     }else{
//         return false;
//     }

// }

// function get_active_store(){
//     return Store::where('user_id',Auth::id())->where('status',true)->get();
// }

// function activate_store(){
//     Store::where('user_id',Auth::id())->update([
//         'status'=>true
//     ]);
// }

// function deactivate_store(){
//     Store::where('user_id',Auth::id())->update([
//         'status'=>false
//     ]);
// }

// Products

function get_product($sku){

    $store = get_store()->first();

    return Product::where('store_id',$store->id)->where('sku',$sku)->get();

}

function get_products(){

    $store = get_store()->first();

    return Product::where('store_id',$store->id)->get();

}

function get_products_paginate($paginate){

    $store = get_store()->first();

    return Product::where('store_id',$store->id)->paginate($paginate);

}

function get_domestic_shipping(){

    $store = get_store()->first();

    return ShippingDomestic::where('store_id',$store->id)->get();

}

function get_shipping(){

    $domestics = get_domestic_shipping();

    foreach ($domestics as $key => $domestic) {
        $domestic->international = ShippingInternational::where('shipping_id',$domestic->id)->get();
    }

    return $domestics;  

}

function get_domestic_shipping_id($id){

    $domestics = ShippingDomestic::where('id',$id)->get();

    return $domestics;  
}

function get_shipping_id($id){

    $domestics = get_domestic_shipping_id($id);

    foreach ($domestics as $key => $domestic) {
        $domestic->international = ShippingInternational::where('shipping_id',$domestic->id)->get();
    }

    return $domestics;  

}


function product_details($products){

    foreach ($products as $key => $value) {
    
        $products[$key]->store = get_store_by_id($value->store_id)->first();

        if ($products[$key]->store) {
            get_store_rating($products[$key]->store,$value->store_id);
            get_store_3rd_party_rating($products[$key]->store,$value->store_id);
            $products[$key]->shipping = check_if_free_shipping($value->shippingMethod);
        }else{
            $products->forget($key);
        }

    }

}


function check_if_free_shipping($id){
    $shipping = ShippingDomestic::where('id',$id)->get();

    $domestic = $shipping->where('origin',session('country') ? : env('CASHIER_COUNTRY'))->first();

    if ($domestic) {
        if ($domestic->cost == 0.00 && $domestic->free_shipping == true) {
            return true;
        }
    }

    $international = ShippingInternational::where('shipping_id',$id)->whereIn('origin',[session('country') ? : env('CASHIER_COUNTRY'),'Everywhere'])->get();

    for ($i=0; $i < $international->count(); $i++) { 
        
        if ($international[$i]) {

            if ($international[$i]->cost == 0.00 && $international[$i]->free_shipping == true) {
                return true;
            }
        }

    }
    return false;
}

function get_products_with_shipping(){

    $store = get_store()->first();

    return Product::where('products.store_id',$store->id)->leftJoin('shipping_domestics','products.shippingMethod','=','shipping_domestics.id')->select('products.*','shipping_domestics.name','shipping_domestics.cost','shipping_domestics.additional_cost')->get();

}   

function get_social_media(){

    $store = get_store()->first();

    return StoreSocialMedia::where('store_id',$store->id)->get();

}


// Bank Balance

function create_bank_balance($id){

    if ($id != null) {
        $store = get_store_by_id($id)->first();
    }else{
        $store = get_store()->first();
    }

    $balance = new BankBalance;
    $balance->store_id = $store->id;
    $balance->type = 'created';
    $balance->currency = $store->currency;
    $balance->save();
    sleep(1);
    return $balance;
}

function get_bank_balance(){
    $store = get_store()->first();
    $bank = BankBalance::where('store_id',$store->id)->latest()->get();

    if ($bank->count() == 0) {
        $bank = create_bank_balance(null);
    }

    return $bank;
}

function get_bank_balance_id($id){
    $bank = BankBalance::where('store_id',$id)->latest()->get();

    if ($bank->count() == 0) {
        $bank = create_bank_balance($id);
    }

    return $bank;
}

function bank_balance_timestamp($id,$ref,$type,$amount){

    $current = get_bank_balance_id($id)->first();


    $balance = new BankBalance;
    $balance->store_id = $current->store_id;
    $balance->ref_number = $ref;
    $balance->type = $type;
    $balance->currency = $current->currency;
    ($amount > 0)? $balance->debit = $amount : $balance->credit = $amount;
    $balance->balance = $current->balance+$amount;
    $balance->save();

    return $balance;

}