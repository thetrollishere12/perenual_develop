<?php

namespace App\Http\Livewire\Admin\Marketing\Reddit;

use Livewire\Component;
use App\Models\Species;
use Livewire\WithPagination;
class SpeciesExperience extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.marketing.reddit.species-experience',[
            'species'=>Species::where('indoor',true)->where('default_image','!=',null)->paginate(10)
        ]);
    }
}
