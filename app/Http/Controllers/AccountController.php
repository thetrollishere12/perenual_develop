<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Rating;

use Auth;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreSocialMedia;
use App\Models\MyPlant;

class AccountController extends Controller
{

    public function shop($id,$name){

        try{

            $shop = Store::where('id',$id)->where('name',$name)->first();

            $user = user_details($shop->user_id);

            $shop->country_name = country_code_to_string($shop->country);

            get_store_rating($shop,$shop->id);

            $products = Product::where('products.store_id',$shop->id)->paginate(20);

            $shop->socialMedia = StoreSocialMedia::where('store_id',$shop->id)->get();

            foreach ($products as $key => $value) {

                $products[$key]->shipping = check_if_free_shipping($value->shippingMethod);

            }

            return view('shop.profile',['user'=>$user,'products'=>$products,'shop'=>$shop]);

        }catch(\Exception $e){
            // dd($e);
            return redirect('marketplace');
        }
        
    }

    public function user($id){

        try{

            $user = user_details($id);

            $user->shop = get_store_by_userId($user->id)->first();

            $user->favorites = Favorite::where('user_id',$user->id)->leftJoin('products','favorites.sku','=','products.sku')->get();

            $user->plants = MyPlant::where('user_id',$user->id)->get();

            return view('shop.user',['user'=>$user]);

        }catch(\Exception $e){
            return redirect('marketplace');
        }

    }

    public function user_plant($id,$plant_id){

        $user = user_details($id);

        $user->plant = MyPlant::where('user_id',$user->id)->where('plant_id',$plant_id)->first();

        return view('shop.plant',['user'=>$user]);

    }

}
