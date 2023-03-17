<?php

namespace App\Http\Controllers;

use App\Models\Species;

class ResultController extends Controller
{
    public function view(){
        $analyze=session()->get('analyze');
        if($analyze) {
            $species=Species::where('indoor',$analyze->indoor)
            ->where('watering',$analyze->watering)
            ->where('flowers',$analyze->flower)
            ->where('sunlight','LIKE','%'.$analyze->sunlight.'%')
            ->where('origin','LIKE','%'.$analyze->location.'%')
            ->where('rare',$analyze->rare)
            ->first();
            return view('result',compact('species'));
        }
        else abort(404);
    }
}
