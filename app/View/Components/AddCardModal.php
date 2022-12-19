<?php

namespace App\View\Components;

use Illuminate\View\Component;
// use File;

class AddCardModal extends Component
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
        // $country = json_decode(File::get(public_path('storage/json/country.json')), true); 
        return view('components.add-card-modal');
    }
}
