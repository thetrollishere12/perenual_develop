<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Str;
use App\Models\Store;

use App\Models\Product;
use App\Models\ProductUnlQuantity;
use App\Models\Variation;
use App\Models\VariationList;
use App\Models\Rating;

use App\Models\ShippingDomestic;

use App\Models\PayoutAccount;

use App\Models\BankBalance;

use Storage;

class AccountShopController extends Controller
{
    
    public function shop(){

        $shop = get_store_with_element()->first();

        get_store_rating($shop,$shop->id);
        get_store_3rd_party_rating($shop,$shop->id);
        
        $socialMedia = get_social_media();

        $products = get_products_paginate(20);

        $shop->country_name = country_code_to_string($shop->country);

        foreach ($products as $product) {

            $product_ratings = Rating::where('sku',$product->sku)->get();

            $product->ratings = $product_ratings->avg('ratings');

            $product->ratings_count = $product_ratings->count();

            $product->shipping = ShippingDomestic::where('id',$product->shippingMethod)->first();

        }

        return view('profile.shop.shop.index',['shop'=>$shop,'socialMedia'=>$socialMedia,'products'=>$products,'user'=>Auth::user()]);

    }

    public function show(){

        return view('profile.shop.shop.edit');

    }


    public function update(Request $req){

        $this->validate($req, [
            'name' => 'required|string|min:5|max:100',
            'about' => 'string|max:10000|nullable',
            'return_exchange'=>'required',
            'return_exchange_policy' => 'max:2000',
            'social' =>'array'
        ]);

        try {

        $store = get_store()->first();

        $count = Store::where('name',$req->name)->where('id','!=',$store->id)->get()->count();

        if ($count > 0) {
            return back()->withErrors(['Store name already exist']);
        }

        $store->update([
            'name'=>$req->name,
        ]);

        StoreElement::where('store_id',$store->id)->update([
            'about'=>$req->about,
            'return_exchange'=>$req->return_exchange,
            'return_exchange_policy'=>$req->return_exchange_policy
        ]);

        $mediaType = ['facebook','pinterest','youtube','whatsapp','instagram','tiktok','discord'];

        foreach ($req->social as $key) {
            
            $media = new StoreSocialMedia;
            $media->store_id = $store->id;

            foreach($mediaType as $type){

                if(str_contains(Str::lower($key),$type) == true){

                    $exist = StoreSocialMedia::where('store_id',$store->id)->where('platform',$type)->get();

                     if (count($exist) == 0) {
                        
                        $media->platform = $type;
                        $media->url = Str::lower($key);
                        $media->save();

                    }

                }

            }

        }

        return redirect(url('user/shop'));

        }catch(\Exception $e){
            return back()->withErrors($e->getMessage());
        }

    }

    public function get_started(){

        return view('profile.shop.screener.start');

    }


    public function payout(Request $req){

        if(!get_payout() && !valid_store()){

        $json = json_decode(Storage::disk('local')->get('json/payout_country.json'), true);

        return view('profile.shop.screener.payout',['json'=>$json]);

        }elseif(!get_payout() && valid_store()){
            return redirect('user/shop/payout');
        }else{
            return redirect('shop/setup/info');
        }

    }


    public function info(){

        if (get_payout() && !valid_store()) {

            return view('profile.shop.screener.info');

        }elseif(get_payout() && valid_store()){

            return redirect('user/shop/product/create');

        }else{

            return redirect('shop/setup/payout');

        }

    }


    public function complete_shop(Request $req){

        if (!get_payout() || !valid_store()) {
            return redirect('shop/setup/payout');
        }

        return view('profile.shop.screener.complete-shop');

    }


    public function transaction_history(){

        $store = get_store()->first();

        $logs = BankBalance::where('store_id',$store->id)->get();

        return view('profile.shop.transaction',["logs"=>$logs->sortBy('created_at')]);

    }


}
