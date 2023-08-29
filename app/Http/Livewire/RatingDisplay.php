<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RatingDisplay extends Component
{

    public $count;
    public $ratings;
    public $size;

    public function render()
    {
        return view('livewire.rating-display');
    }
}
