<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShopProfilePhoto extends Component
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

        $store = get_store()->first();

        return view('components.shop-profile-photo',['store'=>$store]);

    }
}
