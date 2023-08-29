<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductElement;
use App\Models\Rating;
use Validator;
use DB;
use App\Models\Variation;
use App\Models\VariationList;
use App\Models\ProductUnlQuantity;
use Storage;
use App\Models\ShippingDomestic;
use App\Models\ShippingInternational;
use Auth;

class MarketplaceController extends Controller
{

    public function index(Request $req){

        $validator = Validator::make($req->all(),[
            'search' => 'string|required'
        ]);

        $products = DB::table('products')
        ->when($req->search != null,function($q) use($req){
            $q->where('name','LIKE',"%$req->search%")
            ->orWhere('price','LIKE',"%$req->search%")
            ->orWhere('tags','LIKE',"%$req->search%")
            ->orWhere('style','LIKE',"%$req->search%")
            ->orWhere('category','LIKE',"%$req->search%")
            ->orWhere('sku','LIKE',"%$req->search%");
            searchQuery($req);
        })
        ->when($req->pet_friendly != null,function($q) use ($req){
            $q->where('pet_friendly',$req->pet_friendly);
        })
        ->when($req->poisonous != null,function($q) use ($req){
            $q->where('poisonous',$req->poisonous);
        })
        ->when($req->edible != null,function($q) use ($req){
            $q->where('edible',$req->edible);
        })
        ->when($req->cycle != null,function($q) use ($req){
            $q->where('cycle',$req->cycle);
        })
        ->when($req->sun_exposure != null,function($q) use ($req){
            $q->where('sun_exposure',$req->sun_exposure);
        })
        ->when($req->fruiting_season != null,function($q) use ($req){
            $q->where('fruiting_season',$req->fruiting_season);
        })
        ->when($req->flowering_season != null,function($q) use ($req){
            $q->where('flowering_season',$req->flowering_season);
        })
        ->when($req->sort != null,function($q) use ($req){
            if($req->sort == "high"){
                $q->orderByDesc('price');
            }else if($req->sort == "low"){
                $q->orderBy('price');
            }elseif($req->sort == "best seller"){
                $q->orderBy('sold');
            }
        })
        ->when($req->soil != null,function($q) use ($req){
            for ($i=0; $i < count(json_decode($req->soil)); $i++) { 
                $q->where('soil','like','%'.$req->soil[$i].'%');
            }
        })
        ->when($req->location != null,function($q) use ($req){
            for ($i=0; $i < count(json_decode($req->location)); $i++) { 
                $q->where('suitable_location','like','%'.$req->location[$i].'%');
            }
        })
        ->when($req->color != null,function($q) use ($req){
            for ($i=0; $i < count(json_decode($req->color)); $i++) { 
                $q->where('color','like','%'.$req->color[$i].'%');
            }
        })
        ->when($req->all() ,function($q) {
            $q->leftJoin('product_details','products.id','=','product_details.product_id')
            ->select('products.*','product_details.cycle','product_details.width','product_details.height','product_details.watering','product_details.sun_exposure','product_details.origin','product_details.color','product_details.pet_friendly','product_details.poisonous','product_details.edible','product_details.suitable_location','product_details.maintenance','product_details.growth_rate','product_details.flowering_season','product_details.fruiting_season','product_details.fertilizer','product_details.humidity','product_details.soil','product_details.hardiness','product_details.pruning');
        })
        ->get();


        if ($req->show_zone != null) {
            $products = $products->where('hardiness','!=',null);
            for($i=0; $i < $products->count(); $i++){
                $products[$i]->zmin = floor(json_decode($products[$i]->hardiness,true)['min']);
                $products[$i]->zmax = floor(json_decode($products[$i]->hardiness,true)['max']);
            }

            $products = $products->where('zmin','<=',$req->zmax)->where('zmax','>=',$req->zmin);
        }

        if ($req->shipping == 'free') {

            for ($i=0; $i < $products->count(); $i++) { 
                $products[$i]->shipping = check_if_free_shipping($products[$i]->shippingMethod);
            }
                
            $products = $products->where('shipping',true);

        }

        $count = ceil($products->count()/24);

        $all = $products->pluck('id');

        $products = $products->take(24);

        product_details($products);
        
        return view('marketplace.marketplace',['products'=>$products,'count'=>$count,'all'=>$all,'request'=>$req->input()]);

    }

    public function ajax(Request $req){

        if ($req->ajax()) {

            $products =  DB::table('products')
            ->whereIn('id',$req->product)->get();

            product_details($products);

            return view('marketplace.ajax_marketplace',['products'=>$products]);

        }

    }

    public function like($id){

        $product = Product::where('id',$id)->first();

        if (!\Cookie::get('like'.$product->sku.Auth::id()) ) {
            \Cookie::queue('like'.$product->sku.Auth::id(), true, 60 * 24 * 7);
            $element = ProductElement::where('product_id',$id)->increment('likes');
            $element = ProductElement::where('product_id',$id)->first();
            return $element->likes;
        }else{
            return response()->json(['status'=>'error','message'=>'Product already liked'],400);
        }

        return response()->json(['status'=>'error','message'=>'Error. Please try again'],400);
        
    }

    private function recently_viewed($id){

        if (request()->cookie('recently_viewed')) {

            $array = json_decode(request()->cookie('recently_viewed'));

            if (!in_array($id,$array)) {
                array_unshift($array,$id);

                // if theres more then 5 in array, kick the last one out
                if (count($array) > 10) {
                    array_pop($array);
                }

                // Set for a year
                \Cookie::queue('recently_viewed',json_encode($array), time() + 60 * 60 * 24 * 365);
            }

        }else{
            $array = [$id];
            // Set for a year
            \Cookie::queue('recently_viewed',json_encode($array), time() + 60 * 60 * 24 * 365);
        }

    }

    public function show($sku){

        $product = Product::where('sku',$sku)
        ->leftJoin('product_details','products.id','=','product_details.product_id')
        ->leftJoin('product_elements','products.id','=','product_elements.product_id')
        ->select('products.*','product_details.cycle','product_details.width','product_details.height','product_details.watering','product_details.sun_exposure','product_details.origin','product_details.color','product_details.pet_friendly','product_details.poisonous','product_details.edible','product_details.suitable_location','product_details.maintenance','product_details.growth_rate','product_details.flowering_season','product_details.fruiting_season','product_details.fertilizer','product_details.humidity','product_details.soil','product_details.hardiness','product_details.pruning','product_elements.description','product_elements.seen','product_elements.likes','product_elements.sold')
        ->first();

        try{
        
        $product->soil = json_decode($product->soil,true);
        $product->suitable_location = json_decode($product->suitable_location,true);
        $product->color = json_decode($product->color,true);
        $product->hardiness = json_decode($product->hardiness,true);

        $product->seen();

        $this->recently_viewed($product->id);

        $product->store = get_store_by_id($product->store_id)->first();

        $product->store->address = get_store_address_by_id($product->store_id)->first();
        
        get_store_rating($product->store,$product->store_id);
        
        get_store_3rd_party_rating($product->store,$product->store_id);

            if ($product) {

                $product->category = explode('<span class="px-2 text-sm icon-play3"></span>', $product->category);

                $unl_quantity = ProductUnlQuantity::where('id',$product->id)->first();

                $images=[];

                foreach(Storage::disk('public')->files($product->image.'thumbnail') as $image){

                    $images[] = Storage::disk('public')->url($image);

                }

                $product->images = $images;

                $product->domestic = ShippingDomestic::where('id',$product->shippingMethod)->first();

                $product->international = ShippingInternational::where('shipping_id',$product->shippingMethod)->get();

                $product->shipping = check_if_free_shipping($product->shippingMethod);

                // if (env('VARIATION') == TRUE) {
                
                //     $variation = variation::where('variations.product_id',$product->id)->get(['id','product_id','name']);

                //     if (count($variation) > 0) {
                        
                //         $product->variation = $variation;

                //         $range = [];

                //         foreach ($variation as $key => $value) {
                //             $product->variation[$key]->list = VariationList::where('variation_id',$value->id)->get(['id','variation_id','image','name','quantity','price']);

                //             foreach($product->variation[$key]->list as $list){

                //                 $range[] = $list->price;

                //             }

                //         }

                //     }

                //     if(isset($range)){
                //         sort($range);
                //         $product->price_range = $range;
                //     }

                // }

                return view('marketplace.product',['product'=>$product,'unl_quantity'=>$unl_quantity]);

            }else{

                return redirect(url('marketplace'));

            }

        } catch (\Exception $e) {
            dd($e);
            return redirect('marketplace')->withErrors($e->getMessage());
        }

    }

}
