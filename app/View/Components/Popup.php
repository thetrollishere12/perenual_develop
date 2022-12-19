<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Popup extends Component
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

        if ( !\Cookie::get('popup') && env('POPUP') == true) {
            \Cookie::queue('popup', true, 60 * 24 * 1);
            return view('components.popup');
        }

    }
}
