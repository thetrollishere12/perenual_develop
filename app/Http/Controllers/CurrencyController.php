<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function currency(Request $request){
            
        $array = ['CAD','USD'];

        if(in_array($request->currency,$array))
        {
          session()->put('currency',$request->currency);
        }
 
    }

    public function country(Request $request){
    
        $array = ['CA','US'];

        if(in_array($request->country,$array))
        {
          session()->put('country',$request->country);
        }
 
    }

}
