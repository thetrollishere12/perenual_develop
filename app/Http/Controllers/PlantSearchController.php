<?php

namespace App\Http\Controllers;

use App\Models\Species;
class PlantSearchController extends Controller
{
    public function index(){
        return view('plant-search.index');
    }

    public function show($id){
        return view('plant-search.show',['species'=>Species::find($id)]);
    }
}
