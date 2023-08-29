<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyPlant;

class PlantCommunityController extends Controller
{
    public function index(){

        $plants = MyPlant::all();

        foreach($plants as $plant){
            $plant->user = user_details($plant->user_id);
        }

        return view('plant-community.index',['plants'=>$plants]);

    }
}
