<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyPlant;

class MyPlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.user.my-plants.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('profile.user.my-plants.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MyPlant  $myPlant
     * @return \Illuminate\Http\Response
     */
    public function show(MyPlant $myPlant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MyPlant  $myPlant
     * @return \Illuminate\Http\Response
     */
    public function edit(MyPlant $myPlant)
    {
        return view('profile.user.my-plants.edit',['plant'=>$myPlant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MyPlant  $myPlant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyPlant $myPlant)
    {
        //
    }

}
