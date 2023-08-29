<?php

namespace App\Http\Livewire\Admin\Species;

use Livewire\Component;
use App\Models\Species;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AddTags extends Component
{
    use Actions;
    use WithPagination;
    
    public $tag;

    public function addTags($id){

        Species::where('id',$id)->update([
            'tags'=>array_map('trim', explode(',', $this->tag[$id]))
        ]);

        $this->tag[$id] = "";

        return $this->notification([
            'title'       => 'Saved!',
            'description' => 'Tags was successfully saved',
            'icon'        => 'success',
        ]);

    }

    public function render()
    {
        return view('livewire.admin.species.add-tags',['species'=>Species::paginate(24)]);
    }
}
