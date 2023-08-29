<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Auth;
use Redirect;
class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view('profile.user.address');
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
            'name' => 'required|max:100',
            'line1' => 'required|string|max:100',
            'line2' => 'nullable|string|max:100',
            'country' => 'required|max:100',
            'state_county_province_region' => 'required',
            'city' => 'required|max:100',
            'postal_zip' => 'required|max:100'
        ]);


        $addresses = Address::where('user_id',Auth::user()->id)->get();

        $address = new Address;
        $address->user_id = Auth::user()->id;
        $address->name=$request->name;
        $address->city=$request->city;
        $address->country=$request->country;
        $address->line1=$request->line1;
        $address->line2=$request->line2;
        $address->postal_zip=$request->postal_zip;
        $address->state_county_province_region=$request->state_county_province_region;
        if ($addresses->count() == 0) {
            $address->default=true;
        }
        $address->save();

        return redirect()->back()->with('success', 'Address Added'); 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Address $address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        Address::where('user_id',Auth::user()->id)->update([
            'default'=>false
        ]);

        $address->update([
            'default'=>true
        ]);

        return redirect()->back()->with('success', 'Address Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {

        if ($address->default == true) {
            return back()->withErrors(['Cannot delete default address']);
        }

        $address->delete();
        return Redirect::back();
    }
}
