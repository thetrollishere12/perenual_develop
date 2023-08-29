<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Storage;

class BillingAddress extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $country = json_decode(Storage::disk('local')->get('json/country.json'), true);
        return view('components.billing-address',['json'=>$country]);
    }
}
