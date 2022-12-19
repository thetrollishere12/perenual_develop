<?php

namespace App\Http\Livewire\PlantSearch;

use Livewire\Component;
use App\Models\Species;

class PlantSearchInput extends Component
{

    public $search;

    public function render()
    {
        $species = Species::all();
        return view('livewire.plant-search.plant-search-input',['species'=>$species]);
    }
}
