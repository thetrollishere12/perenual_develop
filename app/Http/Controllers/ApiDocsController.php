<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Species;
use Auth;

use App\Models\ApiLog;

class ApiDocsController extends Controller
{
    public function index(){

        $species = Species::all()->count();
        
        $key = "[YOUR-API-KEY]";

        if (Auth::user() && Auth::user()->api_key()->first()) {
            $key = Auth::user()->api_key()->first()->key;
        }

        return view('api.intro',['species'=>$species,'key'=>$key]);
    }

    public function identify(){
        
        $key = "[YOUR-API-KEY]";

        if (Auth::user() && Auth::user()->api_key()->first()) {
            $key = Auth::user()->api_key()->first()->key;
        }

        // return view('api.identify.index',['key'=>$key]);
        return view('api.identify.temporary',['key'=>$key]);
    }

    public function logs(){

        return view('api.logs');

    }

}
