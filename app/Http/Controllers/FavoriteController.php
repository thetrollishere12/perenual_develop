<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $favorites = Favorite::where('favorites.user_id','=',Auth::id())->join('products','products.sku','=','favorites.sku')->get();

        return view('profile.user.favorite',['favorites'=>$favorites]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'sku' => 'required',
            'link' => 'required'
        ]);

        if ($request->ajax()) {

            $product = favorite::where('user_id',Auth::id())
            ->where('sku',$request->sku)
            ->get(); 

            if (count($product)===0) {

                $fav = new Favorite;
                $fav->user_id = Auth::id();
                $fav->sku = $request->sku;
                $fav->link = $request->link;
                $fav->save();

                return response()->json(['status'=>'valid','message'=>'Product Added To Favorite'],200);

            }else{
                return response()->json(['status'=>'error','message'=>'Product Already Favorited'],400);
            }
        }else{
            return response()->json(['status'=>'error','message'=>'Please Log In'],400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $this->validate($request, [
            'sku' => 'required'
        ]);

        if ($request->ajax()) {

            Favorite::where('sku','=',$request->sku)->where('user_id','=',Auth::id())->delete();

        }else{
            return response()->json(['status'=>'error','message'=>'Please Try Again'],400);
        }
    }
}
