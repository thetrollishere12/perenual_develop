<?php

namespace App\Http\Livewire\Search\Component;

use Livewire\Component;

class SpeciesCareInfo extends Component
{

    public $queries;
    public $display;

    public function render()
    {
        return view('livewire.search.component.species-care-info');
    }
}
