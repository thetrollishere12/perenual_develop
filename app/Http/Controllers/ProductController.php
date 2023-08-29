<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductUnlQuantity;
use App\Models\Variation;
use App\Models\VariationList;

use Auth;
use Storage;

use App\Models\Store;
use App\Models\ProductDimension;

use App\Models\ProductDetail;
use App\Models\ProductElement;
use App\Models\ProductReview;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = get_products_paginate(20);
        
        return view('profile.shop.products',['products'=>$products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        session()->put('images.current',[]);

        return view('profile.product.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function show($shopProduct)
    {

        $product = Product::where('sku',$shopProduct)
        ->leftJoin('product_details','products.id','=','product_details.product_id')
        ->leftJoin('product_elements','products.id','=','product_elements.product_id')
        ->select('products.*','product_details.cycle','product_details.width','product_details.height','product_details.watering','product_details.sun_exposure','product_details.origin','product_details.color','product_details.pet_friendly','product_details.poisonous','product_details.edible','product_details.suitable_location','product_details.maintenance','product_details.growth_rate','product_details.flowering_season','product_details.fruiting_season','product_details.fertilizer','product_details.humidity','product_details.soil','product_details.hardiness','product_details.pruning','product_elements.description')
        ->first();

        if ($product) {

            return view('profile.shop.product',['product'=>$product]);

        }else{

            return redirect('user/shop/product');

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopProduct  $shopProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = Product::limit(1)->where('id',$id)->where('store_id',get_store()->first()->id)->delete();

        ProductDetail::where('product_id',$id)->delete();
        ProductDimension::where('product_id',$id)->delete();
        ProductElement::where('product_id',$id)->delete();
        Storage::disk('public')->deleteDirectory('marketplace/'.$id);

    }

    public function success(){

        if (session('product')) {
            $product = session('product');
            session()->forget('product');
            return view('profile.product.store',['product'=>$product]);
        }else{
            return redirect('user/shop/product/create');
        }
        
    }

    public function import_product(){

        return view('profile.shop.import_products');

    }

    public function create_etsy($id){

        return view('profile.product.create-etsy',['id'=>$id]);

    }

}
